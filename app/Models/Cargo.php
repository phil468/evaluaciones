<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Cargo extends Model
{
	use HasFactory;
    use SoftDeletes;
	
    public $timestamps = true;

    protected $table = 'cargos';

    protected $fillable = [
        'name',
        'tipo_de_puesto_id',
        'estado',
        'empresa_id',
        'idcargo_nisira',
        'fechacreacion_nisira'
    ];
	
    public function setNameAttribute($value)
    {
        $v = trim((string)$value);
        $v = preg_replace('/\s+/', ' ', $v ?? '');
        $v = Str::of($v)->ascii();     // quita tildes
        $this->attributes['name'] = mb_strtoupper($v);
    }

    public function getNameAttribute($value)
    {
        $v = trim((string)$value);
        $v = preg_replace('/\s+/', ' ', $v ?? '');
        $v = Str::of($v)->ascii();     // quita tildes
        return mb_strtoupper($v);        
    }

    public function tipoDePuesto()
    {
        return $this->belongsTo(TipoDePuesto::class, 'tipo_de_puesto_id');
    }
    public function personals()
    {
        return $this->hasMany(Personal::class, 'cargo_id');
    }

    public function cargoSuperior()
    {
        return $this->belongsTo(Cargo::class, 'reporta_a');
    }

    public function cargosDependientes()
    {
        return $this->hasMany(Cargo::class, 'reporta_a');
    }

}
