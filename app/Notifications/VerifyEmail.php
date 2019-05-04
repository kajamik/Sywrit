<?php

namespace App\Notifications;

use Illuminate\Support\Facades\Lang;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailNotification;

class VerifyEmail extends VerifyEmailNotification
{

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
     public function toMail($notifiable)
     {
         return (new MailMessage)
             ->subject(Lang::getFromJson('Benvenuto in Sywrit'))
             ->line(Lang::getFromJson('Clicca il bottone sottostante per verificare il tuo indirizzo email'))
             ->action(
                 Lang::getFromJson('Verifica indirizzo email'),
                 $this->verificationUrl($notifiable)
             )
             ->line(Lang::getFromJson('Se non hai creato l\'account, ti preghiamo di ignorare la email.'));
     }

}
