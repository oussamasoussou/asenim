<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class CustomResetPasswordNotification extends ResetPassword
{
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Réinitialisation de votre mot de passe')
                    ->line('Vous recevez ce message car nous avons reçu une demande de réinitialisation de mot de passe pour votre compte.')
                    ->action('Réinitialiser le mot de passe', url(route('password.reset', [
                        'token' => $this->token, 'email' => $notifiable->getEmailForPasswordReset()
                    ], false)))
                    ->line('Si vous n\'avez pas fait cette demande, ignorez simplement ce message.');
    }
}
