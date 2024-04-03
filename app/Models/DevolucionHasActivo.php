<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class DevolucionHasActivo extends Model
{
    use HasFactory;
    use SoftDeletes;
	
    public $timestamps = true;

    protected $table = 'devolucion_has_activos';

    protected $fillable = [
        'activo_id',
        'devolucion_id',
        'performance_id',
        'observaciones',
        'asignacion_has_activo_id'
    ];
        
    public function accesorios(): BelongsToMany
    {
        return $this->belongsToMany(
            Accesorio::class,
            'devolucion_has_activo_has_accesorios',
            'devolucion_has_activo_id',
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
}
