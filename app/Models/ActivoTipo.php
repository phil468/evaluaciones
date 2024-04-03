<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActivoTipo extends Model
{
	use HasFactory;
    use SoftDeletes;
	
    public $timestamps = true;

    protected $table = 'activo_tipos';

    protected $fillable = ['name','estado'];
	
    public function activos()
    {
        return $this->hasMany('App\Models\Activo', 'activo_tipo_id', 'id');
    }

    public function accesorios(): BelongsToMany
    {
        return $this->belongsToMany(
            Accesorio::class,
            'accesorio_has_activo_tipos',
            'activo_tipo_id',
            'accesorio_id'
        );
    }
    
    public function campos(): BelongsToMany
    {
        return $this->belongsToMany(
            CamposTipoActivo::class,
            'activo_tipo_has_campo',
            'activo_tipo_id',
            'campo_id'
        );
    }

    public function accesorios_descripcion() {
        $descripcion ='';
        foreach ($this->accesorios as $index=>$item) {
            if (count($this->accesorios)-1 > $index) {
                $descripcion .= $item->name.', ' ?? '';
            } else {
                $descripcion .= $item->name ?? '';
            }
        }
        return $descripcion = '';
    }
    
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = mb_strtoupper(trim($value));
    }
}
