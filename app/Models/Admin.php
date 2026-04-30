<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;  // ✅ أضف هذا السطر

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory;  // ✅ أضف HasApiTokens هنا

    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
        'is_active',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    public function canAccessDashboard()
    {
        return $this->is_active && ($this->role === 'super_admin' || $this->role === 'admin');
    }

    public function updateLastLogin()
    {
        $this->update(['last_login_at' => now()]);
    }
}
