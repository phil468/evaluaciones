<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComiteHasPersona extends Model
{
    use HasFactory;

    protected $table = 'comite_has_personas';

    protected $fillable = [
        'comite_calibracion_id',
        'personal_id',
    ];

    public function comite()
    {
        return $this->belongsTo(ComiteCalibracion::class, 'comite_calibracion_id');
    }

    public function personal()
    {
        return $this->belongsTo(Personal::class, 'personal_id');
    }
}