<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResumenRespuestasEvaluacionDesempenoCompetencia extends Model
{
    use HasFactory;

    protected $table = 'resumen_respuestas_evaluacion_desempeno_competencias';

    protected $fillable = [
        'personal_id',
        'competencia_id',
        'pregunta_id',
        'puntaje',
        'puntaje_calibrado',
        'area_id',
        'campania_id',
        'comite_calibracion_id',
    ];

    public function personal()
    {
        return $this->belongsTo(Personal::class, 'personal_id');
    }

    public function competencia()
    {
        return $this->belongsTo(Seccione::class, 'competencia_id'); // O el modelo correcto
    }

    public function pregunta()
    {
        return $this->belongsTo(Pregunta::class, 'pregunta_id');
    }
    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }
    public function campania()
    {
        return $this->belongsTo(Campania::class, 'campania_id');
    }

    public function comite()
    {
        return $this->belongsTo(ComiteCalibracion::class, 'comite_calibracion_id');
    }

}
