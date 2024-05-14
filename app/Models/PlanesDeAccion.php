<?php 

namespace App\Models;

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

    protected $fillable = ['encargado_id','empleado_id','competencia_id','tipo_de_proceso_id','proceso_id','fecha_de_revision','estado_id','gerencia_id','subgerencia_id','area_id','avance','name','nombre_de_proceso_id'];

    public function competencia()
    {
        return $this->belongsTo('App\Models\Competencia', 'competencia_id','id');
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

	
}
