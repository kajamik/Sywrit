<?php

namespace App\Notifications;

use Illuminate\Support\Facades\Lang;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;

class ResetPassword extends ResetPasswordNotification
{

    public $token;
    public $username;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token, $username)
    {
        $this->token = $token;
        $this->username = $username;
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
              ->subject(Lang::getFromJson('Ripristino password - '. config('app.name')))
              ->line('Salve '. $this->username. ',')
              ->line(Lang::getFromJson('Hai ricevuto questa email perché abbiamo ricevuto una richiesta di reimpostazione della password per il tuo account.'))
              ->action(Lang::getFromJson('Ripristino password'), url(config('app.url').route('password.reset', ['token' => $this->token], false)))
              ->line(Lang::getFromJson('L\'url di ripristino scadrà tra :count minuti.', ['count' => config('auth.passwords.users.expire')]))
              ->line(Lang::getFromJson('Se non hai richiesto la reimpostazione della password, ti preghiamo di ignorare la email.'));
    }

}
