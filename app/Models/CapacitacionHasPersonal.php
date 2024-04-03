<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CapacitacionHasPersonal extends Model
{
	use HasFactory;
    // use SoftDeletes;
	
    public $timestamps = true;

    protected $table = 'capacitacion_has_personal';

    protected $fillable = 
    [
        'personal_id',
        'capacitacion_id',
        'active',
        'observaciones',
        'empresa_id',
        'gerencia_id',
        'area_id',
        'cargo_id',
        'planilla_id',
        'sede_id',
        'tipo_de_trabajador_id',
        'tipo_de_personal_id',
        'capacitacion_has_personal_id',
        'synced',
    ];
	
    public function personal()
    {
        return $this->belongsTo(Personal::class,'personal_id','id');
    }

    public function capacitacion()
    {
        return $this->belongsTo(Capacitacione::class,'capacitacion_id','id');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class,'empresa_id','id');
    }
    
    public function gerencia()
    {
        return $this->belongsTo(Gerencia::class,'gerencia_id','id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class,'area_id','id');
    }

    public function cargo()
    {
        return $this->belongsTo(Cargo::class,'cargo_id','id');
    }

    public function planilla()
    {
        return $this->belongsTo(Planilla::class,'planilla_id','id');
    }

    public function sede()
    {
        return $this->belongsTo(Sede::class,'sede_id','id');
    }

    public function tipo_de_trabajador()
    {
        return $this->belongsTo(TipoDeTrabajador::class,'tipo_de_trabajador_id','id');
    }

    public function tipo_de_personal()
    {
        return $this->belongsTo(TipoDePersonal::class,'tipo_de_personal_id','id');
    }

    public function sesiones()
    {
        return $this->hasMany(Sesione::class,'capacitacion_has_personal_id','id');
    }

}
