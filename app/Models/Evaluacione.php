<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Evaluacione extends Model
{
	use HasFactory;
    use SoftDeletes;
	
    public $timestamps = true;

    protected $table = 'evaluaciones';

    protected $fillable = ['eid','title','date','status'];

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
	
}
