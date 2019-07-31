<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SecurityCode extends Notification
{
    use Queueable;

    private $user;
    private $code;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $code)
    {
        $this->user = $user;
        $this->code = $code;
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
                ->from('no-reply@sywrit.com', 'Sywrit NO-REPLY')
                ->subject('Codice di sicurezza - Sywrit')
                ->line('Salve '. $this->user->name .',')
                ->line('Abbiamo ricevuto una richiesta di reimpostazione della tua password di Sywrit.')
                ->line('Inserisci il seguente codice per la reimpostazione della password:')
                ->line(['security_code' => $this->code])
                ->markdown('vendor.notifications.sCode_email');
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
