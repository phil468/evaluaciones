<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoDePuesto extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = true;

    protected $table = 'tipo_de_puestos';

    protected $fillable = [
        'name',
        'nivel_jerarquico_id',
        'estado',
    ];

    public function nivelJerarquico()
    {
        return $this->belongsTo(NivelJerarquico::class);
    }
}