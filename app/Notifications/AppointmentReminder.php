<?php

namespace App\Notifications;

use App\Models\Appointment;
use App\Notifications\Channels\SmsChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class AppointmentReminder extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Appointment $appointment,
        public string $source = 'manual',
    ) {
    }

    public function via(object $notifiable): array
    {
        $channels = ['mail', 'database', 'broadcast'];

        $smsSources = ['appointment_confirmed', 'daily_scheduler'];
        if (in_array($this->source, $smsSources, true) && filled($notifiable->phone ?? null)) {
            $channels[] = SmsChannel::class;
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        $appointment = $this->appointment;
        $requestRef = $appointment->request?->reference_number;

        $mail = (new MailMessage)
            ->subject('Appointment Reminder')
            ->greeting("Hello {$notifiable->name},")
            ->line('This is a reminder regarding your appointment.')
            ->line('Date: ' . $appointment->appointment_date)
            ->line('Time: ' . substr((string) $appointment->appointment_time, 0, 5))
            ->line('Status: ' . ucfirst($appointment->status));

        if ($requestRef) {
            $mail->line('Request: ' . $requestRef)
                ->action('View Request', route('citizen.requests.show', $appointment->service_request_id));
        } else {
            $mail->action('View Office', route('citizen.offices.show', $appointment->office_id));
        }

        return $mail->line('Please arrive on time and bring required documents.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'appointment_id' => $this->appointment->id,
            'service_request_id' => $this->appointment->service_request_id,
            'appointment_date' => $this->appointment->appointment_date,
            'appointment_time' => $this->appointment->appointment_time,
            'status' => $this->appointment->status,
            'source' => $this->source,
            'message' => 'Appointment reminder for ' . $this->appointment->appointment_date . ' at ' . substr((string) $this->appointment->appointment_time, 0, 5) . '.',
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }

    public function toSms(object $notifiable): string
    {
        $date = (string) $this->appointment->appointment_date;
        $time = substr((string) $this->appointment->appointment_time, 0, 5);
        $status = ucfirst((string) $this->appointment->status);

        $link = $this->appointment->service_request_id
            ? route('citizen.requests.show', $this->appointment->service_request_id)
            : route('citizen.offices.show', $this->appointment->office_id);

        return "E-Services: Appointment {$status} on {$date} at {$time}. {$link}";
    }
}
