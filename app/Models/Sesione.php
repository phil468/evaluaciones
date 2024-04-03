<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sesione extends Model
{
	use HasFactory;
    use SoftDeletes;
	
    public $timestamps = true;

    protected $table = 'sesiones';

    protected $fillable = ['capacitacion_id','numero_de_sesion','fecha','hora_inicio','hora_fin','active','synced'];
	
    public function capacitacion()
    {
        return $this->belongsTo(Capacitacione::class,'capacitacion_id','id');
    }

    // public function asistencia()
    // {
    //    return $this->hasMany(Asistencia::class,'sesion_id','id');
    // }

    public function asistencia_count()
    {
        return $this->hasMany(Asistencia::class,'sesion_id','id')->count();
    }

    public function personal()
    {
        return $this->belongsToMany(CapacitacionHasPersonal::class,'asistencia','sesion_id','capacitacion_has_personal_id');
    }
}