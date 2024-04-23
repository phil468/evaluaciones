<?php

namespace App\Console;

use App\Models\Evaluacione;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Notification;


class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $recordatorios = \App\Models\Recordatorio::whereDate('fecha', now())->get();
    
            foreach ($recordatorios as $recordatorio) {
                //$evaluacion = $recordatorio->evaluacion;

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
                ->where('evaluaciones.id', $recordatorio->id_evaluacion)
                ->groupBy('personal.correo_empresa')
                ->get()->pluck('correo_empresa');
            
                //dd($evaluadores);

                //enviar notificacion a todos estos correos
    
                // Aquí debes obtener los usuarios a los que quieres enviar la notificación
                // Por ejemplo, si tienes una relación en tu modelo Evaluacion que se llama usuarios:
                //$usuarios = $evaluacion->usuarios;
    
                
                foreach ($evaluadores as $correo) {
                    Notification::route('mail', $correo)->notify(new \App\Notifications\RecordatorioNotification());
                }
            }
        })->everyTenMinutes();
        
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
