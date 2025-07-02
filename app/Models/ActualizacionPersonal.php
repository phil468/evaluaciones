<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActualizacionPersonal extends Model
{
    protected $table = 'actualizaciones_personal';
    
    protected $fillable = [
        'tipo',
        'detalles',
        'ejecutado_por',
        'ejecutado_por_sistema'
    ];
    
    protected $casts = [
        'detalles' => 'json',
        'ejecutado_por_sistema' => 'boolean',
    ];
    
    public function usuario()
    {
        return $this->belongsTo(User::class, 'ejecutado_por');
    }
}