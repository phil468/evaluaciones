<?php 

namespace App\Models;

use Attribute;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Activo extends Model implements Auditable
{
	use HasFactory;
    use SoftDeletes;
    use AuditableTrait;
	
    public $timestamps = true;

    protected $table = 'activos';

    protected $fillable = [
        'estado',//
        'activo_tipo_id',//
        'brand_id',//
        'modelo_id',//
        'serial_number',//
        'patrimonial_code',
        'status_id',//
        'performance_id',//
        'imei1',
        'imei2',
        'orden_compra',
        'fecha_compra',
        'year',
        'personal_id',
        'fecha_asignacion',
        'fecha_de_vigencia',
        'vigencia_id',
        'fecha_devolucion',
        'fecha_baja',
        'baja_motivo_id',
        'created_by',
        'updated_by',
        'deleted_by',
        'observations',
        'asignacion_has_activo_id',
        'area_id',
        'regularizacion',
        'observaciones_no_asignacion',
        'ct_id',
        'notebook_id',
        'mac',
        'mac_address',
    ];	

    protected $appends = ['descripcion'];

    public function activo_tipo()
    {
        return $this->hasOne('App\Models\ActivoTipo', 'id', 'activo_tipo_id');
    }
    
    public function asignacion_has_activo()
    {
        return $this->hasOne('App\Models\AsignacionHasActivo', 'id', 'asignacion_has_activo_id');
    }

    public function area()
    {
        return $this->hasOne('App\Models\Area', 'id', 'area_id');
    }
    
    public function brand()
    {
        return $this->hasOne('App\Models\Brand', 'id', 'brand_id');
    }

    public function ct()
    {
        return $this->hasOne('App\Models\Activo', 'id', 'ct_id')->withDefault();
    }

    public function modelo()
    {
        return $this->hasOne('App\Models\Modelo', 'id', 'modelo_id');
    }
    
    public function status()
    {
        return $this->hasOne('App\Models\Status', 'id', 'status_id');
    }
    
    public function performance()
    {
        return $this->hasOne('App\Models\Performance', 'id', 'performance_id');
    }
    
    public function personal()
    {
        return $this->hasOne('App\Models\Personal', 'id', 'personal_id');
    }

    public function vigencia()
    {
        return $this->hasOne('App\Models\Vigencium', 'id', 'vigencia_id');
    }
    
    public function baja_motivo()
    {
        return $this->hasOne('App\Models\BajaMotivo', 'id', 'baja_motivo_id');
    }

    public function descripcion() {
        $descripcion = '';
        $descripcion = 
        // '<b>'.$this -> activo_tipo->name.'</b>' .
        (isset($this -> activo_tipo->name) ? '<b>'.$this ->activo_tipo->name.'</b> ' : '').
        (isset($this -> brand->name) ? ', <b>Marca</b>: '.$this -> brand->name : '').
        (isset($this -> modelo->name) ?', <b>Modelo</b>: '.$this -> modelo->name : '') .
        (isset($this -> serial_number) ? ', <b>S/N</b>: '.$this -> serial_number : '') .
        (isset($this -> imei1) ? ', <b>IMEI1</b>: '.$this -> imei1 : '') .
        (isset($this -> imei2) ? ', <b>IMEI2</b>: '.$this -> imei2 : '') ;

        return $descripcion;
    }

    protected function getDescripcionAttribute()
    {
        $descripcion = '';
        $descripcion = 
        // '<b>'.$this -> activo_tipo->name ?? ''.'</b>' .
        (isset($this -> activo_tipo->name) ? '<b>'.$this ->activo_tipo->name.'</b> ' : '').
        (isset($this -> brand->name) ? ', <b>Marca</b>: '.$this -> brand->name : '').
        (isset($this -> modelo->name) ?', <b>Modelo</b>: '.$this -> modelo->name : '') .
        (isset($this -> serial_number) ? ', <b>S/N</b>: '.$this -> serial_number : '') .
        (isset($this -> imei1) ? ', <b>IMEI1</b>: '.$this -> imei1 : '') .
        (isset($this -> imei2) ? ', <b>IMEI2</b>: '.$this -> imei2 : '') ;

        return $descripcion;
    }

    public function setSerialNumberAttribute($value)
    {
        $this->attributes['serial_number'] = strtoupper(trim($value));
    }

    public function setImei1Attribute($value)
    {
        $this->attributes['imei1'] = strtoupper(trim($value));
    }

    public function setImei2Attribute($value)
    {
        $this->attributes['imei2'] = strtoupper(trim($value));
    }

    public function setOrdenCompraAttribute($value)
    {
        $this->attributes['orden_compra'] = strtoupper(trim($value));
    }
    
    public function setObservationsAttribute($value)
    {
        $this->attributes['observations'] = trim($value);
    }

    public function setObservacionesNoAsignacionAttribute($value)
    {
        $this->attributes['observaciones_no_asignacion'] = trim($value);
    }

}
