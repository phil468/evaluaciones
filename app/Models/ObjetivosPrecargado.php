<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ObjetivosPrecargado extends Model
{
	use HasFactory;
    use SoftDeletes;
	
    public $timestamps = true;

    protected $table = 'objetivos_precargados';

    protected $fillable = [
        'meta','grupal','porcentaje_de_participacion','evidencias','resultado_anterior_o_esperado','tipo_objetivo_id','minimo','maximo','valor',
        'porcentaje_de_logro_STI','peso_ponderado','evaluacion_id','tipo_de_jerarquia_id'];
	
    public function tipo_objetivo()
    {
        return $this->belongsTo(TiposDeObjetivo::class,'tipo_objetivo_id','id');
    }

    public function evaluacion()
    {
        return $this->belongsTo(Evaluacione::class,'evaluacion_id','id');
    }

    // set y get de porcentaje_de_participacion
    public function setPorcentajeDeParticipacionAttribute($value)
    {
        $this->attributes['porcentaje_de_participacion'] = ($value/100);
    }

    public function getPorcentajeDeParticipacionAttribute($value)
    {
        return ($value*100);
    }

    // set y get de minimo
    public function setMinimoAttribute($value)
    {
        if ($this->tipo_objetivo_id == 2) { // si es porcentaje
            $this->attributes['minimo'] = ($value/100);
        } else {
            $this->attributes['minimo'] = $value;
        }

        // $this->attributes['minimo'] = ($value/100);
    }

    public function getMinimoAttribute($value)
    {
        
        if ($this->tipo_objetivo_id == 2) { // si es porcentaje
            return ($value*100);
        } else {
            return $value;
        }
        // return ($value*100);
    }

    // set y get de maximo
    public function setMaximoAttribute($value)
    {
        if ($this->tipo_objetivo_id == 2) { // si es porcentaje
            $this->attributes['maximo'] = ($value/100);
        } else {
            $this->attributes['maximo'] = $value;
        }
        
        // $this->attributes['maximo'] = ($value/100);
    }

    public function getMaximoAttribute($value)
    {
        if ($this->tipo_objetivo_id == 2) { // si es porcentaje
            return ($value*100);
        } else {
            return $value;
        }
        // return ($value*100);
    }

    // set y get de resultado_anterior_o_esperado
    public function setResultadoAnteriorOEsperadoAttribute($value)
    {
        if ($this->tipo_objetivo_id == 2) { // si es porcentaje
            $this->attributes['resultado_anterior_o_esperado'] = ($value/100.00);
        } else {
            $this->attributes['resultado_anterior_o_esperado'] = $value;
        }
    }

    public function getResultadoAnteriorOEsperadoAttribute($value)
    {
        if ($this->tipo_objetivo_id == 2) { // si es porcentaje
            return ($value*100);
        } else {
            return $value;
        }
    }

}
