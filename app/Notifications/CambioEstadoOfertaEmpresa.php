<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CambioEstadoOfertaEmpresa extends Notification
{
    use Queueable;

    public $subject = "";
    protected $oferta;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($oferta)
    {
        $this->subject = "Cambio estado Oferta | Ofertas Laborales Unicauca";
        $this->oferta = $oferta;

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
            ->view("mail.notificacion_cambio_estado_oferta_empresa", ["oferta" => $this->oferta]);
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
