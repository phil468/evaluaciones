<?php
namespace App\Mail;

use App\Models\PlanesDeAccion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EstadoAprobacionPlanMail extends Mailable {
    use Queueable, SerializesModels;

    public $plan;
    public function __construct(PlanesDeAccion $plan){
        $this->plan = $plan;
    }
    public function build(){
        $estado = $this->plan->estado_aprobacion;
        $subject = 'Plan de Mejora '.strtoupper($estado);
        return $this->subject($subject)
            ->view('emails.plan_estado_aprobacion');
    }
}