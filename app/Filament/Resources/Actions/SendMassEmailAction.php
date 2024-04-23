<?php

namespace App\Filament\Actions;

use App\Models\Evaluacione;
use Filament\Pages\Actions\Action;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class SendMassEmailAction extends Action
{
    public function run()
    {
        return $this->redirect('/admin');
        $evaluadores = 
                Evaluacione::
                select('evaluaciones.title', 'personal.correo_empresa as correo')
                ->join('evaluador_has_evaluados', 'evaluaciones.id', '=', 'evaluador_has_evaluados.evaluacion_id')
                ->join('personal', 'evaluador_has_evaluados.evaluador_id', '=', 'personal.id')
        //        ->pluck('personal.correo_empresa,personal.correo_empresa')
                ->whereNull('evaluador_has_evaluados.realizado')
                ->whereNull('evaluador_has_evaluados.deleted_at')
                ->whereNull('evaluaciones.deleted_at')
                ->whereNull('personal.deleted_at')
                ->where('evaluaciones.status', 1)
                // ->where('evaluaciones.id', $recordatorio->id_evaluacion)
                ->groupBy('personal.correo_empresa')
                ->get()->pluck('correo_empresa');
            
               

                //enviar notificacion a todos estos correos
    
                // Aquí debes obtener los usuarios a los que quieres enviar la notificación
                // Por ejemplo, si tienes una relación en tu modelo Evaluacion que se llama usuarios:
                //$usuarios = $evaluacion->usuarios;
    
                
                foreach ($evaluadores as $correo) {
                    Notification::route('mail', $correo)->notify(new \App\Notifications\RecordatorioNotification());
                }
        
    }
}