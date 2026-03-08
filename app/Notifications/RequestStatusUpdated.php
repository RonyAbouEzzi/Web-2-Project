<?php

namespace App\Notifications;

use App\Models\ServiceRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequestStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public ServiceRequest $serviceRequest) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $req    = $this->serviceRequest;
        $status = ucfirst(str_replace('_', ' ', $req->status));

        return (new MailMessage)
            ->subject("Request #{$req->reference_number} — Status Updated")
            ->greeting("Hello {$notifiable->name},")
            ->line("Your service request **{$req->reference_number}** has been updated.")
            ->line("**New Status:** {$status}")
            ->when($req->office_notes, fn ($m) => $m->line("**Office Note:** {$req->office_notes}"))
            ->action('View Request', route('citizen.requests.show', $req))
            ->line('Thank you for using our e-services platform.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'request_id'       => $this->serviceRequest->id,
            'reference_number' => $this->serviceRequest->reference_number,
            'status'           => $this->serviceRequest->status,
            'message'          => "Your request #{$this->serviceRequest->reference_number} status changed to {$this->serviceRequest->status}.",
        ];
    }
}
