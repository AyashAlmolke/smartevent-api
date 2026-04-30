<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'qr_code',
        'status',
        'used_at',
        'scan_attempts',
    ];

    protected $casts = [
        'used_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function attendance()
    {
        return $this->hasOne(Attendance::class);
    }

    public function logs()
    {
        return $this->hasMany(Log::class);
    }
    
    // هل الدعوة صالحة؟
    public function isValid()
    {
        return $this->status === 'active' && is_null($this->used_at);
    }
}