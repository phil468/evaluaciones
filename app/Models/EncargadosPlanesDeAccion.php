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
    ];

    public function empleado()
    {
        return $this->belongsTo(Personal::class, 'empleado_id','id');
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
        return $this->hasMany(PlanesDeAccion::class, 'empleado_id','empleado_id');
    }
    
    public function planes_de_accion_empleado_realizados()
    {
        return $this->hasMany(PlanesDeAccion::class, 'empleado_id','empleado_id')->where('estado_id','<>',1);
    }

    public function plan_de_mejora()
    {
        return $this->belongsTo(PlanesConfiguracion::class, 'planes_de_accion_configuracion_id','id');        
    }

    //cuando evaluacion->tipo_evaluacion_id sea 2 comparar objetivos con la cantidad de objetivos, si es mejor el estado de la evaluacion es pendiente
    public function getEstadoPendienteAttribute()
    {
        if($this->plan_de_mejora) {
                if($this->plan_de_mejora->activa) {
                    // if($this->evaluacion->tipo_de_evaluacion_id == 2) {
                        if($this->plan_de_mejora->primera_fase_activa) {
                            if($this->cantidad_requerida > $this->planes_de_accion_empleado->count()) {
                                return true;
                            }
                            else {
                                return false;
                            }
                        }elseif($this->plan_de_mejora->segunda_fase_activa) {
                            if($this->planes_de_accion_empleado->count() > $this->planes_de_accion_empleado_realizados->count()) {
                                return true;
                            }
                            else {
                                return false;
                            }
                        }
                        else {
                            return false;
                        }
                    // }
                    // elseif($this->evaluacion->tipo_de_evaluacion_id == 1) {
                    //     if($this->realizado == 1) {
                    //         return false;
                    //     }
                    //     else {
                    //         return true;
                    //     }
                    // }
                    // else {
                    //     return false;
                    // }
                } else {
                    return false;
                }
        }
        else {
            return false;
        }
        return false;
    }	
}

// public function getEstadoRegistradosAttribute() {

// }

