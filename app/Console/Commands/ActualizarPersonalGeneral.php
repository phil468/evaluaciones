<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\PersonalController;

class ActualizarPersonalGeneral extends Command
{
    protected $signature = 'personal:actualizar-general';
    protected $description = 'Ejecuta la actualización general del personal desde la API externa';

    public function handle()
    {
        $this->info(now()->toDateTimeString() . ' Iniciando actualización general de personal...');
        
        $controller = new PersonalController();
        $resultado = $controller->actualizacionGeneralCompleta();
        
        if (isset($resultado['success']) && $resultado['success']) {
            $this->info('Actualización general completada exitosamente.');
            //agregar la fecha y hora de la última actualización
            $this->info('Última actualización: ' . now()->toDateTimeString());
            $this->line('Detalles: ' . json_encode($resultado['detalles']));
            return 0;
        } else {
            $this->error('Error en la actualización general.');
            $this->line('Detalles: ' . json_encode($resultado));
            return 1;
        }
    }
}