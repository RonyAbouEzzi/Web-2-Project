<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceCategory extends Model
{
    use HasFactory;

    protected $fillable = ['office_id', 'name', 'icon', 'description'];

    public function office()   { return $this->belongsTo(Office::class); }
    public function services() { return $this->hasMany(Service::class, 'category_id'); }
}
