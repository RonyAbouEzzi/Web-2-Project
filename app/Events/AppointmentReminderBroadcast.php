<?php

namespace App\Events;

use App\Models\Appointment;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AppointmentReminderBroadcast implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Appointment $appointment,
        public string $source = 'manual',
    ) {
    }

    public function broadcastOn(): array
    {
        $channels = [
            new PrivateChannel('user.' . $this->appointment->citizen_id),
            new PrivateChannel('office.' . $this->appointment->office_id),
        ];

        if ($this->appointment->service_request_id) {
            $channels[] = new PrivateChannel('request.' . $this->appointment->service_request_id);
        }

        return $channels;
    }

    public function broadcastAs(): string
    {
        return 'appointment.reminder';
    }

    public function broadcastWith(): array
    {
        return [
            'appointment_id' => $this->appointment->id,
            'service_request_id' => $this->appointment->service_request_id,
            'office_id' => $this->appointment->office_id,
            'citizen_id' => $this->appointment->citizen_id,
            'status' => $this->appointment->status,
            'appointment_date' => $this->appointment->appointment_date,
            'appointment_time' => $this->appointment->appointment_time,
            'source' => $this->source,
            'message' => sprintf(
                'Appointment reminder: %s at %s',
                $this->appointment->appointment_date,
                substr((string) $this->appointment->appointment_time, 0, 5)
            ),
        ];
    }
}

