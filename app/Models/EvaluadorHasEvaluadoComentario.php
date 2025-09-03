<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluadorHasEvaluadoComentario extends Model
{
    protected $table = 'evaluador_has_evaluado_comentarios';

    protected $fillable = [
        'evaluador_has_evaluado_id',
        'evaluado_id',
        'campania_id',
        'campania_has_competencia_id',
        'tipo_relacion_jerarquica_id',
        'comentario',
    ];

    public function competencia()
    {
        return $this->belongsTo(CampaniaHasCompetencia::class, 'campania_has_competencia_id');
    }
}