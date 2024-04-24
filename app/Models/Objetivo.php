<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Objetivo extends Model
{
	use HasFactory;
    use SoftDeletes;
	
    public $timestamps = true;

    protected $table = 'objetivos';

    protected $fillable = ['resultado','evaluado_id','evaluador_id','tipo_objetivo_id','descripcion','evidencia','evaluador_has_evaluado_id'];
	
    public function tipo_objetivo()
    {
        return $this->hasOne('App\Models\TiposDeObjetivo', 'id', 'tipo_objetivo_id');
    }

}
