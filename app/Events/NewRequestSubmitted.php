<?php

namespace App\Events;

use App\Models\ServiceRequest;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewRequestSubmitted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public ServiceRequest $serviceRequest)
    {
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('office.' . $this->serviceRequest->office_id),
            new PrivateChannel('user.' . $this->serviceRequest->citizen_id),
            new PrivateChannel('request.' . $this->serviceRequest->id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'request.submitted';
    }

    public function broadcastWith(): array
    {
        return [
            'request_id' => $this->serviceRequest->id,
            'reference_number' => $this->serviceRequest->reference_number,
            'office_id' => $this->serviceRequest->office_id,
            'citizen_id' => $this->serviceRequest->citizen_id,
            'status' => $this->serviceRequest->status,
            'payment_status' => $this->serviceRequest->payment_status,
            'submitted_at' => optional($this->serviceRequest->created_at)->toIso8601String(),
        ];
    }
}

