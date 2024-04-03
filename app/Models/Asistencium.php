<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asistencium extends Model
{
	use HasFactory;
    use SoftDeletes;
	
    public $timestamps = true;

    protected $table = 'asistencia';

    protected $fillable = ['sesion_id',
    'active','observaciones',
    'capacitacion_has_personal_id',
    'synced'
    ];

    public function capacitacion_has_personal()
    {
        return $this->belongsTo(CapacitacionHasPersonal::class,'capacitacion_has_personal_id','id');
    }

    public function sesion()
    {
        return $this->belongsTo(Sesione::class,'sesion_id','id');
    }

    // public function personal()
    // {
    //     return $this->belongsTo(Personal::class,'personal_id','id');
    // }
    // public function sesion()
    // {
    //     return $this->belongsTo(Sesione::class,'sesion_id','id');
    // }

    // public function empresa()
    // {
    //     return $this->belongsTo(Empresa::class,'empresa_id','id');
    // }

    // public function gerencia()
    // {
    //     return $this->belongsTo(Gerencia::class,'gerencia_id','id');
    // }

    // public function area()
    // {
    //     return $this->belongsTo(Area::class,'area_id','id');
    // }


	
}
