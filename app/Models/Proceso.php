<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proceso extends Model
{
	use HasFactory;
    use SoftDeletes;
	
    public $timestamps = true;

    protected $table = 'tipo_de_evaluaciones';

    protected $fillable = ['name','estado','tipo_de_proceso_id'];

    public function tipo_de_proceso()
    {
        return $this->belongsTo('App\Models\TipoDeProceso','id', 'tipo_de_proceso_id');
    }
	
}
