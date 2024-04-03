<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Respuesta extends Model
{
	use HasFactory;
    use SoftDeletes;
	
    public $timestamps = true;

    protected $table = 'respuestas';

    protected $fillable = ['pregunta_id','opcion_id','valor_numerico','valor_texto','evaluado_id'];
	
    public function pregunta()
    {
        return $this->belongsTo(Pregunta::class,'pregunta_id','id');
    }

    // public function opcion()
    // {
    //     return $this->belongsTo(Opcion::class,'opcion_id','id');
    // }

    public function evaluado()
    {
        return $this->belongsTo(Personal::class,'evaluado_id','id');
    }

}
