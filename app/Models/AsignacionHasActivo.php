<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AsignacionHasActivo extends Model
{
	use HasFactory;
    use SoftDeletes;
	
    public $timestamps = true;

    protected $table = 'asignacion_has_activos';

    protected $fillable = ['activo_id',
        'asignacion_id',
        'accesorios_entregados',
        'accesorios_devueltos',
        'performance_id',
        'vigencia_id',
        'fecha_de_vigencia',
        'fecha_de_asignacion',
        'regularizacion',
        'devuelto',
        'fecha_de_devolucion',
        'observaciones'
    ];
	
    public function accesorios(): BelongsToMany
    {
        return $this->belongsToMany(
            Accesorio::class,
            'asignacion_activo_has_accesorios',
            'asignacion_has_activo_id',
            'accesorio_id'
        );
    }
       
    public function activo()
    {
        return $this->hasOne('App\Models\Activo', 'id', 'activo_id');
    }

    public function performance()
    {
        return $this->hasOne('App\Models\Performance', 'id', 'performance_id');
    }
    
    public function vigencia()
    {
        return $this->hasOne('App\Models\Vigencium', 'id', 'vigencia_id');
    }
    
    public function asignacion()
    {
        return $this->hasOne('App\Models\Asignacione', 'id', 'asignacion_id');
    }
}
