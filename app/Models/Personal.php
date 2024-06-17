<?php 

namespace App\Models;

use App\Observers\PersonalObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Personal extends Model
{
	use HasFactory;
    use SoftDeletes;
	
    public $timestamps = true;

    protected $table = 'personal';

    protected $fillable = [
        'dni',
        'name',
        'nombres',
        'apellido_paterno',
        'apellido_materno',
        'empresa_id',
        'gerencia_id',
        'subgerencia_id',
        'sede_id',
        'area_id',
        'cargo_id',
        'correo_empresa',
        'celular_empresa',
        'correo_personal',
        'telefono_personal',
        'celular_personal',
        'foto',
        'estado',
        'genero',
        'fecha_ingreso',
        'firma',
        'tipo_de_trabajador_id',
        'tipo_de_personal_id',
        'planilla_id',
        'cesado',
        'fecha_cese',
        'importado',
        'reporta_a'
    ];
	
    protected $dates = ['deleted_at','fecha_ingreso','fecha_cese'];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id', 'id');
         //) ('App\Models\Empresa', 'id', 'empresa_id');
    }
    
    public function gerencia()
    {
        return $this->belongsTo(Gerencia::class, 'gerencia_id', 'id');
    }

    public function subgerencia()
    {
        return $this->belongsTo(Subgerencia::class, 'subgerencia_id', 'id');
    }

    public function sede()
    {
        return $this->belongsTo(Sede::class, 'sede_id', 'id');
        // hasOne('App\Models\Sede', 'id', 'sede_id');
    }
    
    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }
    
    public function cargo()
    {
        return $this->hasOne('App\Models\Cargo', 'id', 'cargo_id');
    }

    public function planilla()
    {
        return $this->hasOne('App\Models\Planilla', 'id', 'planilla_id');
    }
    public function tipo_trabajador()
    {
        return $this->hasOne('App\Models\TipoDeTrabajador', 'id', 'tipo_de_trabajador_id');
    }
    public function tipo_personal()
    {
        return $this->hasOne('App\Models\TipoDePersonal', 'id', 'tipo_de_personal_id');
    }

    public function evaluaciones()
    {
        return $this->hasMany('App\Models\EvaluadorHasEvaluado', 'evaluado_id', 'id');
    }
    
    public function has_evaluacion($tipo_de_evaluacion_id)
    {
        return $this->evaluaciones()->where('tipo_de_evaluacion_id', $tipo_de_evaluacion_id)->exists();
    }

    public function evaluaciones_por_tipo_de_evaluacion($tipo_de_evaluacion) 
    {
        return $this->evaluaciones()->where('tipo_de_evaluacion_id', $tipo_de_evaluacion)->get();
    }

    public function user()
    {
        return $this->hasOne('App\Models\User', 'personal_id', 'id');
    }

    public function reporta_a()
    {
        return $this->hasOne('App\Models\Personal', 'id', 'reporta_a');
    }
    
    protected static function boot()
    {
        parent::boot();
        Personal::observe(PersonalObserver::class);        
    } 

    public function scopeActive($query)
    {
        return $query->where('estado', 1);
    }
    
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = mb_strtoupper(trim($value));
    }
    
}
