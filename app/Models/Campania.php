<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campania extends Model
{
	use HasFactory;
    use SoftDeletes;
	
    public $timestamps = true;

    protected $table = 'campanias';

    protected $fillable = ['name','estado','relacionado_anterior_id', 'es_campania_actual'];
    protected $appends = ['anio_mostrar'];
    
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = mb_strtoupper(trim($value));
    }

    public function evaluaciones()
    {
        return $this->hasMany(Evaluacione::class, 'campania_id', 'id');
    }

    public function planesConfiguracion()
    {
        return $this->hasMany(PlanesConfiguracion::class, 'campania_id', 'id');
    }

    public function campaniaHasCompetencias()
    {
        return $this->hasMany(CampaniaHasCompetencia::class, 'campania_id', 'id');
    }

    public function dominios()
    {
        return $this->hasMany(Dominio::class, 'campania_id', 'id');
    }
    
    public function competencias()
    {
        return $this->belongsToMany(Competencia::class, 'campania_has_competencias', 'campania_id', 'competencia_id')
                    ->withPivot('id', 'estado')
                    ->withTimestamps();
    }

    public function relacionadoAnterior()
    {
        return $this->belongsTo(Campania::class, 'relacionado_anterior_id');
    }
    
    // Campo calculado: primer año antes del guion en el nombre (ej. "2024-2025" -> "2024")
    public function getAnioMostrarAttribute()
    {
        $name = (string)($this->name ?? '');
        $parts = explode('-', $name);
        $anio = trim($parts[0] ?? '');
        return $anio !== '' ? $anio : $name;
    }
}
