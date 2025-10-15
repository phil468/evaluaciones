<?php 

namespace App\Models;

use App\Http\Livewire\EncargadosPlanes;
use App\Http\Livewire\Secciones;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class PlanesDeAccion extends Model implements Auditable
{
	use HasFactory;
    use SoftDeletes;
    use AuditableTrait;

    public $timestamps = true;

    protected $table = 'planes_de_accion';

    protected $fillable = [
        'encargado_id',
        'empleado_id',
        'competencia_id',
        'tipo_de_proceso_id',
        'proceso_id',
        'fecha_de_revision',
        'estado_id',
        'gerencia_id',
        'subgerencia_id',
        'area_id',
        'avance',
        'name',
        'nombre_de_proceso_id',
        'encargados_planes_de_accion_id',
        'objetivo','alcanzado','porcentaje_cumplimiento','estado_cumplimiento',
        'tipo_objetivo','estado_aprobacion','observacion_validacion',
        'enviado_para_validacion_at','aprobado_revisado_at','ultima_notificacion_aprobacion_at',
        // 'planes_de_accion_configuracion_id',
    ];

    
public function aprobacionesHistorial() {
    return $this->hasMany(PlanesDeAccionAprobacionHistorial::class,'planes_de_accion_id');
}

public function recalcularCumplimiento() {
    if($this->objetivo && $this->objetivo > 0 && $this->alcanzado !== null){
        $pct = ($this->alcanzado / $this->objetivo) * 100;
        $this->porcentaje_cumplimiento = round($pct,2);
        // Rangos visualizados en tu maqueta
        if($pct < 50) $this->estado_cumplimiento = 'No cumplimiento';
        elseif($pct < 70) $this->estado_cumplimiento = 'Bajo cumplimiento';
        elseif($pct < 90) $this->estado_cumplimiento = 'Medio cumplimiento';
        else $this->estado_cumplimiento = 'Cumplimiento esperado';
    } else {
        $this->porcentaje_cumplimiento = null;
        $this->estado_cumplimiento = null;
    }
}

    // public function competencia()
    // {
    //     return $this->belongsTo('App\Models\Competencia', 'competencia_id','id');
    // }

    
    public function competencia()
    {
        // Ahora apunta a CampaniaHasCompetencia en lugar de Secciones
        return $this->belongsTo(CampaniaHasCompetencia::class, 'competencia_id');
    }

    // Agregar relación opcional a la competencia original
    public function competenciaOriginal()
    {
        return $this->hasOneThrough(
            Secciones::class,
            CampaniaHasCompetencia::class,
            'id', // Foreign key en campania_has_competencias
            'id', // Foreign key en secciones
            'competencia_id', // Local key en planes_de_accion
            'competencia_id' // Local key en campania_has_competencias
        );
    }
    public function estado()
    {
        return $this->belongsTo('App\Models\EstadosDePlanDeAccion', 'estado_id','id');
    }
    public function gerencia()
    {
        return $this->belongsTo('App\Models\Gerencia', 'gerencia_id','id');
        //return $this->empleado->gerencia()??null;
    }
    public function subgerencia()
    {
        return $this->belongsTo('App\Models\Subgerencia', 'subgerencia_id','id');
        //return $this->empleado->subgerencia()??null;
    }
    public function area()
    {
        return $this->belongsTo('App\Models\Area', 'area_id','id');
        //return $this->empleado->area()??null;
    }
    public function empleado()
    {
        return $this->belongsTo(Personal::class, 'empleado_id','id');
    }
    public function tipo_de_proceso()
    {
        return $this->proceso->tipo_de_proceso()??null;
    }
    public function proceso()
    {
        return $this->belongsTo(TipoDeEvaluacione::class, 'proceso_id', 'id');
    }
    public function encargado()
    {
        return $this->belongsTo('App\Models\Personal','encargado_id','id');
    }

    public function nombre_de_proceso()
    {
        return $this->belongsTo(Evaluacione::class, 'nombre_de_proceso','id');
    }

    public function encargados_planes_de_accion()
    {
        return $this->belongsTo(EncargadosPlanesDeAccion::class,'encargados_planes_de_accion_id','id');
    }
	
    public function evidencias()
    {
        return $this->hasMany(PlanesDeMejoraHasEvidencia::class, 'planes_de_accion_id','id');
    }

    // Acceso a la campaña a través de las relaciones
    public function getCampaniaAttribute()
    {
        // Compatible con PHP < 8 y usa el nombre correcto de la relación
        return data_get($this, 'encargados_planes_de_accion.plan_de_mejora.campania');
    }

    // Métodos de estado
    public function puedeEditarse()
    {
        return in_array($this->estado_aprobacion, ['borrador', 'no_validado']);
    }

    public function estaEnFase1()
    {
        return (bool) data_get($this, 'encargados_planes_de_accion.plan_de_mejora.primera_fase_activa', false);
    }

    public function estaEnFase2()
    {
        return (bool) data_get($this, 'encargados_planes_de_accion.plan_de_mejora.segunda_fase_activa', false);
    }

    public function puedeValidarse()
    {
        $campania = $this->campania;
        return $this->estado_aprobacion === 'pendiente' && 
               $campania && 
               $campania->es_campania_actual && 
               $this->estaEnFase1();
    }

    // Cálculo automático de porcentaje
    public function calcularPorcentajeCumplimiento()
    {
        if (!$this->objetivo || !$this->alcanzado) {
            return null;
        }
        
        return round(($this->alcanzado / $this->objetivo) * 100, 2);
    }

    // Estado de cumplimiento según porcentaje
    public function determinarEstadoCumplimiento()
    {
        $porcentaje = $this->porcentaje_cumplimiento;
        
        if ($porcentaje === null) return null;
        
        if ($porcentaje >= 100) return 'Superado';
        if ($porcentaje >= 90) return 'Cumplido';
        if ($porcentaje >= 70) return 'Parcialmente Cumplido';
        return 'No Cumplido';
    }

    // Boot method para cálculos automáticos
    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($plan) {
            // Calcular porcentaje automáticamente en fase 2
            if ($plan->estaEnFase2() && $plan->objetivo && $plan->alcanzado) {
                $plan->porcentaje_cumplimiento = $plan->calcularPorcentajeCumplimiento();
                $plan->estado_cumplimiento = $plan->determinarEstadoCumplimiento();
            }
        });
    }
}
