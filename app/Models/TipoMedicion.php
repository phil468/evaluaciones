<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoMedicion extends Model
{
    use HasFactory;
    use SoftDeletes;
	
    public $timestamps = true;

    protected $table = 'tipo_mediciones';

    protected $fillable = [
        'name',
        'estado',
    ];
}