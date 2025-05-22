<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComiteCalibracion extends Model
{
    use HasFactory;

    protected $table = 'comites_calibracion';

    protected $fillable = [
        'personal_id',
        // 'competencia_id',
        'campania_id',
        'comentario',
        'area',
        'nivel_jerarquico',
        // 'estado',
    ];

    // Relaciones (ajusta los modelos según corresponda)
    public function personal()
    {
        return $this->belongsTo(Personal::class, 'personal_id');
    }

    // public function competencia()
    // {
    //     return $this->belongsTo(Seccione::class, 'competencia_id');
    // }

    public function campania()
    {
        return $this->belongsTo(Campania::class, 'campania_id');
    }
    
    public function personas()
    {
        return $this->hasMany(ComiteHasPersona::class, 'comite_calibracion_id');
    }
}