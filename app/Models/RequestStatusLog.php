<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestStatusLog extends Model
{
    protected $fillable = [
        'service_request_id', 'changed_by', 'from_status', 'to_status', 'comment',
    ];

    public function request()   { return $this->belongsTo(ServiceRequest::class, 'service_request_id'); }
    public function changedBy() { return $this->belongsTo(User::class, 'changed_by'); }
}
