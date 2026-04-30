<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BehaviorData extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'attempts_count',
        'time_patterns',
        'risk_score',
    ];

    protected $casts = [
        'attempts_count' => 'integer',
        'risk_score' => 'float',
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
}
