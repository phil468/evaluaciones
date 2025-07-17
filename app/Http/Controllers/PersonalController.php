<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Cargo;
use App\Models\Empresa;
use App\Models\Personal;
use App\Models\Planilla;
use App\Models\TipoDePersonal;
use App\Models\TipoDeTrabajador;
use App\Models\TipoDePuestoHasNivelJerarquico;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PersonalController extends Controller
{    
    public $token = null;
    
    public function getData(Request $request)
    {
        if (Gate::denies('ver-personal')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        try {
            $personal = Personal::with([
                'empresa', 'gerencia', 'subgerencia', 'sede', 
                'area', 'cargo', 'planilla', 'tipo_trabajador', 'tipo_personal', 'superior'
            ])
            ->where('cesado', false)
            ->get();

            return response()->json($personal);

        } catch (\Exception $e) {
            Log::error('Error al obtener datos de personal: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'message' => 'Error al obtener datos de personal',
                'error' => $e->getMessage(),
                'trace' => app()->environment('local') ? $e->getTraceAsString() : null
            ], 500);
        }
    }

    public function obtenerResponse(string $numero) {
        // si numero == 0 entonces trae toda la información actual del personal 
		$tokenController = new ApiController();

        $this->token = $tokenController->getLastToken();

        if (isset($this->token)) {
			if ($tokenController->checkTokenExpiration($this->token)) {
                $res = $tokenController->login();
				$this->evaluarResultado($res);
            } 
		} else {
			$res = $tokenController->login();
			$this->evaluarResultado($res);
		}

        return $response2  = Http::withHeaders([
            'Authorization' => 'Bearer '.$this->token->access_token,
        ])->get(config('app.url_api').'api/manager/capacitaciones/personal/'.$numero);

    }

    public function actualizarPersonalNisira(string $numero) 
    {
        $numero = trim($numero);
        $message='';
        $response2 = $this->obtenerResponse($numero);

        if ($response2->successful()) {
            $data = $response2->json();
            
            if(!$data) {
                if($numero == 0) {
                    // dd('Se consultó en el ERP NISIRA. Consulta general no encontrada.');
                    $message = 'Se consultó en el ERP NISIRA. Consulta general no encontrada.';
                    return [
                        'res' => false,
                        'message' => $message
                    ];
                } else {
                    // dd('Se consultó en el ERP NISIRA. DNI '. $numero.' no encontrado.');
                    $message = 'Se consultó en el ERP NISIRA. DNI '. $numero.' no encontrado.';
                    return [
                        'res' => false,
                        'message' => $message
                    ];
                }
            } else {
                // $row=$data[0];
                // dd($data);
                $chunks = array_chunk($data, 100);

                foreach ($chunks as $chunkedData) {
                    foreach ($chunkedData as $row) {
                    //Recuperando o Insertando Empresa
                    if(!empty(trim($row['IDEMPRESA']))){
                        $empresa = Empresa::updateOrCreate(
                            ['name' => trim($row['empresa'])],
                            ['estado' => 1]
                        );
                    }

                    //Recuperando o Insertando CARGO
                    if(!empty(trim($row['IDCARGO']))){
                        $cargo = Cargo::updateOrCreate(
                            ['idcargo_nisira' => trim($row['IDCARGO']), 'empresa_id' => $empresa->id],
                            ['name' => trim($row['cargo']) , 'estado' => 1]
                        );
                    } else {
                        $cargo =  null;
                    }

                    //Recuperando o Insertando PLANILLA
                    if(!empty(trim($row['IDPLANILLA']))){
                        $planilla = Planilla::updateOrCreate(
                            ['idplanilla_nisira' => trim($row['IDPLANILLA']), 'empresa_id' => $empresa->id],
                            ['name' => trim($row['planilla']) , 'estado' => 1]
                        );
                    } else {
                        $planilla =  null;
                    }

                    //Recuperando o Insertando tipo de trabajador
                    if(!empty(trim($row['IDTIPOTRABAJADOR']))){
                        $tipotrabajador = TipoDeTrabajador::updateOrCreate(
                            ['idtipotrabajador_nisira' => trim($row['IDTIPOTRABAJADOR']), 'empresa_id' => $empresa->id],
                            ['name' => trim($row['TIPOTRABAJADOR']) , 'estado' => 1]
                        );
                    } else {
                        $tipotrabajador =  null;
                    }

                    //Recuperando o Insertando tipo de Personal
                    if(!empty(trim($row['IDTIPOPERSONAL']))){
                        $tipopersonal = TipoDePersonal::updateOrCreate(
                            ['idtipopersonal_nisira' => trim($row['IDTIPOPERSONAL']), 'empresa_id' => $empresa->id],
                            ['name' => trim($row['tipopersonal']) , 'estado' => 1]
                        );
                    } else {
                        $tipopersonal =  null;
                    }

                    //Recuperando o Insertando IDCCOSTO
                    if(!empty(trim($row['IDCCOSTO']))){
                        $area = Area::firstOrCreate(
                            ['idccosto_nisira' => trim($row['IDCCOSTO']), 'empresa_id' => $empresa->id],
                            ['name' => trim($row['CENTRO_COSTO']),'centro_costo' => trim($row['CENTRO_COSTO']) , 'estado' => 1]
                        );
                    } else {
                        $area =  null;
                    }

                    $personal = Personal::firstOrNew(['dni' => trim($row['NRODOCUMENTO'])]);

                    $personal->name = mb_strtoupper(trim($row['nombrecompleto']));
                    $personal->nombres = mb_strtoupper(trim($row['NOMBRES']));
                    $personal->apellido_paterno = mb_strtoupper(trim($row['A_PATERNO']));
                    $personal->apellido_materno = mb_strtoupper(trim($row['A_MATERNO']));
                    if($empresa != null) {
                        $personal->empresa_id = $empresa->id;
                    }
                    if($cargo != null) {
                        $personal->cargo_id = $cargo->id;
                    }
                    if($planilla != null) {
                        $personal->planilla_id = $planilla->id;
                    }
                    if($tipotrabajador != null) {
                        $personal->tipo_de_trabajador_id = $tipotrabajador->id;
                    }
                    if($tipopersonal != null) {
                        $personal->tipo_de_personal_id = $tipopersonal->id;
                    }
                    if($area != null && $personal->area_id == null) {
                        $personal->area_id = $area->id;
                    }
                    $personal->sexo = isset($row['sexo'])?trim($row['sexo']):NULL;
                    $personal->estado = 1;
                    $personal->cesado = 0;
                    $personal->importado = 1;
                    $personal->fecha_cese = NULL;
                    $personal->fecha_ingreso  = trim($row['FECHA_INGRESO']) == '' ? NULL : (Carbon::createFromFormat('Y-m-d H:i:s.u', trim($row['FECHA_INGRESO']))->toDateString());
                    
                    if ($personal->isDirty()) {
                        // si el personal es selccionado no se actualiza ni se guarda
                        if (!$personal->seleccionado) {
                            $personal->save();
                            $message = $message.'Se ingresaron/actualizaron los datos del trabajador '.$personal->name.'.\n';
                        } else {
                            // $message = $message.'No se ingresaron/actualizaron los datos del trabajador '.$personal->name.' por estar seleccionado.\n';
                        }
                    } else {
                        // $message = $message.'No se ingresaron/actualizaron los datos del trabajador '.$personal->name.'.\n';
                    }

                    if($personal) {
                        $res = true;
                        // if ($numero != 0) {
                        // }

                    // $planillaController = new PlanillaController();
                    // $messageActualizarPlanilla = $planillaController->actualizarNombreParaTodos();
                    // $tipopersonalController = new TipoDePersonalController();
                    // $messageActualizarTipoDePersonal = $tipopersonalController->actualizarNombreParaTodos();
                    // $tipotrabajadorController = new TipoDeTrabajadorController();
                    // $messageActualizarTipoDeTrabajador = $tipotrabajadorController->actualizarNombreParaTodos();
                    }
                }
            }
                
                // if ($numero == 0) {
                //     $message = 'Se ingresaron/actualizaron '.count($data).' personas.<br>';
                // }

                return 
                [
                    'res' => true,
                    'message' => $message
                    // .$messageActualizarPlanilla
                    // .$messageActualizarTipoDePersonal
                    // .$messageActualizarTipoDeTrabajador
                ];
                // if($numero != 0) {
                //     if ($personal) {
                //         // dd('DNI '. $numero.' importados desde NISIRA. Datos de usuario seleccionados.');
                //         // $this->edit($personal->id);
                //         session()->flash('message-busqueda-dni', 'DNI '. $numero.' importados desde NISIRA.');
                //         // $this->emit('alert-success');
                //         // $this->listarSelects();
                //     } else {
                //         // dd('DNI '. $numero.' no encontrado en la lista de personal. Se consultaron los datos de NISIRA pero hubo un error en la inserción/lectura de los datos');
                //         session()->flash('message-busqueda-dni', 'DNI '. $numero.' no encontrado en la lista de personal. Se consultaron los datos de NISIRA pero hubo un error en la inserción/lectura de los datos');
                //         // $this->emit('alert-danger');
                //         // Manejar el código de estado de error
                //     }
                // }
            }

        } else {
            // La solicitud no fue exitosa, manejar el error
            $statusCode = $response2->status();
            
            if($numero == 0) {
                // dd('message-busqueda-dni', 'Consulta general devolvió error. Error: '.$statusCode);
                $message = 'Consulta general devolvió error. Error: '.$statusCode;
            } else {
                // dd('DNI '. $numero.' no encontrado en la lista de personal ni en los registros de NISIRA. Error: '.$statusCode);
                $message = 'DNI '. $numero.' no encontrado en la lista de personal ni en los registros de NISIRA. Error: '.$statusCode;
            }

            return [
                'res' => false,
                'message' => $message
            ];
            // $this->emit('alert-danger');
            // Manejar el código de estado de error
        }
    }

    public function evaluarResultado($res) {
		if($res['statusCode'] == 200) {
			$this->token = $res['token'];
		} else {
			$this->token = null;
            $message = 'No se tiene acceso al servidor del API. Error: '.$res['statusCode'];
            return [
                'res' => false,
                'message' => $message
            ];
        }
	}

    public function actualizarEstadoParaTodos() {
        $message = 'Se actualizaron los estados de los trabajadores.<br>';
        // Recorre todos los registros de tu modelo
        $modelo = Personal::all(); // Reemplaza 'TuModelo' por el nombre de tu modelo
        $jsonData = ($this->obtenerResponse(0)->json()); // Obtiene el JSON de la API
        // json_decode($jsonData, true); // Convierte el JSON a un array asociativo
    
        // $modelo->actualizarEstadoParaTodos($jsonData);

        // Create an array to store the DNIs from the JSON data
        $jsonDnis = [];

        // Extract the DNIs from the JSON data and store them in the array
        foreach ($jsonData as $jsonRegistro) {
            if (isset($jsonRegistro['NRODOCUMENTO'])) {
                $jsonDnis[] = $jsonRegistro['NRODOCUMENTO'];
            }
        }

        // Update the state in the model for each record
        foreach ($modelo as $registro) {
            $dni = $registro->dni;
            $registro->cesado = in_array($dni, $jsonDnis) ? 0 : 1;

            if ($registro->isDirty('cesado')) {
                if ($registro->cesado) {
                    $registro->seleccionado = 0;
                }
                $registro->save();
                $message .= 'Se actualizó el estado de cese de: ' . $registro->name . ' - DNI ' . $dni . ($registro->cesado ? ' CESADO' : ' NO CESADO') . '<br>';
            }
        }

        return [
            'res' => true,
            'message' => $message
        ];
//        return $message;
    }

    public function ingresarDNI($dni) {
        $dni = trim($dni);
        $personal = Personal::create([
			'dni' => $dni,
			'estado' => 1,
			'importado' => 0,
		]);

		return $personal->id;
    }

    public function searchComite(Request $request)
    {
        $search = $request->search;
        $page = $request->page ?? 1;
        $per_page = 10;

        $personas = Personal::where('name', 'LIKE', "%$search%")
            ->select('id', 'name as text')
            ->orderBy('name')
            ->skip(($page - 1) * $per_page)
            ->take($per_page)
            ->get();

        $count = Personal::where('name', 'LIKE', "%$search%")->count();

        return response()->json([
            'results' => $personas,
            'pagination' => [
                'more' => ($page * $per_page) < $count
            ]
        ]);
    }

    /**
     * Obtiene los detalles de un personal para autocompletar los campos del formulario
     */
    public function getPersonalDetails(Request $request)
    {
        $personalId = $request->personal_id;
        if (!$personalId) {
            return response()->json(['error' => 'ID de personal no proporcionado'], 400);
        }

        $personal = Personal::with(['area', 'cargo.tipoDePuesto', 'superior'])
            ->find($personalId);

        if (!$personal) {
            return response()->json(['error' => 'Personal no encontrado'], 404);
        }

        // Obtener nivel jerárquico relacionado con el puesto usando TipoDePuestoHasNivelJerarquico
        $nivelJerarquicoId = null;
        if ($personal->cargo && $personal->cargo->tipo_de_puesto_id) {
            // Buscar en la tabla de relación
            $tipoPuestoNivel = TipoDePuestoHasNivelJerarquico::where('tipo_de_puesto_id', $personal->cargo->tipo_de_puesto_id)
                ->first();
                
            if ($tipoPuestoNivel) {
                $nivelJerarquicoId = $tipoPuestoNivel->id;
            }
        }

        // Preparar respuesta con los datos del personal
        $response = [
            'area_id' => $personal->area_id,
            'puesto_id' => $personal->cargo_id,
            'nivel_jerarquico_id' => $nivelJerarquicoId,
            'superior_id' => $personal->reporta_a,
            'estado' => (bool)$personal->estado,
            'cesado' => (bool)$personal->cesado,
            'superior' => $personal->superior ? [
                'id' => $personal->superior->id,
                'text' => $personal->superior->name . ' (' . $personal->superior->dni . ')'
            ] : null
        ];

        return response()->json($response);
    }

    public function verificarCorreo(Request $request)
    {
        $personal_ids = $request->input('personal_ids');
        $personal_sin_correo = Personal::whereIn('id', $personal_ids)
            ->whereNull('correo_empresa')
            ->pluck('name');

        return response()->json(['sin_correo' => $personal_sin_correo]);
    }

    /**
     * Muestra la página de Personal con tabla Tabulator
     *
     * @return \Illuminate\View\View
     */
    public function indexTabulator()
    {
        if (Gate::denies('ver-personal')) {
            abort(403, 'No autorizado');
        }
        
        return view('personal.index');
    }
        
    // Métodos para Select2 AJAX
    public function select2Empresa(Request $request) {
        $q = $request->q;
        $results = Empresa::where('name', 'like', "%$q%")->select('id', 'name as text')->limit(20)->get();
        return response()->json(['results' => $results]);
    }
    public function select2Gerencia(Request $request) {
        $q = $request->q;
        $results = Area::where('name', 'like', "%$q%")->where('name', 'like', "%gerencia%")->select('id', 'name as text')->limit(20)->get();
        return response()->json(['results' => $results]);
    }
    public function select2Area(Request $request) {
        $q = $request->q;
        $results = Area::where('name', 'like', "%$q%")->select('id', 'name as text')->limit(20)->get();
        return response()->json(['results' => $results]);
    }
    public function select2Cargo(Request $request) {
        $q = $request->q;
        $results = Cargo::where('name', 'like', "%$q%")->select('id', 'name as text')->limit(20)->get();
        return response()->json(['results' => $results]);
    }
    public function select2Reporta(Request $request) {
        $q = $request->q;
        $exclude = $request->exclude;
        $query = Personal::where('name', 'like', "%$q%");
        if ($exclude) $query->where('id', '!=', $exclude);
        $results = $query->select('id', 'name as text')->limit(20)->get();
        return response()->json(['results' => $results]);
    }

    // CRUD REST (index, store, update, destroy, show)
    public function index(Request $request) {
        // Devuelve la vista principal
        return view('personal.index');
    }
    public function data(Request $request) {
        // Devuelve los datos para Tabulator (puedes agregar paginación, filtros, etc.)
        $personals = Personal::with(['empresa', 'gerencia', 'area', 'cargo', 'superior'])->get();
        return response()->json($personals);
    }

    // public function store(Request $request) {
    //     $data = $request->all();
    //     $personal = Personal::create($data);
    //     return response()->json(['success' => true, 'data' => $personal]);
    // }

    // public function show($id) {
    //     $personal = Personal::with(['empresa', 'gerencia', 'area', 'cargo', 'superior'])->findOrFail($id);
    //     return response()->json($personal);
    // }
    // public function update(Request $request, $id) {
    //     $personal = Personal::findOrFail($id);
    //     $personal->update($request->all());
    //     return response()->json(['success' => true, 'data' => $personal]);
    // }

    // public function destroy($id) {
    //     $personal = Personal::findOrFail($id);
    //     $personal->delete();
    //     return response()->json(['success' => true]);
    // }

    
    public function show($id)
    {
        if (Gate::denies('ver-personal')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        try {
            $personal = Personal::with([
                'empresa', 'gerencia', 'subgerencia', 'sede', 
                'area', 'cargo', 'planilla', 'tipo_trabajador', 'tipo_personal'
            ])->findOrFail($id);

            return response()->json($personal);
        } catch (\Exception $e) {
            Log::error('Error al mostrar personal: ' . $e->getMessage());
            return response()->json(['message' => 'No se encontró el personal'], 404);
        }
    }

    /**
     * Almacena un nuevo personal
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        if (Gate::denies('crear-personal')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'dni' => 'required|string|unique:personal,dni',
            'name' => 'required|string',
            'nombres' => 'required|string',
            'apellido_paterno' => 'required|string',
            'apellido_materno' => 'nullable|string',
            'empresa_id' => 'required|exists:empresas,id',
            'gerencia_id' => 'nullable|exists:gerencias,id',
            'subgerencia_id' => 'nullable|exists:subgerencias,id',
            'sede_id' => 'nullable|exists:sedes,id',
            'area_id' => 'nullable|exists:areas,id',
            'cargo_id' => 'nullable|exists:cargos,id',
            'correo_empresa' => 'nullable|email',
            'celular_empresa' => 'nullable|string',
            'correo_personal' => 'nullable|email',
            'telefono_personal' => 'nullable|string',
            'celular_personal' => 'nullable|string',
            'estado' => 'nullable|boolean',
            'genero' => 'nullable|string|in:M,F',
            'fecha_ingreso' => 'nullable|date',
            'tipo_de_trabajador_id' => 'nullable|exists:tipo_de_trabajador,id',
            'tipo_de_personal_id' => 'nullable|exists:tipo_de_personal,id',
            'planilla_id' => 'nullable|exists:planillas,id',
            'cesado' => 'nullable|boolean',
            'fecha_cese' => 'nullable|date',
            'reporta_a' => 'nullable|exists:personal,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $personal = Personal::create($request->all());
            return response()->json($personal, 201);
        } catch (\Exception $e) {
            Log::error('Error al crear personal: ' . $e->getMessage());
            return response()->json(['message' => 'Error al crear personal'], 500);
        }
    }

    /**
     * Actualiza un personal específico
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        if (Gate::denies('editar-personal')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $personal = Personal::findOrFail($id);
    
        // Determinar si es una actualización parcial (solo seleccionado)
        $isPartialUpdate = $request->has('seleccionado') && count($request->all()) == 1;
        
        if ($isPartialUpdate) {
            // Para edición inline de seleccionado, solo validamos ese campo
            $validator = Validator::make($request->all(), [
                'seleccionado' => 'boolean',
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'dni' => 'required|string|unique:personal,dni,' . $id,
                'name' => 'required|string',
                'nombres' => 'required|string',
                'apellido_paterno' => 'required|string',
                'apellido_materno' => 'nullable|string',
                'empresa_id' => 'required|exists:empresas,id',
                'gerencia_id' => 'nullable|exists:gerencias,id',
                'subgerencia_id' => 'nullable|exists:subgerencias,id',
                'sede_id' => 'nullable|exists:sedes,id',
                'area_id' => 'nullable|exists:areas,id',
                'cargo_id' => 'nullable|exists:cargos,id',
                'correo_empresa' => 'nullable|email',
                'celular_empresa' => 'nullable|string',
                'correo_personal' => 'nullable|email',
                'telefono_personal' => 'nullable|string',
                'celular_personal' => 'nullable|string',
                'estado' => 'nullable|boolean',
                'genero' => 'nullable|string|in:M,F',
                'fecha_ingreso' => 'nullable|date',
                'tipo_de_trabajador_id' => 'nullable|exists:tipo_de_trabajador,id',
                'tipo_de_personal_id' => 'nullable|exists:tipo_de_personal,id',
                'planilla_id' => 'nullable|exists:planillas,id',
                'cesado' => 'nullable|boolean',
                'fecha_cese' => 'nullable|date',
                'reporta_a' => 'nullable|exists:personal,id',
            ]);
            
        }

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            // $personal = Personal::findOrFail($id);
            // $personal->update($request->all());
            // return response()->json($personal);

            // Actualizar según el tipo de actualización
            if ($isPartialUpdate) {
                $personal->seleccionado = $request->seleccionado;
                // dd($personal->seleccionado, $request->seleccionado);
                // dd($personal);
                $personal->save();
                // dd($personal);
            } else {
                $personal->update($request->all());
            }
            
            return response()->json(['success' => true, 'message' => 'Personal actualizado correctamente']);

        } catch (\Exception $e) {
            Log::error('Error al actualizar personal: ' . $e->getMessage());
            return response()->json(['message' => 'Error al actualizar personal'], 500);
        }
    }

    /**
     * Elimina un personal específico
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        if (Gate::denies('eliminar-personal')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        try {
            $personal = Personal::findOrFail($id);
            $personal->delete();
            return response()->json(['message' => 'Personal eliminado correctamente']);
        } catch (\Exception $e) {
            Log::error('Error al eliminar personal: ' . $e->getMessage());
            return response()->json(['message' => 'Error al eliminar personal'], 500);
        }
    }

    public function marcarSeleccionados()
    {
        if (Gate::denies('editar-personal')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        // Obtener la campaña actual
        $campaniaActual = \App\Models\Campania::where('es_campania_actual', true)->first();
        $evaluaciones = $campaniaActual
            ? \App\Models\Evaluacione::where('campania_id', $campaniaActual->id)->get()
            : collect();

        $idsSeleccionados = [];
        if ($campaniaActual && $evaluaciones->count()) {
            $fechaCortes = $evaluaciones->pluck('fecha_corte')->filter()->sort()->values();
            $personales = \App\Models\Personal::with('planilla')->where('cesado', 0)->get();
            foreach ($personales as $personal) {
                // Planilla debe empezar con E
                $planillaOk = $personal->planilla && str_starts_with($personal->planilla->idplanilla_nisira, 'E');
                // Fecha de ingreso debe ser menor a alguna fecha de corte
                $fechaIngreso = $personal->fecha_ingreso;
                $cumpleFecha = $fechaCortes->contains(function($fechaCorte) use ($fechaIngreso) {
                    return $fechaIngreso && $fechaCorte && $fechaCorte > $fechaIngreso;
                });
                if ($planillaOk && $cumpleFecha) {
                    $idsSeleccionados[] = $personal->id;
                }
            }
        }

        if (empty($idsSeleccionados)) {
            return response()->json([
                'message' => 'No se encontraron registros que cumplan con las condiciones para ser seleccionados'
            ]);
        }

        // Actualizar los registros seleccionados
        $actualizados = Personal::whereIn('id', $idsSeleccionados)
            ->update(['seleccionado' => true]);

        return response()->json([
            'message' => "Se han marcado {$actualizados} registros como seleccionados",
            'seleccionados' => $idsSeleccionados
        ]);
    }

    public function actualizacionGeneralCompleta()
    {
        // Primero actualizar la información de todos los empleados
        $resultadoActualizacion = $this->actualizarPersonalNisira('0');
        
        // Luego actualizar los estados (cesado)
        $resultadoEstados = $this->actualizarEstadoParaTodos();
        
        // Registrar la actualización en el historial
        $this->registrarActualizacion([
            'tipo' => 'general',
            'resultado_actualizacion' => $resultadoActualizacion,
            'resultado_estados' => $resultadoEstados,
            'ejecutado_por' => auth()->check() ? auth()->user()->id : null,
            'ejecutado_por_nombre' => auth()->check() ? auth()->user()->name : 'Sistema',
            'ejecutado_por_sistema' => auth()->check() ? false : true
        ]);
        
        return [
            'success' => true,
            'message' => 'Actualización general completada',
            'detalles' => [
                'actualizacion' => $resultadoActualizacion,
                'estados' => $resultadoEstados
            ]
        ];
    }

    // Método mejorado para actualizar por DNI individual
    public function actualizacionIndividual($dni)
    {
        // Validar que el DNI tenga 8 dígitos
        if (!preg_match('/^\d{8}$/', $dni)) {
            return [
                'success' => false,
                'message' => 'El DNI debe tener 8 dígitos numéricos'
            ];
        }
        
        $resultado = $this->actualizarPersonalNisira($dni);
        // dd(auth()->user());
        // Registrar la actualización en el historial
        $this->registrarActualizacion([
            'tipo' => 'individual',
            'dni' => $dni,
            'resultado' => $resultado,
            'ejecutado_por' => auth()->user()->id,
            'ejecutado_por_nombre' => auth()->user()->name,
        ]);
        
        return [
            'success' => $resultado['res'],
            'message' => $resultado['message']
        ];
    }

    // Método para buscar un personal por DNI (combinando DB y API)
    public function buscarPersonalPorDNI(Request $request)
    {
        $dni = trim($request->dni);
        
        // Validar que el DNI tenga 8 dígitos
        if (!preg_match('/^\d{8}$/', $dni)) {
            return response()->json([
                'success' => false,
                'message' => 'El DNI debe tener 8 dígitos numéricos'
            ]);
        }
        
        // Buscar primero en la base de datos
        $personal = Personal::where('dni', $dni)->first();
        
        if ($personal) {
            return response()->json([
                'success' => true,
                'encontrado_en' => 'base_de_datos',
                'personal' => $personal->load(['empresa', 'gerencia', 'area', 'cargo', 'superior'])
            ]);
        }
        
        // Si no existe en la base de datos, buscar en la API
        $resultadoAPI = $this->actualizarPersonalNisira($dni);
        
        if ($resultadoAPI['res']) {
            // Si se encontró y guardó correctamente, obtener el personal recién creado
            $personal = Personal::where('dni', $dni)->first();
            
            if ($personal) {
                return response()->json([
                    'success' => true,
                    'encontrado_en' => 'api',
                    'personal' => $personal->load(['empresa', 'gerencia', 'area', 'cargo', 'superior']),
                    'message' => 'Personal encontrado en el sistema externo y guardado correctamente'
                ]);
            }
        }

        // Si no se encontró en ningún lado
        return response()->json([
            'success' => false,
            'message' => 'No se encontró el personal con el DNI proporcionado'
        ]);
    }

    // Método para registrar las actualizaciones en el historial
    private function registrarActualizacion($datos)
    {
        try {
            \App\Models\ActualizacionPersonal::create([
                'tipo' => $datos['tipo'],
                'detalles' => json_encode($datos),
                'ejecutado_por' => $datos['ejecutado_por'] ?? null,
                'ejecutado_por_sistema' => $datos['ejecutado_por_sistema'] ?? false,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al registrar actualización de personal: ' . $e->getMessage());
        }
    }

    public function historialActualizaciones(Request $request)
    {
        if (Gate::denies('ver-personal')) {
            abort(403, 'No autorizado');
        }
        
        $query = \App\Models\ActualizacionPersonal::with('usuario')->orderBy('created_at', 'desc');
        
        // Filtros
        if ($request->has('fecha_desde') && !empty($request->fecha_desde)) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }
        
        if ($request->has('fecha_hasta') && !empty($request->fecha_hasta)) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }
        
        if ($request->has('tipo') && !empty($request->tipo)) {
            $query->where('tipo', $request->tipo);
        }
        $actualizaciones = $query->paginate(15);
        
        return view('personal.historial-actualizaciones', compact('actualizaciones'));
    }

}
