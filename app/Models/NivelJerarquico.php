<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NivelJerarquico extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    public $timestamps = true;

    protected $table = 'nivel_jerarquicos';

    protected $fillable = [
        'name',
        'estado',
    ];    
}