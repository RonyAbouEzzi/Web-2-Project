<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Office extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'municipality_id', 'name', 'address', 'latitude', 'longitude',
        'phone', 'email', 'website', 'working_hours', 'logo', 'is_active',
    ];

    protected $casts = [
        'working_hours' => 'array',
        'is_active'     => 'boolean',
    ];

    public function municipality()  { return $this->belongsTo(Municipality::class); }
    public function users()         { return $this->belongsToMany(User::class, 'office_users')->withPivot('role'); }
    public function services()      { return $this->hasMany(Service::class); }
    public function categories()    { return $this->hasMany(ServiceCategory::class); }
    public function requests()      { return $this->hasMany(ServiceRequest::class); }
    public function appointments()  { return $this->hasMany(Appointment::class); }
    public function feedbacks()     { return $this->hasMany(Feedback::class); }

    public function averageRating()
    {
        return $this->feedbacks()->avg('rating');
    }
}
