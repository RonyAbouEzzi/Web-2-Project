<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone', 'national_id',
        'id_document',          // column in migration
        'is_active',
        'two_factor_secret',    // column in migration
        'two_factor_enabled',   // column in migration
        'social_provider', 'social_id', 'avatar',
    ];

    protected $hidden = ['password', 'remember_token', 'two_factor_secret'];

    protected $casts = [
        'email_verified_at'  => 'datetime',
        'is_active'          => 'boolean',
        'two_factor_enabled' => 'boolean',
    ];

    // ── Role helpers ──────────────────────────────────────────────
    public function isAdmin(): bool      { return $this->role === 'admin'; }
    public function isOfficeUser(): bool { return $this->role === 'office_user'; }
    public function isCitizen(): bool    { return $this->role === 'citizen'; }

    // ── Relationships ─────────────────────────────────────────────
    public function offices()
    {
        return $this->belongsToMany(Office::class, 'office_users')
                    ->withPivot('role')->withTimestamps();
    }

    public function serviceRequests()
    {
        return $this->hasMany(ServiceRequest::class, 'citizen_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'citizen_id');
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class, 'citizen_id');
    }
}
