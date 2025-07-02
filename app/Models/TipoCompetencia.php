<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoCompetencia extends Model
{
    use HasFactory;
    use SoftDeletes;
	
    public $timestamps = true;

    protected $table = 'tipo_competencias';

    protected $fillable = [
        'name',
        'medicion_id',
        'estado',
    ];

    public function tipoMedicion()
    {
        return $this->belongsTo(TipoMedicion::class, 'medicion_id');
    }
}