<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ObjetivoPrecargadoHasEvidencia extends Model
{
	use HasFactory;
    use SoftDeletes;
	
    public $timestamps = true;

    protected $table = 'objetivo_precargado_has_evidencias';

    protected $fillable = ['objetivo_precargado_id','ruta','name','estado'];
    
    public function objetivo_precargado()
    {
        return $this->belongsTo(ObjetivosPrecargado::class, 'objetivo_precargado_id', 'id');
    }
	
}
