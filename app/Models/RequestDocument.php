<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestDocument extends Model
{
    protected $fillable = [
        'service_request_id', 'file_path', 'original_name',
        'document_type', 'uploaded_by',
    ];

    public function request() { return $this->belongsTo(ServiceRequest::class, 'service_request_id'); }
}
