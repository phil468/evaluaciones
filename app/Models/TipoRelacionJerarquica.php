<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoRelacionJerarquica extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tipo_relacion_jerarquicas';

    protected $fillable = [
        'name',
        'estado'
    ];

    protected $casts = [
        // 'estado' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // Relaciones con otros modelos pueden añadirse aquí

    public function pesos()
    {
        return $this->hasMany(Peso::class, 'tipo_relacion_jerarquica_id');
    }
}