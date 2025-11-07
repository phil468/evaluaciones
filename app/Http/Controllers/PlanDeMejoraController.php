<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PlanesDeAccion;
use App\Models\Campania;
use App\Models\EncargadosPlanesDeAccion;
use App\Models\Personal;

class PlanDeMejoraController extends Controller
{
    /**
     * Vista de PMI del usuario logueado
     */
    public function index()
    {
        $user = auth()->user();
        
        if (!$user->personal_id) {
            return view('plan_de_mejora.index', ['planes' => [], 'esPropio' => true]);
        }

        $planes = $this->obtenerPlanesPorEmpleado($user->personal_id);
        
        return view('plan_de_mejora.index', [
            'planes' => $planes,
            'esPropio' => true,
            'nombreEmpleado' => $user->personal->name ?? 'Mi'
        ]);
    }

    /**
     * Vista de PMI de un subordinado
     */
    public function subordinado($empleado_id)
    {
        $user = auth()->user();
        
        if (!$user->personal_id) {
            return redirect()->route('resultados-de-equipo.index')
                ->with('error', 'No tiene permisos para ver estos planes.');
        }

        // Verificar que el empleado es subordinado del usuario logueado o es él mismo
        $esSubordinado = $this->verificarEsSubordinado($user->personal_id, $empleado_id);
        $esElMismo = $user->personal_id == $empleado_id;
        
        if (!$esSubordinado && !$esElMismo) {
            return redirect()->route('resultados-de-equipo.index')
                ->with('error', 'No tiene permisos para ver los planes de este empleado.');
        }

        $empleado = Personal::find($empleado_id);
        
        if (!$empleado) {
            return redirect()->route('resultados-de-equipo.index')
                ->with('error', 'Empleado no encontrado.');
        }

        $planes = $this->obtenerPlanesPorEmpleado($empleado_id);
        
        return view('plan_de_mejora.index', [
            'planes' => $planes,
            'esPropio' => $esElMismo,
            'nombreEmpleado' => $empleado->name,
            'empleado_id' => $empleado_id
        ]);
    }

    /**
     * Detalle de un plan específico
     */
    public function detalle(Request $request, $empleado_id)
    {
        $campaniaId = $request->campania_id;
        $user = auth()->user();
        
        // Verificar permisos: debe ser el mismo usuario o su subordinado
        $esSubordinado = $this->verificarEsSubordinado($user->personal_id, $empleado_id);
        $esElMismo = $user->personal_id == $empleado_id;
        
        if (!$esSubordinado && !$esElMismo) {
            return redirect()->route('plan.mejora.index')
                ->with('error', 'No tiene permisos para ver estos planes.');
        }
        
        // Obtener el encargado de planes para esta campaña y empleado
        $encargadoPlan = EncargadosPlanesDeAccion::where('empleado_id', $empleado_id)
            ->whereHas('plan_de_mejora', function($q) use ($campaniaId) {
                $q->where('campania_id', $campaniaId);
            })
            ->with([
                'planesDeMejora.competencia.competencia',
                'planesDeMejora.feedbacks.user',
                'planesDeMejora.evidencias',
                'empleado',
                'encargado',
                'plan_de_mejora.campania'
            ])
            ->first();
        
        if (!$encargadoPlan) {
            return redirect()->back()
                ->with('error', 'No se encontraron planes para esta campaña.');
        }

        // dd($encargadoPlan, $empleado_id, $esElMismo);
        
        return view('plan_de_mejora.detalle', [
            'encargadoPlan' => $encargadoPlan,
            'empleado_id' => $empleado_id,
            'soloLectura' => true,
            'esPropio' => $esElMismo
        ]);
    }

    /**
     * Obtener planes de un empleado específico
     */
    private function obtenerPlanesPorEmpleado($empleado_id)
    {
        // Obtener todas las campañas donde el empleado tiene planes validados
        $campanias = Campania::whereHas('planesConfiguracion.encargadoPlanesDeAccion', function($q) use ($empleado_id) {
            $q->where('empleado_id', $empleado_id)
              ->whereHas('planesDeMejora', function($planQuery) {
                  $planQuery->where('estado_aprobacion', 'validado');
              });
        })
        ->with(['planesConfiguracion.encargadoPlanesDeAccion' => function($q) use ($empleado_id) {
            $q->where('empleado_id', $empleado_id)
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
                    $planesValidados = $encargado->planesDeMejora->where('estado_aprobacion', 'validado');
                    
                    // Verificar que se alcanzó la cantidad requerida y todos estén validados
                    if ($planesValidados->count() >= $encargado->cantidad_requerida &&
                        $planesValidados->count() > 0) {
                        
                        $promedioAvance = round($planesValidados->avg('avance'), 2);
                        
                        $planes[$campania->id] = [
                            'campania_id' => $campania->id,
                            'nombre' => $campania->name,
                            'progreso' => $promedioAvance,
                            'tieneResultados' => true,
                            'encargado_plan_id' => $encargado->id,
                            'empleado_id' => $empleado_id,
                            'total_planes' => $planesValidados->count()
                        ];
                    }
                }
            }
        }
        
        return $planes;
    }

    /**
     * Verificar si un empleado es subordinado del usuario logueado
     */
    private function verificarEsSubordinado($jefe_id, $empleado_id)
    {
        // Obtener el personal del empleado
        $empleado = Personal::find($empleado_id);
        
        if (!$empleado) {
            return false;
        }

        // Verificar si reporta directamente
        if ($empleado->reporta_a == $jefe_id) {
            return true;
        }

        // Verificar si reporta indirectamente (subordinados de subordinados)
        return $this->esSubordinadoRecursivo($jefe_id, $empleado->reporta_a);
    }

    /**
     * Verificación recursiva de subordinados
     */
    private function esSubordinadoRecursivo($jefe_id, $superior_id, $nivel = 0)
    {
        // Limitar niveles de recursión para evitar bucles infinitos
        if ($nivel > 10 || !$superior_id) {
            return false;
        }

        if ($superior_id == $jefe_id) {
            return true;
        }

        $superior = Personal::find($superior_id);
        
        if (!$superior) {
            return false;
        }

        return $this->esSubordinadoRecursivo($jefe_id, $superior->reporta_a, $nivel + 1);
    }
}
