<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Objetivo extends Model implements Auditable
{
	use HasFactory;
    use SoftDeletes;	
    use AuditableTrait;

    public $timestamps = true;

    protected $table = 'objetivos';

    protected $fillable = [
        'resultado','evaluado_id','evaluador_id','tipo_objetivo_id','descripcion','evidencia','evaluador_has_evaluado_id',
        'meta','grupal','porcentaje_de_participacion','evidencias','resultado_anterior_o_esperado','minimo','maximo','valor',
        'porcentaje_de_logro_STI','peso_ponderado','evaluacion_id','objetivo_precargado_id'
    ];
	
    public function tipo_objetivo()
    {
        return $this->hasOne('App\Models\TiposDeObjetivo', 'id', 'tipo_objetivo_id');
    }

    public function evaluado()
    {
        return $this->belongsTo(Personal::class, 'evaluado_id','id');
    }

    public function evaluador()
    {
        return $this->belongsTo(Personal::class, 'evaluador_id','id');
    }

    public function evaluacion()
    {
        return $this->belongsTo(Evaluacione::class, 'evaluacion_id','id');
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
            $this->attributes['minimo'] = ($value/100);
        }
    
        public function getMinimoAttribute($value)
        {
            return ($value*100);
        }
    
        // set y get de maximo
        public function setMaximoAttribute($value)
        {
            $this->attributes['maximo'] = ($value/100);
        }
    
        public function getMaximoAttribute($value)
        {
            return ($value*100);
        }
    
        // set y get de resultado_anterior_o_esperado
        public function setResultadoAnteriorOEsperadoAttribute($value)
        {
            if ($this->tipo_objetivo_id == 2) { // si es porcentaje
                $this->attributes['resultado_anterior_o_esperado'] = ($value/100);
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

    // public function evaluador_has_evaluado()
    // {
    //     return $this->belongsTo(EvaluadorHasEvaluadoObjetivo::class, 'evaluador_has_evaluado_id','id');
    // }

    // public function getEvidenciaAttribute()
    // {
    //     return $this->evidencia??'';
    // }

    // public function getResultadoAttribute()
    // {
    //     return $this->resultado??'';
    // }

}
