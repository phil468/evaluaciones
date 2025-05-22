<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class ComiteFormadoNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $comite;

    public function __construct($comite)
    {
        $this->comite = $comite;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('Has sido asignado a un Comité de Calibración')
            ->greeting('Hola ' . $notifiable->nombres)
            ->line('Has sido asignado a un comité de calibración.')
            ->line('Detalles del Comité:')
            // ->line('ID del Comité: ' . $this->comite->id)
            ->line('Personal: ' . ($this->comite->personal->name ?? ''))
            ->line('Campaña: ' . ($this->comite->campania->name ?? ''))
            ->line('Área: ' . ($this->comite->area ?? '')) //tiene que cambiarse por la matriz de campaña personal
            ->line('Nivel Jerárquico: ' . ($this->comite->nivel_jerarquico ?? '')) //tiene que cambiarse por la matriz de campaña personal
            ->line('Comentario: ' . ($this->comite->comentario ?? ''));
            // ->line('Por favor, ingresa al sistema para participar en el proceso de calibración.');
    }
}