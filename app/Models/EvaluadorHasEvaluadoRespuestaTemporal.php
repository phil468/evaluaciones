<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EvaluadorHasEvaluadoRespuestaTemporal extends Model
{
    protected $table = 'evaluador_has_evaluado_respuestas_tmp';

    protected $fillable = [
        'evaluador_has_evaluado_id',
        'pregunta_id',
        'valor_numerico',
    ];
}
