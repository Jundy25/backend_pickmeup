<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'role_id',
        'first_name',
        'last_name',
        'gender',
        'date_of_birth',
        'email',
        'user_name',
        'password',
        'mobile_number',
        'status'
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'role_id' => 'integer',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }

    // In User model or a separate RoleConstants class
    public const ROLE_RIDER = 3;
    public const ROLE_CUSTOMER = 4;

    public function riderRegistrations()
    {
        return $this->hasMany(Rider::class, 'user_id', 'user_id');
    }

    public function rideHistories()
    {
        return $this->hasMany(RideHistory::class, 'user_id', 'user_id');
    }

    public function feedbacks()
    {
        return $this->morphMany(Feedback::class, 'sender');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class, 'user_id', 'user_id');
    }

    public function sentActivities()
    {
        return $this->morphMany(ActivityLog::class, 'sender');
    }

    public function receivedActivities()
    {
        return $this->morphMany(ActivityLog::class, 'recipient');
    }
}
