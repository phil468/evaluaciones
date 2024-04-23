<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recordatorio extends Model
{
    use HasFactory;

    protected $table = 'recordatorios';

    protected $fillable = [
        'fecha',
        'id_evaluacion',
    ];

    protected $dates = ['fecha'];
    
    public function evaluacion()
    {
        return $this->belongsTo(Evaluacione::class, 'id_evaluacion');
    }
}