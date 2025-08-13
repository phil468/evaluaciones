<?php
namespace App\Http\Controllers;

use App\Models\Cargo;
use App\Models\TipoDePuesto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CargoController extends Controller
{
    public function index()
    {
        return view('cargos.index');
    }

    public function getData()
    {
        if (Gate::denies('ver-cargo')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $cargos = Cargo::with('tipoDePuesto')
        ->withCount('personals') // <-- Esto agrega personals_count
        ->get();
        
        return response()->json($cargos);
    }

    public function show(Cargo $cargo)
    {
        if (Gate::denies('ver-cargo')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $cargo->load('tipoDePuesto');
        return response()->json($cargo);
    }
    
    public function create()
    {
        if (Gate::denies('crear-cargo')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $tiposDePuesto = TipoDePuesto::where('estado', true)->get();
        return view('cargos.create', compact('tiposDePuesto'));
    }

    public function edit(Cargo $cargo)
    {
        if (Gate::denies('editar-cargo')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $tiposDePuesto = TipoDePuesto::where('estado', true)->get();
        return view('cargos.edit', compact('cargo', 'tiposDePuesto'));
    }

    public function store(Request $request)
    {
        if (Gate::denies('crear-cargo')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'tipo_de_puesto_id' => 'nullable|exists:tipo_de_puestos,id',
            'estado' => 'nullable|boolean',
            'idcargo_nisira' => 'nullable|string|max:50',
            'fechacreacion_nisira' => 'nullable|date',
            'empresa_id' => 'nullable|integer',
        ], [], [
            'name' => 'Nombre',
            'tipo_de_puesto_id' => 'Tipo de puesto',
            'idcargo_nisira' => 'ID Cargo Nisira',
            'fechacreacion_nisira' => 'Fecha Creación Nisira',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $cargo = Cargo::create($request->all());
            return response()->json($cargo, 201);
        } catch (\Exception $e) {
            Log::error('Error al crear Cargo: ' . $e->getMessage());
            return response()->json(['message' => 'Error al crear Cargo'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        if (Gate::denies('editar-cargo')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'tipo_de_puesto_id' => 'nullable|exists:tipo_de_puestos,id',
            'estado' => 'nullable|boolean',
            'idcargo_nisira' => 'nullable|string|max:50',
            'fechacreacion_nisira' => 'nullable|date',
            'empresa_id' => 'nullable|integer',
        ], [], [
            'name' => 'Nombre',
            'tipo_de_puesto_id' => 'Tipo de puesto',
            'idcargo_nisira' => 'ID Cargo Nisira',
            'fechacreacion_nisira' => 'Fecha Creación Nisira',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $cargo = Cargo::findOrFail($id);

            // dd($cargo, $request->all());
            $cargo->update($request->all());
            return response()->json($cargo, 200);
        } catch (\Exception $e) {
            Log::error('Error al actualizar Cargo: ' . $e->getMessage());
            return response()->json(['message' => 'Error al actualizar Cargo'], 500);
        }
    }

    public function destroy($id)
    {
        if (Gate::denies('borrar-cargo')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        try {
            $cargo = Cargo::findOrFail($id);
            $cargo->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error('Error al eliminar Cargo: ' . $e->getMessage());
            return response()->json(['message' => 'Error al eliminar Cargo'], 500);
        }
    }

    public function actualizarTiposDePuesto()
    {
        if (Gate::denies('editar-cargo')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        try {
            // Obtener todos los cargos que no tienen tipo_de_puesto_id
            $cargos = Cargo::whereNull('tipo_de_puesto_id')->orWhere('tipo_de_puesto_id', 0)->get();
            $tiposDePuesto = TipoDePuesto::all();
            
            $actualizados = 0;
            $noActualizados = 0;
            $cargosSinCoincidencia = [];

            foreach ($cargos as $cargo) {
                $cargoName = strtolower($cargo->name);
                $mejorCoincidencia = null;
                $longitudMasAlta = 0;
                // $porcentajeMasAlto = 0;
                
                foreach ($tiposDePuesto as $tipo) {
                    // Separar por "/" y probar cada parte
                    $nombres = array_map('trim', explode('/', $tipo->name));
                    foreach ($nombres as $nombreTipo) {
                        // Buscar como palabra completa (usando expresiones regulares)
                        if (preg_match('/\b' . preg_quote(strtolower($nombreTipo), '/') . '\b/u', $cargoName)) {
                            if (strlen($nombreTipo) > $longitudMasAlta) {
                                $mejorCoincidencia = $tipo;
                                $longitudMasAlta = strlen($nombreTipo);
                            }
                        }
                    }
                }
                
                // Si encontramos una buena coincidencia, actualizamos el cargo
                if ($mejorCoincidencia) {
                    $cargo->tipo_de_puesto_id = $mejorCoincidencia->id;
                    $cargo->save();
                    $actualizados++;
                } else {
                    $noActualizados++;
                    $cargosSinCoincidencia[] = $cargo->name;
                }
            }

            $mensaje = "Proceso completado: $actualizados cargos actualizados, $noActualizados sin coincidencias suficientes.";
            if ($noActualizados > 0) {
                $mensaje .= " Cargos sin coincidencia: " . implode(', ', $cargosSinCoincidencia) . ".";
            }

            return response()->json([
                'status' => 'success',
                'message' => $mensaje
            ]);
        } catch (\Exception $e) {
            Log::error('Error al actualizar tipos de puesto: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Error al actualizar tipos de puesto: ' . $e->getMessage()
            ], 500);
        }
    }
}
