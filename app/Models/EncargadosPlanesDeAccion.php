<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EncargadosPlanesDeAccion extends Model
{
	use HasFactory;
    use SoftDeletes;
	
    public $timestamps = true;

    protected $table = 'encargados_planes_de_accion';

    protected $fillable = [
        'encargado_id',
        'empleado_id',
        'evaluacion_id',
        'realizado',
        'tipo_de_evaluacion_id',
        'cargo_de_evaluador',
        'area_de_evaluador',
        'gerencia_sub_gerencia_de_evaluador',
        'cargo_de_evaluado',
        'area_de_evaluado',
        'gerencia_sub_gerencia_de_evaluado',
        'cantidad_requerida',
        'valor_esperado',
        'jerarquia',
        'planes_de_accion_configuracion_id',
        'habilitado', // Agregar este campo
    ];
    
    protected $appends = ['estado_pendiente'];

    public function empleado()
    {
        return $this->belongsTo(Personal::class, 'empleado_id','id');
    }

    // buscar el cargo del empleado para la campaña especifica pero en la tabla campaniahasevaluado, ojo que un empleado_id puede aparecer en el personal_id pero de otras campanias
    public function campania_has_evaluado()
    {
        return $this->hasOne(CampaniaHasEvaluado::class, 'personal_id','empleado_id')
                ->when($this->relationLoaded('plan_de_mejora') && $this->plan_de_mejora, 
                      fn($query) => $query->where('campania_id', $this->plan_de_mejora->campania_id));
    }  

    public function encargado()
    {
        return $this->belongsTo(Personal::class, 'encargado_id','id');
    }

    public function evaluacion()
    {
        return $this->belongsTo(Evaluacione::class, 'evaluacion_id','id');
    }

    public function planes_de_accion_encargado()
    {
        return $this->hasMany(PlanesDeAccion::class, 'encargado_id','id');
    }

    public function planes_de_accion_empleado()
    {
        return $this->hasMany(PlanesDeAccion::class, 'encargados_planes_de_accion_id','empleado_id');
    }
    
    public function planes_de_accion_empleado_realizados()
    {
        return $this->hasMany(PlanesDeAccion::class, 'encargados_planes_de_accion_id','empleado_id')->where('estado_id','<>',1);
    }

    public function plan_de_mejora()
    {
        return $this->belongsTo(PlanesConfiguracion::class, 'planes_de_accion_configuracion_id','id');        
    }

    //cuando evaluacion->tipo_evaluacion_id sea 2 comparar objetivos con la cantidad de objetivos, si es mejor el estado de la evaluacion es pendiente
    public function getEstadoPendienteAttribute()
    {
        if(!$this->habilitado)
        {
            return false;
        }
        // Verificar si hay un plan de mejora asociado
        if ($this->plan_de_mejora) {
            // Verificar si el plan de mejora está activo
            if ($this->plan_de_mejora->activa) {
                // Verificar si la primera fase del plan de mejora está activa
                if ($this->plan_de_mejora->primera_fase_activa) {
                    // Comparar la cantidad requerida con el número de planes de acción del empleado
                    if ($this->cantidad_requerida > $this->planes_de_accion_empleado->count()) {
                        return true; // Estado pendiente
                    } else {
                        return false; // Estado no pendiente
                    }
                // Verificar si la segunda fase del plan de mejora está activa
                } elseif ($this->plan_de_mejora->segunda_fase_activa) {
                    // Comparar el número de planes de acción del empleado con los realizados
                    if ($this->planes_de_accion_empleado->count() > $this->planes_de_accion_empleado_realizados->count()) {
                        // Nueva lógica: Verificar si algún plan tiene la fecha de revisión pasada y no está realizado
                        foreach ($this->planes_de_accion_empleado as $plan) {
                            if ($plan->fecha_de_revision < now() && $plan->estado_id == 1) { // Estado 1 = No realizado
                                return true; // Estado pendiente
                            }
                        }
                        return false; // Estado no pendiente
                    } else {
                        return false; // Estado no pendiente
                    }
                } else {
                    return false; // Ninguna fase activa, estado no pendiente
                }
            } else {
                return false; // Plan de mejora no activo, estado no pendiente
            }
        } else {
            return false; // No hay plan de mejora asociado, estado no pendiente
        }
        // Retorno final redundante
        return false;
    }

    //scope habilitado
    public function scopeHabilitado($query)
    {
        return $query->where('habilitado',1);
    }

    public function planesDeMejora()
    {
        return $this->hasMany(PlanesDeAccion::class, 'encargados_planes_de_accion_id','id');
    }
}
