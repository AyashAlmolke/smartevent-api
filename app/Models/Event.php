<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_name',
        'location',
        'start_time',
        'end_time',
        'description',
        'admin_id',
        'status',
        'max_attendees',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }
    
    // ✅ أضف هذه العلاقة (الأهم)
    public function attendances()
    {
        return $this->hasManyThrough(Attendance::class, Invitation::class, 'event_id', 'invitation_id');
    }
    
    // ✅ علاقة مساعدة للحضور المباشر (أسهل)
    public function directAttendances()
    {
        return $this->hasMany(Attendance::class);
    }
}