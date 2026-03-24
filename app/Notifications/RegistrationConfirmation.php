<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegistrationConfirmation extends Notification implements ShouldQueue
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Welcome to E-Services Platform')
            ->greeting("Hello {$notifiable->name},")
            ->line('Your account has been created successfully.')
            ->line('You can now submit requests, track progress, and receive official updates online.')
            ->action('Open Dashboard', route('citizen.dashboard'))
            ->line('Thank you for using our e-services platform.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Your account was created successfully. Welcome to E-Services Platform.',
            'type' => 'registration_confirmation',
        ];
    }
}

