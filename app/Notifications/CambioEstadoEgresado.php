<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CambioEstadoEgresado extends Notification
{
    use Queueable;

    public $subject = "Cambio estado Egresado | Ofertas Laborales Unicauca";
    protected $oferta = null;
    protected $estado = null;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($oferta, $estado)
    {
        $this->subject = "Cambio estado Egresado | Ofertas Laborales Unicauca";
        $this->oferta = $oferta;
        $this->estado = $estado;

    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
        ->subject($this->subject)
        ->view("mail.notificacion_cambio_estado_egresado", ["egresado" => $notifiable, "oferta" => $this->oferta, "estado" => $this->estado]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
