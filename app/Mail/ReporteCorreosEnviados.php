<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReporteCorreosEnviados extends Mailable
{
    use Queueable, SerializesModels;

    public $evaluadores_enviados;

    public function __construct(
        $evaluadores_enviados
        )
    {
        $this->evaluadores_enviados = $evaluadores_enviados;
    }

    public function build()
    {       
        return $this->markdown('emails.evaluaciones.reporte_correos_enviados',[
            'evaluadores_enviados' => $this->evaluadores_enviados,
        ]);
    }
}
