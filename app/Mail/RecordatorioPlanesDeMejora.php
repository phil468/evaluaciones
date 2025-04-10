<?php

namespace App\Mail;

use App\Models\PlanesConfiguracion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RecordatorioPlanesDeMejora extends Mailable
{
    use Queueable, SerializesModels;

    // public $evaluador;
    // public $lista_de_correos_de_evaluadores;
    public $name_evaluador;
    public $primera_fase_activa;
    public $segunda_fase_activa;

    public function __construct(
        $name_evaluador,
        $primera_fase_activa,
        $segunda_fase_activa)
    {
        $this->name_evaluador = $name_evaluador;
        $this->primera_fase_activa = $primera_fase_activa;
        $this->segunda_fase_activa = $segunda_fase_activa;
    }

    public function build()
    {        
        return $this->markdown('emails.planes.recordatorio_planes_de_mejora');
    }
}
