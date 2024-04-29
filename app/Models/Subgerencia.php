<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subgerencia extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'subgerencias';

    protected $fillable = [
        'name',
        'estado',
        'gerencia_id',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function gerencia()
    {
        return $this->belongsTo(Gerencia::class, 'gerencia_id', 'id');
    }

}
