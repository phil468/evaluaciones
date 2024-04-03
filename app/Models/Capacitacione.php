<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Capacitacione extends Model
{
	use HasFactory;
    use SoftDeletes;
	
    public $timestamps = true;

    protected $table = 'capacitaciones';

    protected $fillable = ['empresa_id',
    'capacitaciones_tipo_id',
    'tema_id',
    'sede_id',
    'fecha_capacitacion',
    'hora_inicio',
    'hora_fin',
    'expositor_id',
    'cargo_expositor_id',
    'registrador_id',
    'cargo_registrador_id',
    'fecha_registro',
    'activo',
    'status_id',
    'modalidad_id',
    'cantidad_de_sesiones',
    'expositor_externo',
    'nombre_expositor_externo',
    'synced'
    ];
	    
    public function empresa()
    {
        return $this->belongsTo(Empresa::class,'empresa_id','id');
    }
    
    public function tipo_capacitacion()
    {
        return $this->belongsTo(TipoDeCapacitacione::class,'capacitaciones_tipo_id','id');
    }
    
    public function tema()
    {
        return $this->belongsTo(Tema::class,'tema_id','id');
    }
    
    public function sede()
    {
        return $this->belongsTo(Sede::class,'sede_id','id');
    }

    public function expositor()
    {
        return $this->belongsTo(Personal::class,'expositor_id','id');
    }

    public function cargo_expositor()
    {
        return $this->belongsTo(Cargo::class,'cargo_expositor_id','id');
    }

    public function registrador()
    {
        return $this->belongsTo(Personal::class,'registrador_id','id');
    }

    public function cargo_registrador()
    {
        return $this->belongsTo(Cargo::class,'cargo_registrador_id','id');
    }    

    public function estado()
    {
        return $this->belongsTo(Status::class,'status_id','id');
    }

    public function modalidad()
    {
        return $this->belongsTo(Modalidade::class,'modalidad_id','id');
    }

    public function personal()
    {
        return $this->belongsToMany(Personal::class,'capacitacion_has_personal','capacitacion_id','personal_id')->withTimestamps();
    }

    public function capacitacion_has_personal()
    {
        return $this->hasMany(CapacitacionHasPersonal::class,'capacitacion_id','id');
    }

    public function areas()
    {
        return $this->belongsToMany(Area::class,'capacitacion_has_area','capacitacion_id','area_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class,'status_id','id');
    }

    // Define los atributos calculados
    protected $appends = ['cantidad_personas'];

    // Método para obtener la cantidad de personas asociadas a la capacitación
    public function getCantidadPersonasAttribute()
    {
        return $this->personal()->count();
    }

    public function sesiones()
    {
        return $this->hasMany(Sesione::class,'capacitacion_id','id');
    }
}


