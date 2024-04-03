<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Devolucion extends Model
{
    use HasFactory;
    use SoftDeletes;
	
    public $timestamps = true;

    protected $table = 'devoluciones';

    protected $fillable = [
            'personal_id',
            'empresa_id',
            'gerencia_id',
            'sede_id',
            'area_id',
            'cargo_id',
            'fecha',
            'responsable_id',
            'responsable_area_id',
            'responsable_cargo_id',
            'created_by',
            'updated_by',
            'deleted_by',
            'pdf'
        ];
    
        public function personal()
        {
            return $this->hasOne('App\Models\Personal', 'id', 'personal_id');
        }
	
        public function empresa()
        {
            return $this->hasOne('App\Models\Empresa', 'id', 'empresa_id');
        }
        
        public function gerencia()
        {
            return $this->hasOne('App\Models\Gerencia', 'id', 'gerencia_id');
        }

        public function sede()
        {
            return $this->hasOne('App\Models\Sede', 'id', 'sede_id');
        }
        
        public function area()
        {
            return $this->hasOne('App\Models\Area', 'id', 'area_id');
        }
        
        public function cargo()
        {
            return $this->hasOne('App\Models\Cargo', 'id', 'cargo_id');
        }
        
        public function responsable()
        {
            return $this->hasOne('App\Models\Personal', 'id', 'responsable_id');
        }
        
        public function responsable_area()
        {
            return $this->hasOne('App\Models\Area', 'id', 'responsable_area_id');
        }
        
        public function responsable_cargo()
        {
            return $this->hasOne('App\Models\Cargo', 'id', 'responsable_cargo_id');
        }

        public function activos()
        {
            return $this->belongsToMany(
                Activo::class,
                'devolucion_has_activos',
                'devolucion_id',
                'activo_id'
            );
        }
        
        public function activos_devueltos(): HasMany
        {
            return $this->hasMany(
                DevolucionHasActivo::class,
                'devolucion_id',
                'id'
            );
        }    

        public function activos_id()
        {
            $ids = [];
            foreach ($this->activos as $activo) {
                array_push($ids, $activo->id);
            }     

            return $ids;
        }
}
