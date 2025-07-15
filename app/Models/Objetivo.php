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
        'resultado',
        'evaluado_id',
        'evaluador_id',
        'tipo_objetivo_id',
        'descripcion',
        'evidencia',
        'evaluador_has_evaluado_id',
        'meta',
        'grupal',
        'porcentaje_de_participacion',
        'evidencias',
        'resultado_anterior_o_esperado',
        'minimo',
        'maximo',
        'valor',
        'porcentaje_de_logro_STI',
        'peso_ponderado',
        'evaluacion_id',
        'objetivo_precargado_id',
        'estado_id',
        'sin_evidencias'
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

    public function evaluador_has_evaluado()
    {
        return $this->belongsTo(EvaluadorHasEvaluado::class, 'evaluador_has_evaluado_id','id');
    }

    public function evidencias()
    {
        return $this->hasMany(ObjetivoHasEvidencia::class, 'objetivo_id','id');
    }

    public function estado()
    {
        return $this->belongsTo(EstadosDeObjetivo::class, 'estado_id','id');
    }

    public function objetivo_precargado()
    {
        return $this->belongsTo(ObjetivosPrecargado::class, 'objetivo_precargado_id','id');
    }

    public function getPorcentajeDeLogroSTIAttribute($value)
    {
        // Si es grupal y tiene objetivo_precargado relacionado, usa los valores del objetivo precargado
        if ($this->grupal && $this->objetivo_precargado_id && $this->objetivo_precargado) {
            $valorPrecargado = $this->objetivo_precargado->porcentaje_de_logro_STI;
            return $valorPrecargado;
        }
        
        // Comportamiento original
        return number_format($value*100.00, 2, '.', '');
    }

    public function getPesoPonderadoAttribute($value)
    {
        // Si es grupal y tiene objetivo_precargado relacionado, usa los valores del objetivo precargado
        if ($this->grupal && $this->objetivo_precargado_id && $this->objetivo_precargado) {
            $valorPrecargado = $this->objetivo_precargado->peso_ponderado;
            return $valorPrecargado;
        }
        
        // Comportamiento original
        return number_format($value*100.00, 2, '.', '');
    }

    public function getValorAttribute($value)
    {
        // if ($this->grupal && $this->objetivo_precargado_id) {
        //     return $this->objetivo_precargado->valor;
        // }
        // return $value;
        // Si es grupal y tiene objetivo_precargado relacionado, usa los valores del objetivo precargado
        if ($this->grupal && $this->objetivo_precargado_id && $this->objetivo_precargado) {
            if ($this->tipo_objetivo_id == 2) { // si es porcentaje
                $valorPrecargado = $this->objetivo_precargado->valor;
                return is_null($valorPrecargado) ? '' : $valorPrecargado;
            } else {
                $valorPrecargado = $this->objetivo_precargado->valor;
                return is_null($valorPrecargado) ? '' : $valorPrecargado;
            }
        }
        
        // Comportamiento original
        if ($this->tipo_objetivo_id == 2) { // si es porcentaje
            return is_null($value) ? '' : number_format($value*100.00, 2, '.', '');
        } else {
            return is_null($value) ? '' : number_format($value, 4, '.', '');
        }
    }

    public function scopeRegistrados()
    {
        return $this->where('estado_id',1);
    }

    public function scopeRegistradosCont($id)
    {
        return $this->where('estado_id',1)->where('evaluador_has_evaluado_id',$id);
    }
    
    public function scopeNoRegistradosCont($id)
    {
        return $this->where('estado_id',null)->where('evaluador_has_evaluado_id',$id);
    }

    public function scopeRegistradosContEvaluado($id)
    {
        return $this->where('estado_id',1)->where('evaluado_id',$id);
    }

    public function scopeRegistradosContEvaluador($id)
    {
        return $this->where('estado_id',1)->where('evaluador_id',$id);
    }
    
    // set y get de porcentaje_de_participacion
    public function setPorcentajeDeParticipacionAttribute($value)
    {
        $this->attributes['porcentaje_de_participacion'] = ($value/100.00);
    }
        public function getPorcentajeDeParticipacionAttribute($value)
    {
        return number_format($value*100.00, 2, '.', '');// ($value*100.00);
    }

    // set y get de porcentaje_de_logro_STI
    public function setPorcentajeDeLogroSTIAttribute($value)
    {
        $this->attributes['porcentaje_de_logro_STI'] = ($value/100.00);
    }

    // public function getPorcentajeDeLogroSTIAttribute($value)
    // {
    //     return number_format($value*100.00, 2, '.', '');// ($value*100.00);
    // }

    // set y get de peso_ponderado
    public function setPesoPonderadoAttribute($value)
    {
        $this->attributes['peso_ponderado'] = ($value/100.00);
    }

    // public function getPesoPonderadoAttribute($value)
    // {
    //     return number_format($value*100.00, 2, '.', '');// ($value*100.00);
    // }
    
    // set y get de minimo
    public function setMinimoAttribute($value)
    {
        if ($this->tipo_objetivo_id == 2) { // si es porcentaje
            $this->attributes['minimo'] = ($value/100.00);
        } else {
            $this->attributes['minimo'] = $value;
        }
    }

    public function getMinimoAttribute($value)
    {
        if ($this->tipo_objetivo_id == 2) { // si es porcentaje
            return number_format($value*100.00, 2, '.', '');
        } else {
            return number_format($value, 2, '.', '');
        }
    }

    // set y get de maximo
    public function setMaximoAttribute($value)
    {
        if ($this->tipo_objetivo_id == 2) { // si es porcentaje
            $this->attributes['maximo'] = ($value/100.00);
        } else {
            $this->attributes['maximo'] = $value;
        }
    }

    public function getMaximoAttribute($value)
    {
        if ($this->tipo_objetivo_id == 2) { // si es porcentaje
            return number_format($value*100.00, 2, '.', '');
        } else {
            return number_format($value, 2, '.', '');
        }
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
            return number_format($value*100.00, 2, '.', '');
        } else {
            return number_format($value, 4, '.', '');
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

    // set y get de resultado_anterior_o_esperado
    public function setValorAttribute($value)
    {
        if ($this->tipo_objetivo_id == 2) { // si es porcentaje
            $this->attributes['valor'] = is_null($value) ? '' : (is_numeric($value) ? ($value / 100.00) : null);
        } else {
            $this->attributes['valor'] = is_null($value) ? '' : (is_numeric($value) ? ($value) : null);
        }
    }

    // public function getValorAttribute($value)
    // {
    //     if ($this->tipo_objetivo_id == 2) { // si es porcentaje
    //         return is_null($value) ? '' : number_format($value*100.00, 2, '.', '');
    //     } else {
    //         return is_null($value) ? '' : number_format($value, 4, '.', '');
    //     }
    // }

    public static function calcularSubtotal($evaluador_has_evaluado_id)
    {
        // Obtener todos los objetivos del evaluador
        $objetivos = static::where('evaluador_has_evaluado_id', $evaluador_has_evaluado_id)
            ->with('objetivo_precargado') // Cargar la relación para evitar consultas N+1
            ->get();
        
        // Calcular suma manual usando los accessors
        $suma = 0;
        foreach ($objetivos as $objetivo) {
            // Al acceder a $objetivo->peso_ponderado ya se aplica el accessor
            $suma += floatval(str_replace(',', '', $objetivo->peso_ponderado)) / 100;
        }
        
        return $suma * 100;
        // // Obtener todos los objetivos del evaluador
        // $objetivos = static::where('evaluador_has_evaluado_id', $evaluador_has_evaluado_id)
        //     ->with('objetivo_precargado') // Cargar la relación para evitar consultas N+1
        //     ->get();
        
        // // Calcular suma manual usando los accessors
        // $suma = 0;
        // foreach ($objetivos as $objetivo) {
        //     // Al acceder a $objetivo->peso_ponderado ya se aplica el accessor
        //     $suma += floatval(str_replace(',', '', $objetivo->peso_ponderado)) / 100;
        // }
        
        // return $suma * 100;
        // // // dd(static::where('evaluador_has_evaluado_id', $evaluador_has_evaluado_id)->get());
        // // return static::where('evaluador_has_evaluado_id', $evaluador_has_evaluado_id)
        // //     ->sum('peso_ponderado') * 100;
    }

    public static function calcularTotal($evaluador_has_evaluado_id)
    {
        $subtotal = static::calcularSubtotal($evaluador_has_evaluado_id);
        $evaluacion = EvaluadorHasEvaluado::find($evaluador_has_evaluado_id)->evaluacion;
        
        if ($subtotal >= $evaluacion->maximo) {
            return $evaluacion->maximo;
        } elseif ($subtotal >= $evaluacion->minimo) {
            return $subtotal;
        } else {
            return 0.00;
        }
    }
}
