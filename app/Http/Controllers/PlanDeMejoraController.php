<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PlanesDeAccion;
use App\Models\Campania;
use App\Models\EncargadosPlanesDeAccion;

class PlanDeMejoraController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if (!$user->personal_id) {
            return view('plan_de_mejora.index', ['planes' => []]);
        }

        // Obtener todas las campañas donde el usuario tiene planes
        $campanias = Campania::whereHas('planesConfiguracion.encargadoPlanesDeAccion', function($q) use ($user) {
            $q->where('empleado_id', $user->personal_id)
              ->whereHas('planesDeMejora', function($planQuery) {
                  // Solo mostrar si TODOS los planes están validados
                  $planQuery->where('estado_aprobacion', 'validado');
              });
        })
        ->with(['planesConfiguracion.encargadoPlanesDeAccion' => function($q) use ($user) {
            $q->where('empleado_id', $user->personal_id)
              ->with(['planesDeMejora' => function($planQuery) {
                  $planQuery->where('estado_aprobacion', 'validado');
              }]);
        }])
        ->orderBy('id', 'desc')
        ->get();

        $planes = [];
        
        foreach ($campanias as $campania) {
            foreach ($campania->planesConfiguracion as $config) {
                foreach ($config->encargadoPlanesDeAccion as $encargado) {
                    $planesValidados = $encargado->planesDeMejora;
                    
                    // Verificar que se alcanzó la cantidad requerida y todos estén validados
                    if ($planesValidados->count() >= $encargado->cantidad_requerida &&
                        $planesValidados->count() == $planesValidados->where('estado_aprobacion', 'validado')->count()) {
                        
                        $promedioAvance = round($planesValidados->avg('avance'), 2);
                        
                        $planes[$campania->id] = [
                            'campania_id' => $campania->id,
                            'nombre' => $campania->name,
                            'progreso' => $promedioAvance,
                            'tieneResultados' => true,
                            'encargado_plan_id' => $encargado->id,
                            'empleado_id' => $user->personal_id,
                            'total_planes' => $planesValidados->count()
                        ];
                    }
                }
            }
        }
        
        return view('plan_de_mejora.index', compact('planes'));
    }

    public function detalle(Request $request, $empleado_id)
    {
        $campaniaId = $request->campania_id;
        
        // Verificar que el empleado_id corresponde al usuario logueado
        if (auth()->user()->personal_id != $empleado_id) {
            return redirect()->route('plan.mejora.index')
                ->with('error', 'No tiene permisos para ver estos planes.');
        }
        
        // Obtener el encargado de planes para esta campaña y empleado
        $encargadoPlan = EncargadosPlanesDeAccion::where('empleado_id', $empleado_id)
            ->whereHas('plan_de_mejora', function($q) use ($campaniaId) {
                $q->where('campania_id', $campaniaId);
            })
            ->with(['planesDeMejora.competencia', 'empleado', 'encargado', 'plan_de_mejora'])
            ->first();
        
        if (!$encargadoPlan) {
            return redirect()->route('plan.mejora.index')
                ->with('error', 'No se encontraron planes para esta campaña.');
        }
        
        return view('plan_de_mejora.detalle', [
            'encargadoPlan' => $encargadoPlan,
            'empleado_id' => $empleado_id,
            'soloLectura' => true
        ]);
    }
}
