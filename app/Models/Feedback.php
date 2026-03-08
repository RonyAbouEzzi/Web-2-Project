<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedbacks';

    protected $fillable = [
        'citizen_id', 'office_id', 'service_request_id',
        'rating', 'comment', 'office_reply', 'reply_is_public',
    ];

    protected $casts = ['reply_is_public' => 'boolean'];

    public function citizen() { return $this->belongsTo(User::class, 'citizen_id'); }
    public function office()  { return $this->belongsTo(Office::class); }
    public function request() { return $this->belongsTo(ServiceRequest::class, 'service_request_id'); }
}
