<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlanesDeMejoraHasEvidencia extends Model
{
	use HasFactory;
    use SoftDeletes;
	
    public $timestamps = true;

    protected $table = 'planes_de_mejora_has_evidencias';

    protected $fillable = ['planes_de_accion_id','ruta','name','estado'];
    
    public function planDeAccion()
    {
        return $this->belongsTo(PlanesDeAccion::class, 'planes_de_accion_id', 'id');
    }
	
}
