<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campania extends Model
{
	use HasFactory;
    use SoftDeletes;
	
    public $timestamps = true;

    protected $table = 'campanias';

    protected $fillable = ['name','estado'];	
    
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = mb_strtoupper(trim($value));
    }

    public function evaluaciones()
    {
        return $this->hasMany(Evaluacione::class, 'campania_id', 'id');
    }

    public function planesConfiguracion()
    {
        return $this->hasMany(PlanesConfiguracion::class, 'campania_id', 'id');
    }
}
