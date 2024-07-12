<?php 

namespace App\Models;

use App\Http\Livewire\Evaluacion;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;

class Respuesta extends Model
{
	use HasFactory;
    use SoftDeletes;
	
    public $timestamps = true;

    protected $table = 'respuestas';

    protected $fillable = ['pregunta_id','opcion_id','valor_numerico','valor_texto','evaluado_id'];
	
    protected $casts = [
        'evaluado_id' => 'encrypted',
        'pregunta_id' => 'encrypted',
        'valor_numerico' => 'encrypted',
    ];

    public function pregunta()
    {
        return $this->belongsTo(Pregunta::class,'pregunta_id','id');
    }

    // public function pregunta()
    // {
    //     $pregunta_id = Crypt::decryptString($this->attributes['pregunta_id']);
    //     return $this->belongsTo(Pregunta::class, $pregunta_id, 'id');
    // }

    public function evaluado()
    {
        return $this->belongsTo(Personal::class,'evaluado_id','id');
    }

    public function getAreaDeEvaluadoAttribute()
    {
        return EvaluadorHasEvaluado::where('evaluado_id',$this->evaluado_id)
        ->where('evaluacion_id',$this->pregunta->evaluacion_id)
        ->first()->area_de_evaluado;
    }

    public function getCargoDeEvaluadoAttribute()
    {
        return EvaluadorHasEvaluado::where('evaluado_id',$this->evaluado_id)
        ->where('evaluacion_id',$this->pregunta->evaluacion_id)->first()->cargo_de_evaluado;
    }

    public function getGerenciaDeEvaluadoAttribute()
    {
        return EvaluadorHasEvaluado::where('evaluado_id',$this->evaluado_id)
        ->where('evaluacion_id',$this->pregunta->evaluacion_id)->first()->gerencia_sub_gerencia_de_evaluado;
    }

    // //desencripto el valor numerico
    // public function getValorNumericoAttribute($value)
    // {
    //     return Crypt::decryptString($value);
    // }

    // //desencripto el valor numerico
    // public function getPreguntaIdAttribute($value)
    // {
    //     return Crypt::decryptString($value);
    // }

    // //desencripto el valor numerico
    // public function getEvaluadoIdAttribute($value)
    // {
    //     return Crypt::decryptString($value);
    // }


   
    
}
