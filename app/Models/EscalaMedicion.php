<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EscalaMedicion extends Model
{
	use HasFactory;
    use SoftDeletes;    
	
    public $timestamps = true;

    protected $table = 'escala_mediciones';

    protected $fillable = [
        'valor_menor',
        'valor_mayor',
        'name',
        'rango_menor',
        'rango_mayor',
        'color',
        'interpretacion',
        'recomendacion',
        'estado',
    ];

    // protected $casts = [
    //     'valor_menor' => 'integer',
    //     'valor_mayor' => 'integer',
    //     'estado' => 'boolean',
    // ];
}