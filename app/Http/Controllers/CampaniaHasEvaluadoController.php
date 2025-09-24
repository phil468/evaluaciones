<?php

namespace App\Http\Controllers;

use App\Exports\EvaluadosTemplateExport;
use App\Imports\EvaluadosRowsImport;
use App\Models\Area;
use App\Models\Campania;
use App\Models\CampaniaHasEvaluado;
use App\Models\Cargo;
use App\Models\ComiteCalibracion;
use App\Models\ComiteHasPersona;
use App\Models\Dominio;
use App\Models\Evaluacione;
use App\Models\EvaluadorHasEvaluado;
use App\Models\Grado;
use App\Models\NivelJerarquico;
use App\Models\Objetivo;
use App\Models\Personal;
use App\Models\Peso;
use App\Models\Respuesta;
use App\Models\ResumenRespuestasEvaluacionDesempenoCompetencia;
use App\Models\TipoDePuestoHasNivelJerarquico;
use App\Models\TipoRelacionJerarquica;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Crypt;

class CampaniaHasEvaluadoController extends Controller
{
    /**
     * Obtener evaluados por campaña.
     *
     * @param  int  $campaniaId
     * @return \Illuminate\Http\Response
     */
    public function getByCampania($campaniaId)
    {
        $evaluados = CampaniaHasEvaluado::where('campania_id', $campaniaId)
            ->with([
                // 'personal', 
                'personal.user',
                'area', 
                'puesto', 
                'tipoPuestoHasNivelJerarquico',
                'tipoPuestoHasNivelJerarquico.tipoDePuesto',
                'tipoPuestoHasNivelJerarquico.dominio',
                'tipoPuestoHasNivelJerarquico.dominio.grado',
                'tipoPuestoHasNivelJerarquico.nivelJerarquico', 
                'superior',
                'subordinados',
                'subordinados.personal',
                'paresMismoSuperior',
                'paresMismoSuperior.personal',
                'paresMismoSuperior.tipoPuestoHasNivelJerarquico', // Relación para obtener pares con el mismo superior y nivel jerárquico
                'paresMismoSuperior.tipoPuestoHasNivelJerarquico.nivelJerarquico', // Relación para obtener pares con el mismo superior y nivel jerárquico
                'paresMismoSuperior.tipoPuestoHasNivelJerarquico.tipoDePuesto', // Relación para obtener pares con el mismo superior y nivel jerárquico
                 // Relación para obtener pares con el mismo superior y nivel jerárquico
            ])
            ->get();
        // dd(response()->json($evaluados));

        return response()->json($evaluados);
    }

    /**
     * Mostrar la página de gestión de evaluados por campaña.
     *
     * @param  int  $campaniaId
     * @return \Illuminate\Http\Response
     */
    public function index($campaniaId)
    {
        $campania = Campania::findOrFail($campaniaId);
        return view('campania_has_evaluados.index', compact('campania'));
    }

    /**
     * Obtener datos para rellenar selects.
     *
     * @return \Illuminate\Http\Response
     */    
    public function getSelects(Request $request)
    {
        $areas = Area::select('id', 'name as name')->where('estado', 1)->orderBy('name')->get();
        $personal = Personal::select('id', 'name', 'dni')
        ->orderBy('name')
        ->where('deleted_at', null)
        ->where('estado', 1) // Solo personal activo
        ->where('cesado', 0) // Excluir personal cesado
        ->get();
        $puestos = Cargo::select('id', 'name')->orderBy('name')->get();
        // Obtener tipos de puesto con nivel jerárquico ordenados por nivel jerarquico y tipo de puesto
        $nivelJerarquicos = TipoDePuestoHasNivelJerarquico::with(['tipoDePuesto', 'nivelJerarquico'])
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->tipoDePuesto->name . ' - ' . $item->nivelJerarquico->name
                ];
            })
            ->sortBy(function($item) {
                return $item['name'];
            })->values();
            
        // Filtrar dominios por nivel jerárquico y campaña si se proporcionan
        $dominiosQuery = Dominio::select('id', 'name', 'nivel_jerarquico_id', 'campania_id')->orderBy('name');
        
        if ($request->has('nivel_jerarquico_id')) {
            $dominiosQuery->where('nivel_jerarquico_id', $request->nivel_jerarquico_id);
        }
        
        if ($request->has('campania_id')) {
            $dominiosQuery->where('campania_id', $request->campania_id);
        }
        
        $dominios = $dominiosQuery->get();
        
        return response()->json([
            'areas' => $areas,
            'personal' => $personal,
            'puestos' => $puestos,
            'nivel_jerarquicos' => $nivelJerarquicos,
            'dominios' => $dominios
        ]);
    }

    /**
     * Almacenar un nuevo evaluado en la campaña.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $evaluado = $this->saveOrUpdateEvaluado($data);

        // Actualizar área en Personal si corresponde
        if ($request->has('area_id') && $request->filled('area_id') && $request->has('actualizar_personal') && $request->actualizar_personal) {
            $personal = Personal::find($evaluado->personal_id);
            if ($personal) {
                $personal->area_id = $request->area_id;
                $personal->save();
            }
        }

        return response()->json([
            'message' => 'Evaluado agregado correctamente a la campaña',
            'evaluado' => $evaluado,
            'personal_actualizado' => $request->has('actualizar_personal') && $request->actualizar_personal ? true : false
        ], 201);
    }

    /**
     * Mostrar un evaluado específico.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $evaluado = CampaniaHasEvaluado::with([
            // 'personal', 'campania', 'area', 'puesto', 'tipoPuestoHasNivelJerarquico', 'dominio', 'superior'
            
                'personal.user', // Añadir relación user
                // 'personal', 
                'area', 
                'puesto', 
                'tipoPuestoHasNivelJerarquico',
                'tipoPuestoHasNivelJerarquico.tipoDePuesto',
                'tipoPuestoHasNivelJerarquico.dominio',
                'tipoPuestoHasNivelJerarquico.dominio.grado',
                'tipoPuestoHasNivelJerarquico.nivelJerarquico', 
                'superior',
                'subordinados',
                'subordinados.personal',
                'paresMismoSuperior', // Relación para obtener pares con el mismo superior y nivel jerárquico
                'paresMismoSuperior.personal',
                // 'pares'
                ])
            ->findOrFail($id);
            
        return response()->json($evaluado);
    }

    /**
     * Actualizar un evaluado en la campaña.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->except('actualizar_personal');
        $evaluado = $this->saveOrUpdateEvaluado($data, $id);

        // Actualizar área en Personal si corresponde
        if ($request->has('area_id') && $request->filled('area_id') && $request->has('actualizar_personal') && $request->actualizar_personal) {
            $personal = Personal::find($evaluado->personal_id);
            if ($personal) {
                $personal->area_id = $request->area_id;
                $personal->save();
            }
        }

        return response()->json([
            'message' => 'Evaluado actualizado correctamente',
            'evaluado' => $evaluado,
            'personal_actualizado' => $request->has('actualizar_personal') && $request->actualizar_personal ? true : false
        ]);
    }

    /**
     * Eliminar un evaluado de la campaña.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            $evaluado = CampaniaHasEvaluado::findOrFail($id);
            
            // 1. Primero obtener los IDs necesarios antes de eliminar
            $personal_id = $evaluado->personal_id;
            $campania_id = $evaluado->campania_id;
            
            // 2. Obtener los evaluadores para eliminar objetivos
            $evaluadores = EvaluadorHasEvaluado::where('evaluado_id', $personal_id)
                ->where('campania_id', $campania_id)
                ->get();
            
            // 3. Obtener comités de calibración
            $comites = ComiteCalibracion::where('personal_id', $personal_id)
                ->where('campania_id', $campania_id)
                ->get();
            
            // 4. Eliminar registros relacionados en orden correcto
            // 4.1 Eliminar objetivos
            foreach ($evaluadores as $evaluador) {
                Objetivo::where('evaluador_has_evaluado_id', $evaluador->id)->forceDelete();
            }
            
            // 4.2 Eliminar personas en comités
            if ($comites->count() > 0) {
                ComiteHasPersona::whereIn('comite_calibracion_id', $comites->pluck('id'))->forceDelete();
            }
            
            // 4.3 Eliminar respuestas
            Respuesta::where('evaluado_id', $personal_id)
                ->where('campania_id', $campania_id)
                ->forceDelete();
            
            // 4.4 Eliminar resúmenes
            ResumenRespuestasEvaluacionDesempenoCompetencia::where('personal_id', $personal_id)
                ->where('campania_id', $campania_id)
                ->forceDelete();
            
            // 4.5 Eliminar evaluadores
            EvaluadorHasEvaluado::where('evaluado_id', $personal_id)
                ->where('campania_id', $campania_id)
                ->forceDelete();
            
            // 4.6 Eliminar comités
            ComiteCalibracion::where('personal_id', $personal_id)
                ->where('campania_id', $campania_id)
                ->forceDelete();
            
            // 4.7 Finalmente eliminar el evaluado
            $evaluado->forceDelete();
            
            DB::commit();
            
            return response()->json([
                'message' => 'Evaluado eliminado correctamente de la campaña'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al eliminar evaluado: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'Error al eliminar el evaluado: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Desactivar o activar evaluación de competencias.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function toggleCompetencias(Request $request, $id)
    {
        $evaluado = CampaniaHasEvaluado::findOrFail($id);
        $habilitar = $request->input('habilitar', !$evaluado->habilitado_para_evaluacion_de_competencias);
        $motivo = $request->input('motivo');

        //formatear habilitar como boolean
        $habilitar = filter_var($habilitar, FILTER_VALIDATE_BOOLEAN);

        if ($habilitar) {
            $evaluado->habilitarCompetencias();
            $mensaje = 'Habilitado para evaluación de competencias';
        } else {
            $evaluado->deshabilitarCompetencias($motivo);
            $mensaje = 'Deshabilitado para evaluación de competencias';
        }
        
        return response()->json([
            'message' => $mensaje,
            'evaluado' => $evaluado
        ]);
    }

    /**
     * Desactivar o activar evaluación por objetivos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function toggleObjetivos(Request $request, $id)
    {
        $evaluado = CampaniaHasEvaluado::findOrFail($id);
        $habilitar = $request->input('habilitar', !$evaluado->habilitado_para_evaluacion_por_objetivos);
        $motivo = $request->input('motivo');
        
        if ($habilitar) {
            $evaluado->habilitarObjetivos();
            $mensaje = 'Habilitado para evaluación por objetivos';
        } else {
            $evaluado->deshabilitarObjetivos($motivo);
            $mensaje = 'Deshabilitado para evaluación por objetivos';
        }
        
        return response()->json([
            'message' => $mensaje,
            'evaluado' => $evaluado
        ]);
    }

    /**
     * Importar evaluados masivamente a la campaña.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function importar(Request $request)
    {
        $request->validate([
            'campania_id' => 'required|exists:campanias,id',
            'file' => 'required|file|mimes:csv,xlsx,xls'
        ]);

        $campaniaId = (int)$request->campania_id;

        $import = new EvaluadosRowsImport();
        Excel::import($import, $request->file('file'));
        $rows = $import->rows ?? collect();

        $summary = [];
        $created = 0;
        $updated = 0;
        $skipped = 0;
        $rowNumber = 2; // cabeceras en fila 1

        foreach ($rows as $row) {
            // Normalizar filas a array para usar array_key_exists sin errores
            if ($row instanceof \Illuminate\Support\Collection) {
                $row = $row->toArray();
            }

            $rowInfo = ['row' => $rowNumber, 'dni' => trim((string)($row['dni'] ?? '')), 'status' => '', 'changes' => [], 'errors' => []];

            $dni = trim((string)($row['dni'] ?? ''));
            if ($dni === '') {
                $rowInfo['status'] = 'skipped';
                $rowInfo['errors'][] = 'DNI es obligatorio.';
                $summary[] = $rowInfo; $skipped++; $rowNumber++; continue;
            }

            $personal = Personal::with(['cargo', 'user'])->where('dni', $dni)->first();
            if (!$personal) {
                $rowInfo['status'] = 'skipped';
                $rowInfo['errors'][] = "No existe personal con DNI {$dni}.";
                $summary[] = $rowInfo; $skipped++; $rowNumber++; continue;
            }

            // Buscar registro actual en campaña
            $existing = CampaniaHasEvaluado::where('personal_id', $personal->id)->where('campania_id', $campaniaId)->first();

            // Preparar valores base (mantener actuales si no vienen en el archivo)
            $data = [
                'personal_id' => $personal->id,
                'campania_id' => $campaniaId,
                'area_id' => $existing->area_id ?? $personal->area_id,
                'puesto_id' => $existing->puesto_id ?? $personal->cargo_id,
                'tipo_de_puesto_campania_id' => $existing->tipo_de_puesto_campania_id??null,
                'superior_personal_id' => $existing->superior_personal_id ?? $personal->reporta_a,
                'habilitado_para_evaluacion_de_competencias' => $existing->habilitado_para_evaluacion_de_competencias ?? 0,
                'habilitado_para_evaluacion_por_objetivos' => $existing->habilitado_para_evaluacion_por_objetivos ?? 0,
                'cesado' => $existing->cesado ?? $personal->cesado,
                'estado' => $existing->estado ?? 1,
            ];

            // AREA
            $areaNameIn = $this->norm($row['area'] ?? '');
            if ($areaNameIn !== '') {
                $area = $this->findAreaByName($areaNameIn);
                if (!$area) {
                    $rowInfo['errors'][] = "Área '{$areaNameIn}' no encontrada.";
                } else {
                    $data['area_id'] = $area->id;
                    if (!$existing || $existing->area_id !== $area->id) $rowInfo['changes'][] = 'area_id';
                }
            }

            // PUESTO
            $cargoNameIn = $this->norm($row['puesto'] ?? '');
            $tipoDePuestoId = $personal->cargo->tipo_de_puesto_id ?? null;
            // dd($tipoDePuestoId);
            if ($cargoNameIn !== '') {
                $cargo = $this->findCargoByNameActivo($cargoNameIn);
                // dd($cargo);
                if (!$cargo) {
                    $rowInfo['errors'][] = "Puesto/Cargo '{$cargoNameIn}' no encontrado.";
                } else {
                    $data['puesto_id'] = $cargo->id;
                    $tipoDePuestoId = $cargo->tipo_de_puesto_id;
                    if (!$existing || $existing->puesto_id !== $cargo->id) $rowInfo['changes'][] = 'puesto_id';
                    
                    // Resolver tipo_de_puesto_campania_id por campania + tipo_de_puesto + nivel
                    $tpnj = TipoDePuestoHasNivelJerarquico::where('campania_id', $campaniaId)
                        ->when($tipoDePuestoId, fn($q) => $q->where('tipo_de_puesto_id', $tipoDePuestoId))
                        // ->where('nivel_jerarquico_id', $nivel->id)
                        ->first();
                    // dd($tpnj);
                    if (!$tpnj) {
                        $rowInfo['errors'][] = "No existe mapeo TipoPuesto+Nivel para la campaña (revise configuración).";
                    } else {
                        $data['tipo_de_puesto_campania_id'] = $tpnj->id;
                        if (!$existing || $existing->tipo_de_puesto_campania_id !== $tpnj->id) $rowInfo['changes'][] = 'tipo_de_puesto_campania_id';
                    }
                }
            }

            // DNI SUPERIOR
            $dniSup = trim((string)($row['dni_superior'] ?? ''));
            if ($dniSup !== '') {
                $sup = Personal::where('dni', $dniSup)->first();
                if (!$sup) {
                    $rowInfo['errors'][] = "Superior con DNI {$dniSup} no encontrado.";
                } elseif ($sup->id === $personal->id) {
                    $rowInfo['errors'][] = "El superior no puede ser la misma persona.";
                } else {
                    $data['superior_personal_id'] = $sup->id;
                    if (!$existing || $existing->superior_personal_id !== $sup->id) $rowInfo['changes'][] = 'superior_personal_id';
                }
            }

            // Booleans
            if (array_key_exists('habilitado_para_evaluacion_de_competencias', $row)) {
                $val = $this->parseBool($row['habilitado_para_evaluacion_de_competencias']);
                if (!is_null($val)) {
                    $data['habilitado_para_evaluacion_de_competencias'] = $val ? 1 : 0;
                    if (!$existing || (int)$existing->habilitado_para_evaluacion_de_competencias !== (int)$data['habilitado_para_evaluacion_de_competencias']) {
                        $rowInfo['changes'][] = 'habilitado_para_evaluacion_de_competencias';
                    }
                }
            }
            if (array_key_exists('habilitado_para_evaluacion_por_objetivos', $row)) {
                $val = $this->parseBool($row['habilitado_para_evaluacion_por_objetivos']);
                if (!is_null($val)) {
                    $data['habilitado_para_evaluacion_por_objetivos'] = $val ? 1 : 0;
                    if (!$existing || (int)$existing->habilitado_para_evaluacion_por_objetivos !== (int)$data['habilitado_para_evaluacion_por_objetivos']) {
                        $rowInfo['changes'][] = 'habilitado_para_evaluacion_por_objetivos';
                    }
                }
            }

            // CORREO -> actualizar Personal.correo_empresa y User.email (único). Crear user si no existe.
            $correo = trim((string)($row['correo'] ?? ''));
            if ($correo !== '') {
                if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                    $rowInfo['errors'][] = "Correo '{$correo}' inválido.";
                } else {
                    $user = $personal->user;
                    $existsOther = User::where('email', $correo)
                        ->when($user, fn($q) => $q->where('id', '!=', $user->id))
                        ->exists();
                    
                    // evaluamos si este otro usuario tiene email null o vacío
                    $otherUser = User::where('email', $correo)
                        ->when($user, fn($q) => $q->where('id', '!=', $user->id))
                        ->first();

                    if ($otherUser && !$otherUser->personal_id) {
                        $otherUser->personal_id = $personal->id;
                        $otherUser->save();
                        // buscar si tiene rol, sino asignar el rol PERSONAL
                        if ($otherUser->roles->isEmpty()) {
                            $otherUser->assignRole('PERSONAL');
                        }
                        $user = $otherUser;
                        $existsOther = false; // ya no existe otro usuario con este email
                    }                    

                    if ($existsOther) {
                        $rowInfo['errors'][] = "El email '{$correo}' ya está en uso por otro usuario.";
                    } else {
                        // Actualizar correo empresa en Personal
                        if ($personal->correo_empresa !== $correo) {
                            $personal->correo_empresa = $correo;
                            $personal->save();
                            $rowInfo['changes'][] = 'personal.correo_empresa';
                        }
                        // Actualizar o crear User
                        if ($user) {
                            if ($user->email !== $correo) {
                                $user->email = $correo;
                                $user->save();
                                $rowInfo['changes'][] = 'user.email';
                            }
                        } else {
                            $new = new User();
                            $new->name = $personal->name;
                            $new->email = $correo;
                            // si tu tabla users tiene columna personal_id:
                            if (Schema::hasColumn('users', 'personal_id')) {
                                $new->personal_id = $personal->id;
                            }
                            $new->password = bcrypt(Str::random(16));
                            $new->save();

                            // a este personal nuevo asignarle el rol PERSONAL
                            $new->assignRole('PERSONAL');

                            $rowInfo['changes'][] = 'user.created';
                        }
                    }
                }
            }

            // Si hubo errores en la fila, no persisto cambios de CampaniaHasEvaluado
            if (!empty($rowInfo['errors'])) {
                $rowInfo['status'] = 'skipped';
                $summary[] = $rowInfo;
                $skipped++;
                $rowNumber++;
                continue;
            }

            // Guardar (create/update) usando la misma validación del controlador
            try {
                if ($existing) {
                    $this->saveOrUpdateEvaluado($data, $existing->id);
                    $rowInfo['status'] = 'updated';
                    $updated++;
                } else {
                    $this->saveOrUpdateEvaluado($data);
                    $rowInfo['status'] = 'created';
                    $created++;
                }
            } catch (\Throwable $e) {
                $rowInfo['status'] = 'skipped';
                $rowInfo['errors'][] = $e->getMessage();
                $skipped++;
            }

            $summary[] = $rowInfo;
            $rowNumber++;
        }

        return response()->json([
            'message' => 'Importación completada.',
            'created' => $created,
            'updated' => $updated,
            'skipped' => $skipped,
            'rows' => $summary,
        ]);
    }

    // Helpers de importación
    private function norm(?string $v): string
    {
        $v = trim((string)$v);
        $v = preg_replace('/\s+/', ' ', $v);
        return mb_strtoupper(Str::of($v)->ascii());
    }

    private function parseBool($val): ?bool
    {
        if (is_null($val)) return null;
        $s = mb_strtolower(trim((string)$val));
        if ($s === '') return null;
        return in_array($s, ['1','si','sí','true','t','y','yes','x']) ? true :
               (in_array($s, ['0','no','false','f','n']) ? false : null);
    }

    private function findAreaByName(string $normalizedName): ?Area
    {
        return Area::whereRaw('UPPER(name)=?', [$normalizedName])->first();
    }

    private function findCargoByName(string $normalizedName): ?Cargo
    {
        return Cargo::whereRaw('UPPER(name)=?', [$normalizedName])->first();
    }

    private function findCargoByNameActivo(string $normalizedName): ?Cargo
    {
        return Cargo::whereRaw('UPPER(name)=? AND estado=?', [$normalizedName, true])->first();
    }

    private function findNivelJerarquicoByName(string $normalizedName): ?NivelJerarquico
    {
        return NivelJerarquico::whereRaw('UPPER(name)=?', [$normalizedName])->first();
    }

    /**
     * Obtener subordinados de un evaluado.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getSubordinados($id)
    {
        $evaluado = CampaniaHasEvaluado::findOrFail($id);
        $subordinados = $evaluado->subordinados()
            ->with(['personal', 'area', 'puesto'])
            ->get();
            
        return response()->json($subordinados);
    }

    /**
     * Obtener evaluados con el mismo superior.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getPares($id)
    {
        $evaluado = CampaniaHasEvaluado::findOrFail($id);
        
        if (!$evaluado->superior_personal_id) {
            return response()->json([]);
        }
        
        $pares = CampaniaHasEvaluado::where('superior_personal_id', $evaluado->superior_personal_id)
            ->where('campania_id', $evaluado->campania_id)
            ->where('id', '!=', $evaluado->id)
            ->with(['personal', 'area', 'puesto'])
            ->get();
            
        return response()->json($pares);
    }

    /**
     * Descarga de plantilla Excel con cabeceras e instrucciones
     */
    public function templateEvaluados($campaniaId)
    {
        // No se persiste nada, solo se entrega la plantilla
        return Excel::download(new EvaluadosTemplateExport, "plantilla_evaluados_campania_{$campaniaId}.xlsx");
    }

    /**
     * Validar archivo de importación de evaluados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function validarImportar(Request $request)
    {
        $request->validate([
            'campania_id' => 'required|exists:campanias,id',
            'file' => 'required|file|mimes:csv,xlsx,xls'
        ]);

        $campaniaId = (int)$request->campania_id;

        $import = new EvaluadosRowsImport();
        Excel::import($import, $request->file('file'));
        $rows = $import->rows ?? collect();

        $errors = [];
        $preview = [];
        $rowNumber = 2; // considerando fila 1 como cabeceras

        foreach ($rows as $row) {
            $dni = trim((string)($row['dni'] ?? ''));
            $correo = trim((string)($row['correo'] ?? ''));
            $errRow = [];

            if ($dni === '') {
                $errRow[] = 'DNI es obligatorio.';
            } else {
                $personal = Personal::where('dni', $dni)->first();
                if (!$personal) {
                    $errRow[] = "No existe personal con DNI {$dni}.";
                }
            }

            // Si se proporcionan nombres de área/puesto/nivel, solo validamos existencia
            $areaName = $this->norm($row['area'] ?? '');
            if ($areaName !== '' && !$this->findAreaByName($areaName)) {
                $errRow[] = "Área '{$areaName}' no encontrada.";
            }

            $cargoName = $this->norm($row['puesto'] ?? '');
            if ($cargoName !== '' && !$this->findCargoByNameActivo($cargoName)) {
                $errRow[] = "Puesto/Cargo '{$cargoName}' no encontrado.";
            }

            $nivelName = $this->norm($row['nivel_jerarquico'] ?? '');
            if ($nivelName !== '' && !$this->findNivelJerarquicoByName($nivelName)) {
                $errRow[] = "Nivel jerárquico '{$nivelName}' no encontrado.";
            }

            $dniSup = trim((string)($row['dni_superior'] ?? ''));
            if ($dniSup !== '' && !Personal::where('dni', $dniSup)->exists()) {
                $errRow[] = "Superior con DNI {$dniSup} no encontrado.";
            }

            if ($correo !== '' && !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                $errRow[] = "Correo '{$correo}' inválido.";
            }

            if (!empty($errRow)) {
                $errors[] = "Fila {$rowNumber}: " . implode(' ', $errRow);
            }

            // if (count($preview) < 20) {
                $preview[] = [
                    'DNI' => $dni,
                    'AREA' => (string)($row['area'] ?? ''),
                    'PUESTO' => (string)($row['puesto'] ?? ''),
                    'NIVEL JERARQUICO' => (string)($row['nivel_jerarquico'] ?? ''),
                    'DNI SUPERIOR' => (string)($row['dni_superior'] ?? ''),
                    'HAB. COMPETENCIAS' => (string)($row['habilitado_para_evaluacion_de_competencias'] ?? ''),
                    'HAB. OBJETIVOS' => (string)($row['habilitado_para_evaluacion_por_objetivos'] ?? ''),
                    'CORREO' => $correo,
                ];
            // }

            $rowNumber++;
        }

        return response()->json([
            'valid' => count($errors) === 0,
            'total' => $rows->count(),
            'valid_count' => $rows->count() - count($errors),
            'error_count' => count($errors),
            'errors' => $errors,
            'preview' => $preview,
        ]);
    }

    public function generarEvaluadorHasEvaluado($campaniaId, $tipo)
    {
        // obtener tipo_relacion_jerarquicas
        $tipoRelacionJerarquicas = TipoRelacionJerarquica::all()->keyBy('name');
        // dd($tipoRelacionJerarquicas);

        // 1. Obtener todos los evaluados de la campaña
        $evaluados = CampaniaHasEvaluado::with([
            'personal',
            'superior',
            'subordinados',
            'paresMismoSuperior.personal',
            'tipoPuestoHasNivelJerarquico.tipoDePuesto',
            'tipoPuestoHasNivelJerarquico.dominio.grado'
        ])->where('campania_id', $campaniaId)->get();

        // 2. Obtener la evaluación de competencias de la campaña
        $evaluacion = Evaluacione::where('campania_id', $campaniaId)
            ->whereHas('tipoDeEvaluacion', function($q) {
                $q->where('name', 'EVALUACION DE DESEMPEÑO POR COMPETENCIAS');
            })->first();

        if (!$evaluacion) {
            return response()->json(['message' => 'No se encontró la evaluación de competencias para la campaña.'], 404);
        }
        
        // 2.1 Obtener la evaluación de objetivos de la campaña
        $evaluacionObjetivos = Evaluacione::where('campania_id', $campaniaId)
            ->whereHas('tipoDeEvaluacion', function($q) {
                $q->where('name', 'EVALUACION DE DESEMPEÑO POR OBJETIVOS');
            })->first();

        if (!$evaluacionObjetivos) {
            return response()->json(['message' => 'No se encontró la evaluación de objetivos para la campaña.'], 404);
        }

        // 3. Procesar cada evaluado
        foreach ($evaluados as $evaluado) {
            if ($tipo == 'competencias') {
                // Si el evaluado no está habilitado para evaluación de competencias, continuar
                if (!$evaluado->habilitado_para_evaluacion_de_competencias) continue;
                
                // if ($evaluado->habilitado_para_evaluacion_de_competencias) {
                    // $gradoId = $evaluado->tipoPuestoHasNivelJerarquico->dominio->grado->id ?? null;
                    // if (!$gradoId) continue;

                    $gradoOriginalId = $evaluado->tipoPuestoHasNivelJerarquico->dominio->grado->id ?? null;
                    if (!$gradoOriginalId) continue;

                    
                    // Presencia de relaciones
                    $hasJefe = !empty($evaluado->superior_personal_id);

                    // "Pares válidos": mismo superior y mismo tipo de puesto
                    $paresValidos = $evaluado->paresMismoSuperior
                        ->filter(function($par) use ($evaluado) {
                            return $par->personal_id != $evaluado->personal_id &&
                                $par->tipoPuestoHasNivelJerarquico &&
                                $evaluado->tipoPuestoHasNivelJerarquico &&
                                $par->tipoPuestoHasNivelJerarquico->tipoDePuesto &&
                                $evaluado->tipoPuestoHasNivelJerarquico->tipoDePuesto &&
                                $par->tipoPuestoHasNivelJerarquico->tipoDePuesto->id == $evaluado->tipoPuestoHasNivelJerarquico->tipoDePuesto->id;
                        });

                    $numPares = $paresValidos->count();
                    $numSub = $evaluado->subordinados->count() ?? 0;

                    // 3.1 Calcular pesos efectivos y el grado a registrar según reglas
                    $calc = $this->calcularPesosEfectivosYGrado($campaniaId, (int)$gradoOriginalId, $hasJefe, $numPares, $numSub);
                    $gradoAUsar = $calc['grado_id'];
                    $pesos = $calc['pesos']; // ['JEFE'=>x, 'PAR'=>y, 'SUBORDINADO'=>z]

                    // dd($evaluado->personal_id, $gradoOriginalId, $gradoAUsar, $hasJefe, $numPares, $numSub, $pesos);

                    // 3.2 Limpiar relaciones previas de competencias para este evaluado (evita duplicados)
                    EvaluadorHasEvaluado::where('evaluado_id', $evaluado->personal_id)
                        ->where('campania_id', $campaniaId)
                        ->where('evaluacion_id', $evaluacion->id)
                        ->delete();

                    // 3.3 Crear relaciones respetando prorrateo
                    if (($pesos['JEFE'] ?? 0) > 0 && $hasJefe) {
                        // obtener id de nombre
                        $idRelacionJerarquica = $tipoRelacionJerarquicas['JEFE']->id ?? null;
                        // dd("Evaluado {$evaluado->personal_id} con grado {$gradoAUsar} tiene Jefe con id {$idRelacionJerarquica}");
                        $this->handleSuperior($evaluado, (float)$pesos['JEFE'], $evaluacion, $campaniaId, $gradoAUsar, $idRelacionJerarquica);
                    }

                    if (($pesos['PAR'] ?? 0) > 0 && $numPares > 0) {
                        // obtener id de nombre
                        $idRelacionJerarquica = $tipoRelacionJerarquicas['PAR']->id ?? null;
                        // dd("Evaluado {$evaluado->personal_id} con grado {$gradoAUsar} tiene Par con id {$idRelacionJerarquica}");
                        $this->handlePares($evaluado, (float)$pesos['PAR'], $evaluacion, $campaniaId, $gradoAUsar, $idRelacionJerarquica);
                    }

                    if (($pesos['SUBORDINADO'] ?? 0) > 0 && $numSub > 0) {
                        // obtener id de nombre
                        $idRelacionJerarquica = $tipoRelacionJerarquicas['SUBORDINADO']->id ?? null;
                        // dd("Evaluado {$evaluado->personal_id} con grado {$gradoAUsar} tiene Subordinado con id {$idRelacionJerarquica}");
                        $this->handleSubordinados($evaluado, (float)$pesos['SUBORDINADO'], $evaluacion, $campaniaId, $gradoAUsar, $idRelacionJerarquica);
                    }

                    if (($pesos['UNO MISMO'] ?? 0) >= 0) {
                        // Si se usa "UNO MISMO", manejarlo aquí si es necesario
                        // Por ahora, no se maneja, pero se deja como referencia
                        $idRelacionJerarquica = $tipoRelacionJerarquicas['UNO MISMO']->id ?? null;
                        // dd("Evaluado {$evaluado->personal_id} con grado {$gradoAUsar} tiene Uno Mismo con id {$idRelacionJerarquica}");
                        $this->handleUnoMismo($evaluado, (float)$pesos['UNO MISMO'], $evaluacion, $campaniaId, $gradoAUsar, $idRelacionJerarquica);
                    } else {
                        // Manejar el caso donde no se usa "UNO MISMO"
                    }

            } elseif ($tipo == 'objetivos') {
                // Si el evaluado no está habilitado para evaluación por objetivos, continuar
                if (!$evaluado->habilitado_para_evaluacion_por_objetivos) continue;                

                if ($evaluado->habilitado_para_evaluacion_por_objetivos) {
                    // Aquí podrías manejar la lógica para evaluación por objetivos si es necesario
                    // Por ahora, solo se maneja la evaluación de competencias
                    // su evaluador_id será su superior, si no cuenta con superior no se insertarán los datos

                    // si el nombre del nivel jerarquico es Nivel IV, entonces el valor del campo jerarquia será 5 y el tipo_jerarquia_id será 2
                    // en todos los otros casos el valor del campo jerarquia será 2 y el tipo_jerarquia_id será 1

                    if (!$evaluacionObjetivos) continue;
                    // Verificar si el evaluado tiene un superior

                    if (!$evaluado->superior_personal_id) {
                    } else {
                        if ($evaluado->tipoPuestoHasNivelJerarquico->nivelJerarquico->name == 'Nivel IV') {
                            EvaluadorHasEvaluado::updateOrCreate([
                                'evaluador_id' => $evaluado->superior_personal_id, // Si no tiene superior, se evalúa a sí mismo
                                'evaluado_id' => $evaluado->personal_id,
                                'evaluacion_id' => $evaluacionObjetivos->id,
                                'campania_id' => $campaniaId,
                            ], [
                                'jerarquia' => 5,
                                'tipo_jerarquia_id' => 2,
                            ]);
                        } else {
                            EvaluadorHasEvaluado::updateOrCreate([
                                'evaluador_id' => $evaluado->superior_personal_id, // Si no tiene superior, se evalúa a sí mismo
                                'evaluado_id' => $evaluado->personal_id,
                                'evaluacion_id' => $evaluacionObjetivos->id,
                                'campania_id' => $campaniaId,
                            ], [
                                'jerarquia' => 2,
                                'tipo_jerarquia_id' => 1,
                            ]);
                        }
                    }
                }
            }
        }

        return response()->json(['message' => 'Registros generados correctamente']);
    }

    private function calcularPesosEfectivosYGrado(int $campaniaId, int $gradoId, bool $hasJefe, int $numPares, int $numSub): array
    {
        // Pesos base del grado original
        $base = $this->obtenerPesosBase($campaniaId, $gradoId);

        $gradoID180 = Grado::where('name', '180')->where('estado',1)->first()->id;
        $gradoID90 = Grado::where('name', '90')->where('estado',1)->first()->id;
        $gradoID270 = Grado::where('name', '270')->where('estado',1)->first()->id;

        // Regla: 180° sin pares => se convierte en 90°
        if ($gradoId === $gradoID180 && $numPares === 0) {
            $p90 = $this->obtenerPesosBase($campaniaId, $gradoID90);
            return ['grado_id' => $gradoID90, 'pesos' => $p90];
        }

        // Reglas para 270°
        if ($gradoId === $gradoID270) {
            // Sin subordinados => se convierte en 180°
            if ($numSub === 0) {
                $p180 = $this->obtenerPesosBase($campaniaId, $gradoID180);
                // Si además no hay pares, 180° se convierte en 90°
                if ($numPares === 0) {
                    $p90 = $this->obtenerPesosBase($campaniaId, $gradoID90);
                    return ['grado_id' => $gradoID90, 'pesos' => $p90];
                }
                return ['grado_id' => $gradoID180, 'pesos' => $p180];
            }

            // Sin pares y con jefe => especial 80%/20%, se mantiene 270°
            if ($numPares === 0 && $hasJefe) {
                return ['grado_id' => $gradoID270, 'pesos' => ['JEFE' => 0.8, 'PAR' => 0.0, 'SUBORDINADO' => 0.2, 'UNO MISMO' => 0.0]];
            }

            // Caso “Manuel Yzaga”: sin pares ni jefe => 100% subordinados, se mantiene 270°
            if ($numPares === 0 && !$hasJefe && $numSub > 0) {
                return ['grado_id' => $gradoID270, 'pesos' => ['JEFE' => 0.0, 'PAR' => 0.0, 'SUBORDINADO' => 1.0, 'UNO MISMO' => 0.0]];
            }
        }
        
        // Caso general: usar pesos base y mantener el grado original
        // $base = $base + ['UNO MISMO' => 0.0]; // asegura clave
        // Caso general: usar pesos base y mantener el grado original
        return ['grado_id' => $gradoId, 'pesos' => $base];
    }
    
    /**
     * Obtiene pesos base del grado configurado en la tabla pesos.
     * Retorna claves garantizadas JEFE, PAR, SUBORDINADO con 0.0 si faltan.
     */
    private function obtenerPesosBase(int $campaniaId, int $gradoId): array
    {
        $map = Peso::where('campania_id', $campaniaId)
            ->where('grado_id', $gradoId)
            ->with('tipoRelacionJerarquica')
            ->get()
            ->mapWithKeys(function($p){
                return [
                    // 'idRelacionJerarquica' => $p->tipoRelacionJerarquica->id,
                    strtoupper($p->tipoRelacionJerarquica->name) => (float)$p->peso
                ];
            });

        return [
            'JEFE' => (float)($map['JEFE'] ?? 0.0),
            'PAR' => (float)($map['PAR'] ?? 0.0),
            'SUBORDINADO' => (float)($map['SUBORDINADO'] ?? 0.0),
            'UNO MISMO' => (float)($map['UNO MISMO'] ?? 0.0), // Si se usa en el futuro
        ];
    }

    private function handleSuperior($evaluado, $peso, $evaluacion, $campaniaId, $gradoId, $idRelacionJerarquica)
    {
        $pesoValue = is_object($peso) ? (float)$peso->peso : (float)$peso;

        // Si el evaluado tiene un superior, crear o actualizar el registro
        if ($evaluado->superior_personal_id) {
            EvaluadorHasEvaluado::updateOrCreate([
                'evaluador_id' => $evaluado->superior_personal_id,
                'evaluado_id' => $evaluado->personal_id,
                'evaluacion_id' => $evaluacion->id,
                'campania_id' => $campaniaId,
                'grado_id' => $gradoId,
            ], [
                'peso' => $pesoValue,
                'peso_prorrateado' => $pesoValue,
                'relacion_jerarquica_id' => $idRelacionJerarquica,
            ]);
        }
    }

    private function handlePares($evaluado, $peso, $evaluacion, $campaniaId, $gradoId, $idRelacionJerarquica)
    {
        $pesoValue = is_object($peso) ? (float)$peso->peso : (float)$peso;

        // Obtener pares con el mismo superior y mismo tipo de puesto
        $pares = $evaluado->paresMismoSuperior
            ->filter(function($par) use ($evaluado) {
                return $par->personal_id != $evaluado->personal_id &&
                    $par->tipoPuestoHasNivelJerarquico &&
                    $evaluado->tipoPuestoHasNivelJerarquico &&
                    $par->tipoPuestoHasNivelJerarquico->tipoDePuesto &&
                    $evaluado->tipoPuestoHasNivelJerarquico->tipoDePuesto &&
                    $par->tipoPuestoHasNivelJerarquico->tipoDePuesto->id == $evaluado->tipoPuestoHasNivelJerarquico->tipoDePuesto->id;
            });

        $numPares = $pares->count();
        if ($numPares > 0) {
            $pesoPorPar = $pesoValue / $numPares;
            foreach ($pares as $par) {
                EvaluadorHasEvaluado::updateOrCreate([
                    'evaluador_id' => $par->personal_id,
                    'evaluado_id' => $evaluado->personal_id,
                    'evaluacion_id' => $evaluacion->id,
                    'campania_id' => $campaniaId,
                    'grado_id' => $gradoId,
                ], [
                    'peso' => $pesoValue,
                    'peso_prorrateado' => $pesoPorPar,
                    'relacion_jerarquica_id' => $idRelacionJerarquica,
                ]);
            }
        }
    }

    private function handleSubordinados($evaluado, $peso, $evaluacion, $campaniaId, $gradoId, $idRelacionJerarquica)
    {
        $pesoValue = is_object($peso) ? (float)$peso->peso : (float)$peso;

        // Obtener subordinados del evaluado
        $subordinados = $evaluado->subordinados ?? collect();
        $numSub = $subordinados->count();
        if ($numSub > 0) {
            $pesoPorSub = $pesoValue / $numSub;
            foreach ($subordinados as $sub) {
                EvaluadorHasEvaluado::updateOrCreate([
                    'evaluador_id' => $sub->personal_id,
                    'evaluado_id' => $evaluado->personal_id,
                    'evaluacion_id' => $evaluacion->id,
                    'campania_id' => $campaniaId,
                    'grado_id' => $gradoId,
                ], [
                    'peso' => $pesoValue,
                    'peso_prorrateado' => $pesoPorSub,
                    'relacion_jerarquica_id' => $idRelacionJerarquica,
                ]);
            }
        }
    }
    
    // private function handleSuperior($evaluado, $peso, $evaluacion, $campaniaId, $gradoId)
    // {
    //     // Si el evaluado tiene un superior, crear o actualizar el registro
    //     if ($evaluado->superior_personal_id) {
    //         EvaluadorHasEvaluado::updateOrCreate([
    //             'evaluador_id' => $evaluado->superior_personal_id,
    //             'evaluado_id' => $evaluado->personal_id,
    //             'evaluacion_id' => $evaluacion->id,
    //             'campania_id' => $campaniaId,
    //             'grado_id' => $gradoId,
    //         ], [
    //             'peso' => $peso->peso,
    //             'peso_prorrateado' => $peso->peso,
    //         ]);
    //     }
    // }
    // private function handlePares($evaluado, $peso, $evaluacion, $campaniaId, $gradoId)
    // {
    //     // Obtener pares con el mismo superior y nivel jerárquico
    //     $pares = $evaluado->paresMismoSuperior
    //         ->filter(function($par) use ($evaluado) {
    //             return 
    //                 $par->personal_id != $evaluado->personal_id &&
    //                 $par->tipoPuestoHasNivelJerarquico &&
    //                 $par->tipoPuestoHasNivelJerarquico->tipoDePuesto->id == $evaluado->tipoPuestoHasNivelJerarquico->tipoDePuesto->id;
    //         });

    //     $numPares = $pares->count();
    //     if ($numPares > 0) {
    //         $pesoPorPar = $peso->peso / $numPares;
    //         foreach ($pares as $par) {
    //             EvaluadorHasEvaluado::updateOrCreate([
    //                 'evaluador_id' => $par->personal_id,
    //                 'evaluado_id' => $evaluado->personal_id,
    //                 'evaluacion_id' => $evaluacion->id,
    //                 'campania_id' => $campaniaId,
    //                 'grado_id' => $gradoId,
    //             ], [
    //                 'peso' => $peso->peso,
    //                 'peso_prorrateado' => $pesoPorPar,
    //             ]);
    //         }
    //     }
    // }

    // private function handleSubordinados($evaluado, $peso, $evaluacion, $campaniaId, $gradoId)
    // {
    //     // Obtener subordinados del evaluado
    //     $subordinados = $evaluado->subordinados;
    //     $numSub = $subordinados->count();
    //     if ($numSub > 0) {
    //         $pesoPorSub = $peso->peso / $numSub;
    //         foreach ($subordinados as $sub) {
    //             EvaluadorHasEvaluado::updateOrCreate([
    //                 'evaluador_id' => $sub->personal_id,
    //                 'evaluado_id' => $evaluado->personal_id,
    //                 'evaluacion_id' => $evaluacion->id,
    //                 'campania_id' => $campaniaId,
    //                 'grado_id' => $gradoId,
    //             ], [
    //                 'peso' => $peso->peso,
    //                 'peso_prorrateado' => $pesoPorSub,
    //             ]);
    //         }
    //     }
    // }

    private function handleUnoMismo($evaluado, $peso, $evaluacion, $campaniaId, $gradoId, $idRelacionJerarquica)
    {
        $pesoValue = is_object($peso) ? (float)$peso->peso : (float)$peso;

        if ($pesoValue < 0) {
            return;
        }

        // Registrar el evaluado consigo mismo
        EvaluadorHasEvaluado::updateOrCreate([
            'evaluador_id' => $evaluado->personal_id,
            'evaluado_id' => $evaluado->personal_id,
            'evaluacion_id' => $evaluacion->id,
            'campania_id' => $campaniaId,
            'grado_id' => $gradoId,
        ], [
            'peso' => $pesoValue,
            'peso_prorrateado' => $pesoValue,
            'relacion_jerarquica_id' => $idRelacionJerarquica,
        ]);
    }

    public function exportarPersonalACampaniaActual(Request $request)
    {
        $campania = Campania::where('es_campania_actual', true)->first();
        if (!$campania) {
            return response()->json(['message' => 'No hay campaña actual configurada.'], 400);
        }

        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['message' => 'No se recibieron IDs de personal para exportar.'], 400);
        }
        
        $creados = 0;
        $actualizados = 0;
        $errores = 0;
        
        foreach ($ids as $personalId) {
            try {
                $personal = Personal::with(['cargo', 'cargo.tipoDePuesto'])->findOrFail($personalId);

                // Verificar que tenga los datos necesarios
                // if (!$personal->area_id || !$personal->cargo_id || !$personal->cargo->tipo_de_puesto_id || !$personal->reporta_a) {
                //     $errores++;
                //     continue;
                // }

                $tipoPuestoCampania = TipoDePuestoHasNivelJerarquico::where('campania_id', $campania->id)
                    ->where('tipo_de_puesto_id', $personal->cargo->tipo_de_puesto_id)
                    ->first();
                    
                if (!$tipoPuestoCampania) {
                    $errores++;
                    continue;
                }

                // Verificar si cumple con las fechas de corte para las evaluaciones
                $habilitadoParaEvaluacionDeCompetencias = false;
                $habilitadoParaEvaluacionPorObjetivos = false;

                $evaluacionCompetencias = Evaluacione::where('campania_id', $campania->id)
                    ->whereHas('tipoDeEvaluacion', function($q) {
                        $q->where('name', 'EVALUACION DE DESEMPEÑO POR COMPETENCIAS');
                    })->first();
                if ($evaluacionCompetencias) {
                    $habilitadoParaEvaluacionDeCompetencias = $personal->fecha_ingreso < $evaluacionCompetencias->fecha_corte; 
                }
                
                $evaluacionObjetivos = Evaluacione::where('campania_id', $campania->id)
                    ->whereHas('tipoDeEvaluacion', function($q) {
                        $q->where('name', 'EVALUACION DE DESEMPEÑO POR OBJETIVOS');
                    })->first();
                if ($evaluacionObjetivos) {
                    $habilitadoParaEvaluacionPorObjetivos = $personal->fecha_ingreso < $evaluacionObjetivos->fecha_corte; 
                }

                // Si no cumple con ninguna fecha de corte, no agregarlo
                if (!$habilitadoParaEvaluacionDeCompetencias && !$habilitadoParaEvaluacionPorObjetivos) {
                    $errores++;
                    continue;
                }

                // Preparar datos para guardar
                $data = [
                    'personal_id' => $personalId,
                    'campania_id' => $campania->id,
                    'area_id' => $personal->area_id,
                    'puesto_id' => $personal->cargo_id,
                    'tipo_de_puesto_campania_id' => $tipoPuestoCampania->id,
                    'superior_personal_id' => $personal->reporta_a,
                    'habilitado_para_evaluacion_de_competencias' => $habilitadoParaEvaluacionDeCompetencias,
                    'habilitado_para_evaluacion_por_objetivos' => $habilitadoParaEvaluacionPorObjetivos,
                    'cesado' => $personal->cesado,
                    'estado' => true,
                ];

                // Verificar si ya existe para actualizar o crear nuevo
                $campaniaHasEvaluado = CampaniaHasEvaluado::where('personal_id', $personalId)
                    ->where('campania_id', $campania->id)
                    ->first();

                if ($campaniaHasEvaluado) {
                    $campaniaHasEvaluado->update($data);
                    $actualizados++;
                } else {
                    CampaniaHasEvaluado::create($data);
                    $creados++;
                }
            } catch (\Exception $e) {
                \Log::error('Error al exportar personal: ' . $e->getMessage());
                $errores++;
            }
        }

        return response()->json([
            'message' => "Exportación completada. Creados: $creados, Actualizados: $actualizados, No procesados: $errores",
            'success' => true
        ]);
    }

    public function exportarTodosSeleccionados()
    {
        $campania = Campania::where('es_campania_actual', true)->first();
        if (!$campania) {
            return response()->json(['message' => 'No hay campaña actual configurada.'], 400);
        }

        // Obtener todos los IDs de personal con seleccionado=true
        $idsSeleccionados = Personal::where('seleccionado', true)
                                ->where('estado', true)
                                ->where('cesado', 0)
                                ->pluck('id')
                                ->toArray();
        
        if (empty($idsSeleccionados)) {
            return response()->json(['message' => 'No hay personal marcado como seleccionado que cumpla con los requisitos.'], 404);
        }
        
        $creados = 0;
        $actualizados = 0;
        $notrabajados = 0; // Contador para personal que no cumple con las fechas de corte
        foreach ($idsSeleccionados as $personalId) {
            $personal = Personal::findOrFail($personalId);

            // Misma lógica que en exportarPersonalACampaniaActual
            $tipoPuestoCampania = TipoDePuestoHasNivelJerarquico::where('campania_id', $campania->id)
                ->where('tipo_de_puesto_id', $personal->cargo->tipo_de_puesto_id)
                ->first();
                
            // if (!$personal->area_id || !$personal->cargo || !$tipoPuestoCampania || !$personal->reporta_a) {
            //     continue;
            // }

            $habilitadoParaEvaluacionDeCompetencias = false;
            $habilitadoParaEvaluacionPorObjetivos = false;

            $evaluacionCompetencias = Evaluacione::where('campania_id', $campania->id)
                ->whereHas('tipoDeEvaluacion', function($q) {
                    $q->where('name', 'EVALUACION DE DESEMPEÑO POR COMPETENCIAS');
                })->first();
            if ($evaluacionCompetencias) {
                $habilitadoParaEvaluacionDeCompetencias = ($personal->fecha_ingreso < $evaluacionCompetencias->fecha_corte); 
            }
            
            $evaluacionObjetivos = Evaluacione::where('campania_id', $campania->id)
                ->whereHas('tipoDeEvaluacion', function($q) {
                    $q->where('name', 'EVALUACION DE DESEMPEÑO POR OBJETIVOS');
                })->first();
            if ($evaluacionObjetivos) {
                $habilitadoParaEvaluacionPorObjetivos = ($personal->fecha_ingreso < $evaluacionObjetivos->fecha_corte); 
            }

            if (!$habilitadoParaEvaluacionDeCompetencias && !$habilitadoParaEvaluacionPorObjetivos) {
                // dd($personal,$tipoPuestoCampania,$evaluacionCompetencias,$evaluacionObjetivos,$personal->fecha_ingreso,
                // ($personal->fecha_ingreso >= $evaluacionCompetencias->fecha_corte)
                // ) ;
                $notrabajados++;
                continue;
            }

            $data = [
                'personal_id' => $personalId,
                'campania_id' => $campania->id,
                'area_id' => $personal->area_id,
                'puesto_id' => $personal->cargo_id,
                'tipo_de_puesto_campania_id' => $tipoPuestoCampania->id ?? null,
                'superior_personal_id' => $personal->reporta_a,
                'habilitado_para_evaluacion_de_competencias' => $habilitadoParaEvaluacionDeCompetencias,
                'habilitado_para_evaluacion_por_objetivos' => $habilitadoParaEvaluacionPorObjetivos,
                'cesado' => $personal->cesado,
                'estado' => true,
            ];

            $campaniaHasEvaluado = CampaniaHasEvaluado::where('personal_id', $personalId)
                ->where('campania_id', $campania->id)
                ->first();

            if ($campaniaHasEvaluado) {
                $this->saveOrUpdateEvaluado($data, $campaniaHasEvaluado->id);
                $actualizados++;
            } else {
                $this->saveOrUpdateEvaluado($data);
                $creados++;
            }
        }

        return response()->json([
            'message' => "Exportación completada. $creados creados, $actualizados actualizados, $notrabajados sin ser expxortados."
        ]);
    }

    private function saveOrUpdateEvaluado($data, $id = null)
    {
        // Validar datos requeridos
        $rules = [
            'personal_id' => [
                'required',
                'exists:personal,id',
                Rule::unique('campania_has_evaluados', 'personal_id')
                    ->where('campania_id', $data['campania_id'])
                    ->whereNull('deleted_at')
            ],
            'campania_id' => 'required|exists:campanias,id',
            'area_id' => 'nullable|exists:areas,id',
            'puesto_id' => 'nullable|exists:cargos,id',
            'tipo_de_puesto_campania_id' => 'nullable|exists:tipo_de_puesto_has_nivel_jerarquicos,id',
            'superior_personal_id' => [
                'nullable',
                'exists:personal,id',
                'different:personal_id'
            ],
            'habilitado_para_evaluacion_de_competencias' => 'boolean',
            'habilitado_para_evaluacion_por_objetivos' => 'boolean',
            'cesado' => 'boolean',
            'estado' => 'boolean'
        ];

        // Si es update, ignora el propio registro en la validación única
        if ($id) {
            $rules['personal_id'] = [
                'required',
                'exists:personal,id',
                Rule::unique('campania_has_evaluados', 'personal_id')
                    ->where('campania_id', $data['campania_id'])
                    ->whereNull('deleted_at')
                    ->ignore($id)
            ];
        }

        validator($data, $rules)->validate();

        if ($id) {
            $evaluado = CampaniaHasEvaluado::findOrFail($id);
            $evaluado->update($data);
        } else {
            $evaluado = CampaniaHasEvaluado::create($data);
        }

        return $evaluado;
    }
    
    public function resetRespuestas($id)
    {
        $evaluado = CampaniaHasEvaluado::findOrFail($id);

        $respuestas = Respuesta::with('evaluado')
            ->where('campania_id', $evaluado->campania_id)
            ->where('peso', '>', 0)
            ->get();

        $filtradas = $respuestas->where('evaluado_id', (string)$evaluado->personal_id);

        //eliminar filtradas
        foreach ($filtradas as $respuesta) {
            $respuesta->delete();
        }
        
        //eliminar resumen ResumenRespuestasEvaluacionDesempenoCompetencia
        ResumenRespuestasEvaluacionDesempenoCompetencia::where('campania_id', $evaluado->campania_id)
            ->where('personal_id', $evaluado->personal_id)
            ->delete();

        // Marcar evaluaciones como no realizadas (ajusta según tu modelo)
        EvaluadorHasEvaluado::where('campania_id', $evaluado->campania_id)
            ->where('evaluado_id', $evaluado->personal_id)
            ->where('realizado', 1)
            ->where('peso', '>', 0)
            ->where('evaluador_id', '!=', $evaluado->personal_id) // Excluir autoevaluación
            ->update(['realizado' => 0]);

        $evaluado->update([
            'puntaje_de_evaluacion_de_competencias' => null,
            'evaluacion_de_competencias_completada' => false,
        ]);

        return response()->json(['message' => 'Respuestas reiniciadas correctamente.']);
    }

}
