<?php
// app/Models/Peso.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Peso extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tipo_relacion_jerarquica_id',
        'peso',
        'grado_id',
        'campania_id'
    ];

    // Relaciones
    public function tipoRelacionJerarquica()
    {
        return $this->belongsTo(TipoRelacionJerarquica::class);
    }

    public function grado()
    {
        return $this->belongsTo(Grado::class);
    }

    public function campania()
    {
        return $this->belongsTo(Campania::class);
    }
}