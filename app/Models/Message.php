<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'service_request_id', 'sender_id', 'body', 'attachment', 'read_at',
    ];

    protected $casts = ['read_at' => 'datetime'];

    public function request() { return $this->belongsTo(ServiceRequest::class, 'service_request_id'); }
    public function sender()  { return $this->belongsTo(User::class, 'sender_id'); }
}
