<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Area extends Model
{
	use HasFactory;
    use SoftDeletes;
	
    public $timestamps = true;

    protected $table = 'areas';

    protected $fillable = [
        'name',
        'tipo_id',               // NUEVO
        'area_superior_id',   // NUEVO
        'estado',
        'idempresa_nisira',                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     
        'idarea_nisira',
        'fechacreacion_nisira',
        'subgerencia_id',
        'gerencia_id',
        'idccosto_nisira',
        'empresa_id',
        'centro_costo'
    ];
	

    // idccosto_nisira
    // empresa_id
    // name
    // centro_costo
    // estado

    protected $casts = [
        'estado' => 'boolean',
    ];

    // Relaciones jerárquicas nuevas
    public function superior()
    {
        return $this->belongsTo(Area::class, 'area_superior_id');
    }

    public function hijas()
    {
        return $this->hasMany(Area::class, 'area_superior_id');
    }

    public function tipo()
    {
        return $this->belongsTo(TipoArea::class, 'tipo_id');
    }

    public function gerencia()
    {
        return $this->belongsTo(Gerencia::class, 'gerencia_id', 'id');
    }

    public function subgerencia()
    {
        return $this->belongsTo(Subgerencia::class, 'subgerencia_id', 'id');
    }
    
    public function activos()
    {
        return $this->hasMany('App\Models\Activo', 'area_id', 'id');
    }
    
    // Nombre: trim, sin tildes, mayúsculas y espacios internos normalizados
    public function setNameAttribute($value)
    {
        $v = trim((string)$value);
        $v = preg_replace('/\s+/', ' ', $v ?? '');
        $v = Str::of($v)->ascii();     // quita tildes
        $this->attributes['name'] = mb_strtoupper($v);
    }

    public function getNameAttribute($value)
    {
        $v = trim((string)$value);
        $v = preg_replace('/\s+/', ' ', $v ?? '');
        $v = Str::of($v)->ascii();     // quita tildes
        return mb_strtoupper($v);        
    }
}