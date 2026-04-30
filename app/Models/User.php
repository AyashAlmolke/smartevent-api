<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * الحقول القابلة للتعبئة
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'national_id',
        'organization',
        'is_blacklisted',
        'last_seen_at',
    ];

    /**
     * الحقول المخفية
     */
    protected $hidden = [
        'remember_token',
    ];

    /**
     * التحويلات (Casting)
     */
    protected $casts = [
        'last_seen_at' => 'datetime',
        'is_blacklisted' => 'boolean',
    ];

    /*
    |---------------------------------------
    | العلاقات
    |---------------------------------------
    */

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    public function behaviorData()
    {
        return $this->hasMany(BehaviorData::class);
    }
}