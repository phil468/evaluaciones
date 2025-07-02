<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DominioHasPregunta extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'dominio_has_pregunta';

    protected $fillable = [
        'dominio_id',
        'pregunta_id',
        'numero_orden',
        'estado',
    ];

    public function dominio()
    {
        return $this->belongsTo(Dominio::class);
    }

    public function pregunta()
    {
        return $this->belongsTo(Pregunta::class);
    }
}