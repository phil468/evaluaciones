<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TiposDeObjetivo extends Model
{
    use HasFactory;

    protected $table = 'tipo_de_objetivos';
    
    protected $fillable = [
        'unidad',
        'simbolo',
    ];

}
