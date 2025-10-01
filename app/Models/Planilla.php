<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Planilla extends Model
{
	use HasFactory;
    use SoftDeletes;
	
    public $timestamps = true;

    protected $table = 'planillas';

    protected $fillable = ['name','estado','idplanilla_nisira','empresa_id','sede_id'];
	
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = mb_strtoupper(trim($value));
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class,'empresa_id','id');
    }
    public function sede()
    {
        return $this->belongsTo(Sede::class,'sede_id','id');
    }
    // public function planilla_personal()
    // {
    //     return $this->hasMany(PlanillaPersonal::class,'planilla_id','id');
    // }
}
