<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaDevice extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_name',
        'phone_number',
        'api_key',
        'status',
        'last_seen',
        'is_active'
    ];

    protected $casts = [
        'last_seen' => 'datetime',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function groups()
    {
        return $this->hasMany(WaGroup::class, 'device_id');
    }

    public function messages()
    {
        return $this->hasMany(WaMessage::class, 'device_id');
    }

    public function conversations()
    {
        return $this->hasMany(WaConversation::class, 'device_id');
    }
}
