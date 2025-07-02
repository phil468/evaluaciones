<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dominio extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = true;

    protected $table = 'dominios';

    protected $fillable = [
        'name',
        'grado_id',
        'nivel_jerarquico_id',
        'campania_id',
        'estado',
    ];

    public function grado()
    {
        return $this->belongsTo(Grado::class);
    }

    public function nivelJerarquico()
    {
        return $this->belongsTo(NivelJerarquico::class);
    }

    public function campania()
    {
        return $this->belongsTo(Campania::class);
    }

    public function secciones() {
        return $this->hasManyThrough(
            Seccione::class,
            Pregunta::class,
            'dominio_id', // Foreign key on Pregunta table
            'id', // Foreign key on Seccione table
            'id', // Local key on Dominio table
            'seccion_id' // Local key on Pregunta table
        );
    }

    public function campaniaHasCompetencias() {
        return $this->hasManyThrough(
            CampaniaHasCompetencia::class,
            Pregunta::class,
            'dominio_id', // Foreign key on Pregunta table
            'id', // Foreign key on CampaniaHasCompetencia table
            'id', // Local key on Dominio table
            'campania_has_competencia_id' // Local key on Pregunta table
        );
    }

    public function seccionesUnicas() {
        return $this->campaniaHasCompetencias()->competencia()->unique('id');
    }

    public function preguntas() {
        return $this->hasMany(Pregunta::class, 'dominio_id', 'id');
    }

}