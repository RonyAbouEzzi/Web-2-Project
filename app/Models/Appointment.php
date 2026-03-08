<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'citizen_id', 'office_id', 'service_request_id',
        'appointment_date', 'appointment_time', 'status', 'notes',
    ];

    public function citizen() { return $this->belongsTo(User::class, 'citizen_id'); }
    public function office()  { return $this->belongsTo(Office::class); }
    public function request() { return $this->belongsTo(ServiceRequest::class, 'service_request_id'); }
}
