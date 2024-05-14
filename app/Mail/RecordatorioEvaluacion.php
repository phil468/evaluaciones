<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RecordatorioEvaluacion extends Mailable
{
    use Queueable, SerializesModels;

    public $evaluador;
    public $lista_de_correos_de_evaluadores;
    public $name_evaluador;

    public function __construct($name_evaluador,$evaluador,$lista_de_correos_de_evaluadores)
    {
        $this->name_evaluador = $name_evaluador;
        $this->evaluador = $evaluador;
        $this->lista_de_correos_de_evaluadores = $lista_de_correos_de_evaluadores;
    }

    public function build()
    {
        return $this->markdown('emails.evaluaciones.recordatorio_evaluacion');
    }
}
