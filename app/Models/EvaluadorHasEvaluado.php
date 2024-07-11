<?php 

namespace App\Models;

use App\Http\Livewire\Objetivos;
use App\Models\Objetivo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EvaluadorHasEvaluado extends Model
{
	use HasFactory;
    use SoftDeletes;
	
    public $timestamps = true;

    protected $table = 'evaluador_has_evaluados';

    protected $fillable = [
        'evaluador_id',
        'evaluado_id',
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
        'grupal',
        'tipo_jerarquia_id',

    ];

    protected $appends = ['cantidad_de_objetivos_registrados','cantidad_de_objetivos_no_registrados','estado_pendiente'];
	
    public function evaluador()
    {
        return $this->belongsTo(Personal::class,'evaluador_id','id');
    }

    public function userEvaluador()
    {
        return $this->belongsTo(User::class,'evaluador_id','personal_id');
    }

    public function evaluado()
    {
        return $this->belongsTo(Personal::class,'evaluado_id','id');
    }

    public function evaluacion()
    {
        return $this->belongsTo(Evaluacione::class,'evaluacion_id','id');
    }

    // campania se obtiene a traves de evaluaciones
    public function campania()
    {
        return $this->evaluacion->campania;
    }

    public function objetivos()
    {
        return $this->hasMany(Objetivo::class,'evaluador_has_evaluado_id','id');
    }
    
    // objetivos con estado null
    public function objetivosNoRegistrados()
    {
        return $this->hasMany(Objetivo::class,'evaluador_has_evaluado_id','id')->where('estado_id',null);
    }

    public function objetivosRegistrados()
    {
        return $this->hasMany(Objetivo::class,'evaluador_has_evaluado_id','id')->whereIn('estado_id',[1,2]);
    }
    
    public function objetivosRealizados()
    {
        return $this->hasMany(Objetivo::class,'evaluador_has_evaluado_id','id')->where('estado_id',2);
    }

    //cuando evaluacion->tipo_evaluacion_id sea 2 comparar objetivos con la cantidad de objetivos, si es mejor el estado de la evaluacion es pendiente
    public function getEstadoPendienteAttribute()
    {
        if($this->evaluacion) {
            if($this->evaluacion->activa) {
                if($this->evaluacion->tipo_de_evaluacion_id == 2) {
                    if($this->evaluacion->primera_fase_activa) {
                        if($this->objetivos->count() > $this->objetivosRegistrados->count()) {
                            return true;
                        }
                        else {
                            return false;
                        }
                    }elseif($this->evaluacion->segunda_fase_activa) {
                        if($this->objetivos->count() > $this->objetivosRealizados->count()) {
                            return true;
                        }
                        else {
                            return false;
                        }
                    }
                    else {
                        return false;
                    }
                }
                elseif($this->evaluacion->tipo_de_evaluacion_id == 1) {
                    if($this->realizado == 1) {
                        return false;
                    }
                    else {
                        return true;
                    }
                }
                else {
                    return false;
                }
            } else {
                return false;
            }
        }
        else {
            return false;
        }
        return false;
    }

    //cuando evaluacion->tipo_evaluacion_id sea 2 comparar objetivos con la cantidad de objetivos, si es mejor el estado de la evaluacion es pendiente
    public function getEstadoNoRealizadoAttribute()
    {
        if($this->evaluacion) {
            // if($this->evaluacion->activa) {
                if($this->evaluacion->tipo_de_evaluacion_id == 2) {
                    if($this->evaluacion->primera_fase_activa) {
                        if($this->objetivos->count() > $this->objetivosRegistrados->count()) {
                            return true;
                        }
                        else {
                            return false;
                        }
                    }elseif($this->evaluacion->segunda_fase_activa) {
                        if($this->objetivos->count() > $this->objetivosRealizados->count()) {
                            return true;
                        }
                        else {
                            return false;
                        }
                    }else {
                        if($this->evaluacion->antes_primera_fase) {
                            if($this->objetivos->count() > $this->objetivosRegistrados->count()) {
                                return true;
                            }
                            else {
                                return false;
                            }
                        } elseif($this->evaluacion->despues_segunda_fase) {
                            if($this->objetivos->count() > $this->objetivosRealizados->count()) {
                                return true;
                            }
                            else {
                                return false;
                            }
                        } else {
                            if($this->objetivos->count() > $this->objetivosRegistrados->count()) {
                                return true;
                            }
                            else {
                                return false;
                            }
                        }
                        return false;
                    }
                }
                elseif($this->evaluacion->tipo_de_evaluacion_id == 1) {
                    if($this->realizado == 1) {
                        return false;
                    }
                    else {
                        return true;
                    }
                }
                else {
                    return false;
                }
        }
        else {
            return false;
        }
        return false;
    }

    public function getCantidadPendienteAttribute($query)
    {
        if($this->evaluacion && $this->evaluacion->activa) {
            if($this->evaluacion->tipo_de_evaluacion_id == 2) {
                if($this->evaluacion->primera_fase_activa) {
                    if($this->objetivos->count() > $this->objetivosRegistrados->count()) {
                        return true;
                    }
                    else {
                        return false;
                    }
                }
                elseif($this->evaluacion->segunda_fase_activa) {
                    if($this->objetivos->count() > $this->objetivosRealizados->count()) {
                        return true;
                    }
                    else {
                        return false;
                    }
                }
                else {
                    return false;
                }
            }
            elseif($this->evaluacion->tipo_de_evaluacion_id == 1) {
                if($this->realizado == 1) {
                    return false;
                }
                else {
                    return true;
                }
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
        return false;
    }

    // campo total_realizados 
    public function getTotalRealizadosAttribute()
    {
        return $this->where('evaluador_id',$this->evaluador_id)->where('realizado',1)->count();
    }

    public function getCantidadDeObjetivosAttribute()
    {
        return Objetivo::
        where('evaluador_has_evaluado_id',$this->id)
        ->count();
    }

    public function getCantidadDeObjetivosRegistradosAttribute()
    {
        return Objetivo::
        where('evaluador_has_evaluado_id',$this->id)
        ->whereIn('estado_id',[1,2])
        ->count();
    }

    //quiero contar objetivos con estado_id null relacionados por evaluado_id y evaluacion_id
    public function getCantidadDeObjetivosNoRegistradosAttribute()
    {
        return Objetivo::
        where('evaluador_has_evaluado_id',$this->id)
        ->where('estado_id',null)
        ->count();
    } 
    
    public function getCantidadDeObjetivosCompletadosAttribute()
    {
        return Objetivo::where('evaluador_has_evaluado_id',$this->id)
        ->where('estado_id',2)
        ->count();
    }

    public function getCantidadDeObjetivosNoCompletadosAttribute()
    {
        return Objetivo::where('evaluador_has_evaluado_id',$this->id)
        ->whereIn('estado_id',[null,1])
        ->count();
    }
    
}
