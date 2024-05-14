<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Objetivo extends Model implements Auditable
{
	use HasFactory;
    use SoftDeletes;	
    use AuditableTrait;

    public $timestamps = true;

    protected $table = 'objetivos';

    protected $fillable = ['resultado','evaluado_id','evaluador_id','tipo_objetivo_id','descripcion','evidencia','evaluador_has_evaluado_id'];
	
    public function tipo_objetivo()
    {
        return $this->hasOne('App\Models\TiposDeObjetivo', 'id', 'tipo_objetivo_id');
    }

    public function evaluado()
    {
        return $this->belongsTo(Personal::class, 'evaluado_id','id');
    }

    public function evaluador()
    {
        return $this->belongsTo(Personal::class, 'evaluador_id','id');
    }

    // public function evaluador_has_evaluado()
    // {
    //     return $this->belongsTo(EvaluadorHasEvaluadoObjetivo::class, 'evaluador_has_evaluado_id','id');
    // }

    // public function getEvidenciaAttribute()
    // {
    //     return $this->evidencia??'';
    // }

    // public function getResultadoAttribute()
    // {
    //     return $this->resultado??'';
    // }

}
