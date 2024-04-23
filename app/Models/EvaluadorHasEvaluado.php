<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EvaluadorHasEvaluado extends Model
{
	use HasFactory;
    use SoftDeletes;
	
    public $timestamps = true;

    protected $table = 'evaluador_has_evaluados';

    protected $fillable = ['evaluador_id','evaluado_id','evaluacion_id','realizado','tipo_de_evaluacion_id'];
	
    public function evaluador()
    {
        return $this->belongsTo(Personal::class,'evaluador_id','id');
    }

    public function evaluado()
    {
        return $this->belongsTo(Personal::class,'evaluado_id','id');
    }

    public function evaluacion()
    {
        return $this->belongsTo(Evaluacione::class,'evaluacion_id','id');
    }

    // campo total_realizados 
    public function getTotalRealizadosAttribute()
    {
        return $this->where('evaluador_id',$this->evaluador_id)->where('realizado',1)->count();
    }

    
    
}
