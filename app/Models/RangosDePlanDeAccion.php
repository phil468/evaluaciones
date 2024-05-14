<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RangosDePlanDeAccion extends Model
{
	use HasFactory;
    use SoftDeletes;
	
    public $timestamps = true;

    protected $table = 'rangos_de_plan_de_accion';

    protected $fillable = ['name','color','estado','nombre_para_mostrar','descripción','rango_mayor'];
	
}
