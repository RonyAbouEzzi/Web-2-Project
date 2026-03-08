<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'reference_number', 'citizen_id', 'service_id', 'office_id',
        'status', 'notes', 'office_notes', 'qr_code',
        'amount_paid', 'payment_method', 'payment_status',
        'transaction_id', 'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    // ── Relationships ─────────────────────────────────────────────
    public function citizen()    { return $this->belongsTo(User::class, 'citizen_id'); }
    public function service()    { return $this->belongsTo(Service::class); }
    public function office()     { return $this->belongsTo(Office::class); }
    public function documents()  { return $this->hasMany(RequestDocument::class); }
    public function statusLogs() { return $this->hasMany(RequestStatusLog::class); }
    public function messages()   { return $this->hasMany(Message::class); }
    public function appointment(){ return $this->hasOne(Appointment::class); }

    // ── Helpers ───────────────────────────────────────────────────
    public static function generateReference(): string
    {
        $year = now()->year;
        $count = static::whereYear('created_at', $year)->count() + 1;
        return 'SRQ-' . $year . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }
}
