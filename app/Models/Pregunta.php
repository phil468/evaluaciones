<?php 

namespace App\Models;

use App\Imports\PreguntasImport;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Maatwebsite\Excel\Facades\Excel;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Pregunta extends Model implements Auditable
{
    use HasFactory;
    use SoftDeletes;
    use AuditableTrait;
	
    public $timestamps = true;

    protected $table = 'preguntas';

    protected $fillable = [
        'seccion_id',
        'evaluacion_id',
        'qid',
        'pregunta',
        'tipo',
        'opciones',
        'numero_orden',
        'campania_has_competencia_id',
        'estado',
        'dominio_id',
    ];

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

    public static function importarPreguntas($file, $evaluacion_id, $seccion_id)
    {
        try {
            Excel::import(new PreguntasImport($evaluacion_id, $seccion_id), $file);
            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function dominio()
    {
        return $this->belongsTo(Dominio::class, 'dominio_id', 'id');
    }

    public function campaniaHasCompetencias()
    {
        return $this->belongsTo(CampaniaHasCompetencia::class, 'campania_has_competencia_id', 'id');
    }

    public function competencia()
    {
        return $this->belongsTo(Competencia::class, 'campania_has_competencia_id', 'id');
    }



	
}
