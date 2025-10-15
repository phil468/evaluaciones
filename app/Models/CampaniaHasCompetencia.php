<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CampaniaHasCompetencia extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'competencia_id',
        'campania_id',
        'relacionado_anterior_id',
        'estado',
        'tipo_competencia_id',
        'tipo_medicion_id',
        'color',
    ];

    public function competencia()
    {
        return $this->belongsTo(Seccione::class, 'competencia_id');
    }

    public function campania()
    {
        return $this->belongsTo(Campania::class, 'campania_id');
    }

    public function tipoCompetencia()
    {
        return $this->belongsTo(TipoCompetencia::class, 'tipo_competencia_id');
    }

    public function tipoMedicion()
    {
        return $this->belongsTo(TipoMedicion::class, 'tipo_medicion_id');
    }

    public function relacionadoAnterior()
    {
        return $this->belongsTo(CampaniaHasCompetencia::class, 'relacionado_anterior_id');
    }

    public function dominioHasPreguntas()
    {
        return $this->hasMany(DominioHasPregunta::class, 'campania_has_competencia_id');
    }
    
    // Relación con la competencia original (si existe)
    public function competenciaOriginal()
    {
        return $this->belongsTo(Competencia::class, 'competencia_id');
    }
    
    // Accessor para obtener el nombre
    public function getNameAttribute()
    {
        return $this->nombre ?? $this->competenciaOriginal->name ?? 'Sin nombre';
    }
}
