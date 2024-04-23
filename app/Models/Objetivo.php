<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Objetivo extends Model
{
    use HasFactory;

    protected $table = 'objetivos';
    
    protected $fillable = [
        'descripcion',
        'cantidad',
        'evaluado_id',
        'tipo_objetivo',
    ];

    // public function evalua() {
    //     return $this->belongsTo(EvaluadorHasEvaluado::class,'evalua_id','id');
    // }
}
