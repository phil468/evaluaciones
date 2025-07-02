<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CampaniaHasEvaluado extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'campania_has_evaluados';

    protected $fillable = [
        'personal_id',
        'campania_id',
        'area_id',
        'subgerencia_id',
        'gerencia_id',
        'puesto_id',
        'tipo_de_puesto_campania_id',
        // 'dominio_id',
        'habilitado_para_evaluacion_de_competencias',
        'fecha_baja_de_evaluacion_de_competencias',
        'motivo_baja_de_evaluacion_de_competencias',
        'habilitado_para_evaluacion_por_objetivos',
        'fecha_baja_de_evaluacion_por_objetivos',
        'motivo_baja_de_evaluacion_por_objetivos',
        'cesado',
        'estado',
        'superior_personal_id',
        'puntaje_de_evaluacion_de_competencias',
        'evaluacion_de_competencias_completada',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'fecha_baja_de_evaluacion_de_competencias',
        'fecha_baja_de_evaluacion_por_objetivos',
    ];

    protected $casts = [
        'habilitado_para_evaluacion_de_competencias' => 'boolean',
        'habilitado_para_evaluacion_por_objetivos' => 'boolean',
        'cesado' => 'boolean',
        'estado' => 'boolean'
    ];

    // protected $appends = ['paresFormateados'];

    // protected $atributtes = [
    //     'campania_id' => null,
    // ];
    // Relaciones

    /**
     * Obtiene el personal relacionado
     */
    public function personal()
    {
        return $this->belongsTo(Personal::class, 'personal_id');
    }

    /**
     * Obtiene la campaña relacionada
     */
    public function campania()
    {
        return $this->belongsTo(Campania::class, 'campania_id');
    }

    /**
     * Obtiene el área relacionada
     */
    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    /**
     * Obtiene el puesto relacionado
     */
    public function puesto()
    {
        return $this->belongsTo(Cargo::class, 'puesto_id');
    }

    /**
     * Obtiene el tipo de puesto con nivel jerárquico
     */
    public function tipoPuestoHasNivelJerarquico()
    {
        return $this->belongsTo(TipoDePuestoHasNivelJerarquico::class, 'tipo_de_puesto_campania_id');
    }

    /**
     * Obtiene el dominio relacionado
     */
    // public function dominio()
    // {
    //     // se llega al dominio a través del tipo de puesto con nivel jerárquico
    //     return $this->tipoPuestoHasNivelJerarquico ? $this->tipoPuestoHasNivelJerarquico->dominio : null;
    // }

    /**
     * Obtiene el superior incluyendo el scope campain
     */
    public function superior()
    {
        return $this->belongsTo(Personal::class, 'superior_personal_id');
    }

    /**
     * Obtiene los subordinados de este personal en la campaña
     */
    public function subordinados()
    {
        return $this->hasMany(CampaniaHasEvaluado::class, 'superior_personal_id', 'personal_id');
    }

    public function paresMismoSuperior()
    {
        return 
        $this->hasMany(CampaniaHasEvaluado::class, 'superior_personal_id', 'superior_personal_id');
    }

    /**
     * Obtiene las evaluaciones donde este personal es evaluador
     */
    public function evaluador()
    {
        return $this->hasMany(EvaluadorHasEvaluado::class, 'evaluador_id', 'personal_id')
            ->where('evaluacion.campania_id', $this->campania_id);
    }

    /**
     * Obtiene las evaluaciones donde este personal es evaluado
     */
    public function evaluado()
    {
        return $this->hasMany(EvaluadorHasEvaluado::class, 'evaluado_id', 'personal_id')
            ->where('evaluacion.campania_id', $this->campania_id);
    }

    // Scopes para filtros comunes

    /**
     * Filtrar por campaña activa
     */
    public function scopeActiveCampaign($query)
    {
        return $query->whereHas('campania', function($q) {
            $q->where('status', true);
        });
    }

    /**
     * Filtrar por campaña
     */
    public function scopeCampania($query, $campaniaId)
    {
        return $query->where('campania_id', $campaniaId);
    }

    /**
     * Filtrar por personal habilitado para evaluación de competencias
     */
    public function scopeHabilitadoCompetencias($query)
    {
        return $query->where('habilitado_para_evaluacion_de_competencias', true);
    }

    /**
     * Filtrar por personal habilitado para evaluación por objetivos
     */
    public function scopeHabilitadoObjetivos($query)
    {
        return $query->where('habilitado_para_evaluacion_por_objetivos', true);
    }

    /**
     * Filtrar por personal activo (no cesado)
     */
    public function scopeActivo($query)
    {
        return $query->where('cesado', false)->where('estado', true);
    }

    // Métodos específicos

    /**
     * Habilitar para evaluación de competencias
     */
    public function habilitarCompetencias()
    {
        $this->habilitado_para_evaluacion_de_competencias = true;
        $this->fecha_baja_de_evaluacion_de_competencias = null;
        $this->motivo_baja_de_evaluacion_de_competencias = null;
        $this->save();
    }

    /**
     * Deshabilitar para evaluación de competencias
     */
    public function deshabilitarCompetencias($motivo = null)
    {
        $this->habilitado_para_evaluacion_de_competencias = false;
        $this->fecha_baja_de_evaluacion_de_competencias = now();
        $this->motivo_baja_de_evaluacion_de_competencias = $motivo;
        $this->save();
    }

    /**
     * Habilitar para evaluación por objetivos
     */
    public function habilitarObjetivos()
    {
        $this->habilitado_para_evaluacion_por_objetivos = true;
        $this->fecha_baja_de_evaluacion_por_objetivos = null;
        $this->motivo_baja_de_evaluacion_por_objetivos = null;
        $this->save();
    }

    /**
     * Deshabilitar para evaluación por objetivos
     */
    public function deshabilitarObjetivos($motivo = null)
    {
        $this->habilitado_para_evaluacion_por_objetivos = false;
        $this->fecha_baja_de_evaluacion_por_objetivos = now();
        $this->motivo_baja_de_evaluacion_por_objetivos = $motivo;
        $this->save();
    }

    /**
     * Marcar como cesado
     */
    public function cesar()
    {
        $this->cesado = true;
        $this->save();
    }

    /**
     * Obtener el nivel jerárquico (si está disponible)
     */
    public function getNivelJerarquicoAttribute()
    {
        return $this->tipoPuestoHasNivelJerarquico ? $this->tipoPuestoHasNivelJerarquico->nivelJerarquico : null;
    }

    /**
     * Obtener el grado de la evaluación (si está disponible)
     */
    public function getGradoAttribute()
    {
        return $this->dominio ? $this->dominio->grado : null;
    }
}
