<?php

namespace App\Events;

use App\Models\RequestDocument;
use App\Models\ServiceRequest;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RequestDocumentUploaded implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public ServiceRequest $serviceRequest,
        public RequestDocument $document,
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
        return 'request.document.uploaded';
    }

    public function broadcastWith(): array
    {
        return [
            'request_id' => $this->serviceRequest->id,
            'reference_number' => $this->serviceRequest->reference_number,
            'office_id' => $this->serviceRequest->office_id,
            'citizen_id' => $this->serviceRequest->citizen_id,
            'document_id' => $this->document->id,
            'original_name' => $this->document->original_name,
            'document_type' => $this->document->document_type,
            'uploaded_by' => $this->document->uploaded_by,
            'uploaded_at' => optional($this->document->created_at)->toIso8601String(),
        ];
    }
}

