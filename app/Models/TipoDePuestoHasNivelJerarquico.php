<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoDePuestoHasNivelJerarquico extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = true;

    protected $table = 'tipo_de_puesto_has_nivel_jerarquicos';

    protected $fillable = [
        'tipo_de_puesto_id',
        'nivel_jerarquico_id',
        'campania_id',
        'estado',
    ];

    public function tipoDePuesto()
    {
        return $this->belongsTo(TipoDePuesto::class, 'tipo_de_puesto_id', 'id');
    }

    public function nivelJerarquico()
    {
        return $this->belongsTo(NivelJerarquico::class, 'nivel_jerarquico_id', 'id');
    }

    public function dominio()
    {
        // Obtenemos el dominio manualmente 
        return $this->hasOneThrough(
            Dominio::class,
            NivelJerarquico::class,
            'id', // Clave foránea en nivel_jerarquico
            'nivel_jerarquico_id', // Clave foránea en dominios
            'nivel_jerarquico_id', // Clave local en tipo_de_puesto_has_nivel_jerarquicos
            'id' // Clave local en nivel_jerarquicos
        )->where('dominios.campania_id', '=', function($query) {
            $query->select('campania_id')
                ->from($this->getTable())
                ->whereColumn($this->getTable().'.id', '=', $this->getQualifiedKeyName())
                ->limit(1);
        });
    }

    public function campania()
    {
        return $this->belongsTo(Campania::class);
    }
}