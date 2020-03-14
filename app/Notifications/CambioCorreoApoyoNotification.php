<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CambioCorreoApoyoNotification extends Notification
{
    use Queueable;

    protected $subject;
    private $verificationCode;

    /**
     * Create a new notification instance.
     *
     * @param mixed $verificationCode
     */
    public function __construct($verificationCode)
    {
        $this->subject = 'Cambio de correo apoyo área de egresados Universidad del Cauca';
        $this->verificationCode = $verificationCode;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->subject($this->subject)
            ->view(
                'mail.cambio_correo_apoyo',
                ['apoyo' => $notifiable, 'codigo' => $this->verificationCode]
            )
        ;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
        ];
    }
}
