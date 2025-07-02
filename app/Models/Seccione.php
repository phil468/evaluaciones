<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seccione extends Model
{
	use HasFactory;
    use SoftDeletes;
	
    public $timestamps = true;

    protected $table = 'secciones';

    protected $fillable = ['name', 'estado', 'color', 'descripcion', 'tipo_competencia_id'];

    public function tipoCompetencia()
    {
        return $this->belongsTo(TipoCompetencia::class);
    }
}
