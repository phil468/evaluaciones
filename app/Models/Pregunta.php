<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pregunta extends Model
{
	use HasFactory;
    use SoftDeletes;
	
    public $timestamps = true;

    protected $table = 'preguntas';

    protected $fillable = ['seccion_id','evaluacion_id','qid','pregunta','tipo','opciones','numero_orden'];

    public function evaluacion()
    {
        return $this->belongsTo(Evaluacione::class,'evaluacion_id','id');
    }

    public function seccion()
    {
        return $this->belongsTo(Seccione::class,'seccion_id','id');
    }

    public function respuestas()
    {
        return $this->hasMany(Respuesta::class,'pregunta_id','id');
    }

    
	
}
