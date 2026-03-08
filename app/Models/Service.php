<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'office_id', 'category_id', 'name', 'description',
        'price', 'currency', 'estimated_duration_days',
        'required_documents', 'is_active',
    ];

    protected $casts = [
        'required_documents' => 'array',
        'is_active'          => 'boolean',
    ];

    public function office()   { return $this->belongsTo(Office::class); }
    public function category() { return $this->belongsTo(ServiceCategory::class, 'category_id'); }
    public function requests() { return $this->hasMany(ServiceRequest::class); }
}
