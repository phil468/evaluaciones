<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Area extends Model
{
	use HasFactory;
    use SoftDeletes;
	
    public $timestamps = true;

    protected $table = 'areas';

    protected $fillable = [
        'name',
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
    
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = mb_strtoupper(trim($value));
    }
}