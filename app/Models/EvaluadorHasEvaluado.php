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
        'evaluador_id','evaluado_id','evaluacion_id','realizado',
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

    protected $appends = ['cantidad_de_objetivos_registrados'];
	
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
        return Objetivo::where('evaluador_has_evaluado_id',$this->id)
        ->where('estado_id',1)->get()
        ->count();
    }
    
}
