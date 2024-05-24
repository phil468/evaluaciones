<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class 
Evaluacione extends Model
{
	use HasFactory;
    use SoftDeletes;
	
    public $timestamps = true;

    protected $table = 'evaluaciones';

    protected $fillable = [
        'eid',
        'title',
        'date',
        'status',
        'nombre_para_mostrar',
        'campania',
        'mes',
        'anio',
        'fecha_inicio',
        'fecha_fin',
        'identificador',
        'tipo_de_evaluacion_id',
        'minimo',
        'maximo',
        'fecha_inicio_primera_fase_matricula',
        'fecha_fin_primera_fase_matricula',
        'fecha_inicio_segunda_fase',
        'fecha_fin_segunda_fase',
    ];

    protected $dates = [
        'date',
        'fecha_inicio',
        'fecha_fin',
        'fecha_segunda_fase',
        'fecha_fin_segunda_fase',
        'fecha_inicio_primera_fase_matricula',
        'fecha_fin_primera_fase_matricula',
    ];

    /// quiero deveolver un valor verdadero si hoy se encuentra entre $evaluador_has_evaluado->evaluacion->fecha_inicio y $evaluador_has_evaluado->evaluacion->fecha_fin
    public function getActivaAttribute()
    {
        return $this->fecha_inicio <= now() && $this->fecha_fin >= now();
    }

    public function getPrimeraFaseActivaAttribute()
    {
        // dd($this->fecha_inicio_primera_fase_matricula <= now());
        // dd(now()->startOfDay());
        // dd($this->fecha_fin_primera_fase_matricula);
        return $this->fecha_inicio_primera_fase_matricula <= now() && $this->fecha_fin_primera_fase_matricula->endOfDay() >= now() && $this->tipo_de_evaluacion_id == 2;
    }

    public function getSegundaFaseActivaAttribute()
    {
        return $this->fecha_inicio_segunda_fase <= now() && $this->fecha_fin_segunda_fase->endOfDay() >= now() && $this->tipo_de_evaluacion_id == 2;
    }

    public function preguntas() {
        return $this->hasMany(Pregunta::class,'evaluacion_id','id');
    }
    //la evaluacion tiene preguntas y cada pregunta pertenece a una sección de la evaluación, llego a las seccions a traves de la pregunta
    //sin repetir las secciones
    public function secciones() {
        return $this->hasManyThrough(Seccione::class,Pregunta::class,'evaluacion_id','id','id','seccion_id');
    }

    public function seccionesUnicas() {
        return $this->secciones()->get()->unique('id');
    }

    public function recordatorios() {
        return $this->hasMany(Recordatorio::class,'id_evaluacion','id');
    }
    
    public function evaluadores() {
        return $this->hasMany(EvaluadorHasEvaluado::class,'evaluacion_id','id');
    }

    //lista de correos de evaluadores que tienen evaluaciones realizadas en 0 (evaluador_has_evaluados.realizado = 0 )
    public function evaluadoresSinRealizar() {
        //solo correos
        return $this->evaluadores()->where('realizado',0)->get()->pluck('evaluador.correo_empresa');
        //return $this->evaluadores()->where('realizado',0)->get();
    }

    public function tipoDeEvaluacion() {
        return $this->belongsTo(TipoDeEvaluacione::class,'tipo_de_evaluacion_id','id');
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

    // public function evaluacione_has_preguntas()
    // {
    //     return $this->hasMany(EvaluacioneHasPregunta::class,'evaluacione_id','id');
    // }

    // public function evaluacione_has_personal()
    // {
    //     return $this->hasMany(EvaluacioneHasPersonal::class,'evaluacione_id','id');
    // }

    // public function evaluacione_has_respuestas()
    // {
    //     return $this->hasMany(EvaluacioneHasRespuesta::class,'evaluacione_id','id');
    // }

    // public function evaluacione_has_resultados()
    // {
    //     return $this->hasMany(EvaluacioneHasResultado::class,'evaluacione_id','id');
    // }   

    // public function evaluacione_has_resultados_preguntas()
    // {
    //     return $this->hasMany(EvaluacioneHasResultadoPregunta::class,'evaluacione_id','id');
    // }   

    //scopes
    public function scopeActiva($query) {
        return $query->where('status',1);
    }

    public function scopeInactiva($query) {
        return $query->where('status',0);
    }

    public function scopeFinalizada($query) {
        return $query->where('status',2);
    }

    public function scopePorTipo($query,$tipo) {
        return $query->where('tipo_de_evaluacion_id',$tipo);
    }

    public function scopeEvaluacionPorObjetivos($query) { 
        return $query->where('tipo_de_evaluacion_id',2); 
    }

    public function scopePorFecha($query,$fecha) {
        return $query->where('date',$fecha);
    }

    public function scopePorFechaInicio($query,$fecha) {
        return $query->where('fecha_inicio',$fecha);
    }

    public function scopePorFechaFin($query,$fecha) {
        return $query->where('fecha_fin',$fecha);
    }

    public function scopePorCampania($query,$campania) {
        return $query->where('campania',$campania);
    }

    public function scopePorMes($query,$mes) {
        return $query->where('mes',$mes);
    }

    public function scopePorAnio($query,$anio) {
        return $query->where('anio',$anio);
    }

    public function scopePorIdentificador($query,$identificador) {
        return $query->where('identificador',$identificador);
    }

    public function scopePorNombreParaMostrar($query,$nombre) {
        return $query->where('nombre_para_mostrar',$nombre);
    }

    public function scopePorEid($query,$eid) {
        return $query->where('eid',$eid);
    }

    public function scopePorEvaluador($query,$evaluador) {
        return $query->whereHas('evaluadores',function($q) use ($evaluador) {
            $q->where('evaluador_id',$evaluador);
        });
    }

    public function scopePorEvaluado($query,$evaluado) {
        return $query->whereHas('evaluadores',function($q) use ($evaluado) {
            $q->where('evaluado_id',$evaluado);
        });
    }

    public function scopePorEvaluadorEvaluado($query,$evaluador,$evaluado) {
        return $query->whereHas('evaluadores',function($q) use ($evaluador,$evaluado) {
            $q->where('evaluador_id',$evaluador)->where('evaluado_id',$evaluado);
        });
    }

    public function scopePorEvaluadorEvaluadoRealizado($query,$evaluador,$evaluado,$realizado) {
        return $query->whereHas('evaluadores',function($q) use ($evaluador,$evaluado,$realizado) {
            $q->where('evaluador_id',$evaluador)->where('evaluado_id',$evaluado)->where('realizado',$realizado);
        });
    }

    public function scopePorEvaluadorEvaluadoRealizadoFecha($query,$evaluador,$evaluado,$realizado,$fecha) {
        return $query->whereHas('evaluadores',function($q) use ($evaluador,$evaluado,$realizado,$fecha) {
            $q->where('evaluador_id',$evaluador)->where('evaluado_id',$evaluado)->where('realizado',$realizado)->where('fecha',$fecha);
        });
    }


	
}
