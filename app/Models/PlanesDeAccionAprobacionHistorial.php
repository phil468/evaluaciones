<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanesDeAccionAprobacionHistorial extends Model {
    protected $table = 'planes_de_accion_aprobacion_historial';
    protected $fillable = [
        'planes_de_accion_id','user_id','estado_anterior','estado_nuevo','observacion'
    ];
    public function plan(){
        return $this->belongsTo(PlanesDeAccion::class,'planes_de_accion_id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}