<?php
namespace App\Mail;

use App\Models\PlanesDeAccion;
use App\Models\EncargadosPlanesDeAccion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EstadoAprobacionPlanMail extends Mailable {
    use Queueable, SerializesModels;

    public $planes;
    public $encargadoPlan;
    public $empleado;
    
    public function __construct(EncargadosPlanesDeAccion $encargadoPlan){
        $this->encargadoPlan = $encargadoPlan;
        $this->planes = $encargadoPlan->planesDeMejora;
        $this->empleado = $encargadoPlan->empleado;
    }
    
    public function build(){
        $subject = 'Validación de Planes de Mejora Individual (PMI)';
        return $this->subject($subject)
            ->view('emails.plan_estado_aprobacion');
    }
}
