<?php

namespace App\Events;

use App\Models\ServiceRequest;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ServiceRequestStatusUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public ServiceRequest $serviceRequest,
        public string $oldStatus,
        public ?string $comment = null,
    ) {
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
        return 'request.status.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'request_id' => $this->serviceRequest->id,
            'reference_number' => $this->serviceRequest->reference_number,
            'office_id' => $this->serviceRequest->office_id,
            'citizen_id' => $this->serviceRequest->citizen_id,
            'old_status' => $this->oldStatus,
            'new_status' => $this->serviceRequest->status,
            'comment' => $this->comment,
            'updated_at' => optional($this->serviceRequest->updated_at)->toIso8601String(),
        ];
    }
}

