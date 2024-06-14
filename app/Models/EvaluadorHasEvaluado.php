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

    ];

    protected $appends = ['cantidad_de_objetivos_registrados','cantidad_de_objetivos_no_registrados','estado_pendiente'];
	
    public function evaluador()
    {
        return $this->belongsTo(Personal::class,'evaluador_id','id');
    }

    public function evaluado()
    {
        return $this->belongsTo(Personal::class,'evaluado_id','id');
    }

    public function evaluacion()
    {
        return $this->belongsTo(Evaluacione::class,'evaluacion_id','id');
    }

    public function objetivos()
    {
        return $this->hasMany(Objetivo::class,'evaluador_has_evaluado_id','id');
    }

    // objeticos con estado null
    public function objetivosNoRegistrados()
    {
        return $this->hasMany(Objetivo::class,'evaluador_has_evaluado_id','id')->where('estado_id',null);
    }

    public function objetivosRegistrados()
    {
        return $this->hasMany(Objetivo::class,'evaluador_has_evaluado_id','id')->where('estado_id',1);
    }

    //cuando evaluacion->tipo_evaluacion_id sea 2 comparar objetivos con la cantidad de objetivos, si es mejor el estado de la evaluacion es pendiente
    public function getEstadoPendienteAttribute()
    {
        if($this->evaluacion && $this->evaluacion->tipo_de_evaluacion_id == 2)
        {
            if($this->objetivos->count() > $this->objetivosRegistrados->count())
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            if($this->realizado == 1)
            {
                return false;
            }
            else
            {
                return true;
            }
        }
    }


    // campo total_realizados 
    public function getTotalRealizadosAttribute()
    {
        return $this->where('evaluador_id',$this->evaluador_id)->where('realizado',1)->count();
    }

    public function getCantidadDeObjetivosRealizadosAttribute()
    {
        // return $this->objetivos()->registrados()->count();
        return Objetivo::where('evaluador_has_evaluado_id',$this->id)
        // ->where('estado_id',1)
        ->get()
        ->count();
    }

    public function getCantidadDeObjetivosRegistradosAttribute()
    {
        // dd(Objetivo::where('evaluador_has_evaluado_id',$this->id)
        // ->where('estado_id',1)->get());
        return Objetivo::where('evaluado_id',$this->evaluado_id)
        ->where('evaluacion_id',$this->evaluacion_id)
        ->where('estado_id',1)->get()
        ->count();
    }

    // public function getCantidadDeObjetivosNoRegistradosAttribute()
    // {
    //     // return $this->objetivos()->noRegistradosCont();
    //     // // dd(Objetivo::where('evaluador_has_evaluado_id',$this->id)
    //     // // ->where('estado_id',1)->get());
    //     return Objetivo::where('evaluado_id',$this->evaluado_id)
    //     ->where('evaluacion_id',$this->evaluacion_id)
    //     ->where('estado_id',null)->get()
    //     ->count();
    // }

    //quiero contar objetivos con estado_id null relacionados por evaluado_id y evaluacion_id
    public function getCantidadDeObjetivosNoRegistradosAttribute()
    {
        return Objetivo::where('evaluado_id',$this->evaluado_id)
        ->where('evaluacion_id',$this->evaluacion_id)
        ->where('estado_id',null)->get()
        ->count();
    } 
    
    public function getCantidadDeObjetivosCompletadosAttribute()
    {
        return Objetivo::where('evaluador_has_evaluado_id',$this->id)
        ->where('estado_id',2)->get()
        ->count();
    }

    public function getCantidadDeObjetivosNoCompletadosAttribute()
    {
        return Objetivo::where('evaluador_has_evaluado_id',$this->id)
        ->where('estado_id',null)->get()
        ->count();
    }
    
}
