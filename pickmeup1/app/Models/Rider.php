<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rider extends Model
{
    use HasFactory;

    protected $primaryKey = 'rider_id';

    protected $fillable = [
        'user_id',
        'registration_date',
        'verification_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function requirementPhotos()
    {
        return $this->hasMany(RequirementPhoto::class, 'rider_id', 'rider_id');
    }

    public function rideHistories()
    {
        return $this->hasMany(RideHistory::class, 'rider_id', 'rider_id');
    }

    public function sentActivities()
    {
        return $this->morphMany(ActivityLog::class, 'sender');
    }

    public function receivedActivities()
    {
        return $this->morphMany(ActivityLog::class, 'recipient');
    }

    public function updateUserStatus()
{
    if ($this->verification_status === 'verified') {
        $this->user()->update(['status' => 'Active']);
    } else {
        $this->user()->update(['status' => 'Inactive']);
    }
}
}
