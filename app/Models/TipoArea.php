<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoArea extends Model
{
    use HasFactory;
    // use SoftDeletes;
	
    public $timestamps = true;

    protected $table = 'tipo_areas';

    protected $fillable = [
        'name',
        'estado',
    ];
}