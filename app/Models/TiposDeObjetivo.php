<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TiposDeObjetivo extends Model
{
    use HasFactory;

    // constante competencias
    const NUMERICO = 1;
    const PORCENTAJE = 2;
    const CONDICIONAL = 3;

    protected $table = 'tipo_de_objetivos';
    
    protected $fillable = [
        'unidad',
        'simbolo',
    ];

    public function getNameAttribute()
    {
        return $this->unidad.' ('.$this->simbolo.')';
    }

}
