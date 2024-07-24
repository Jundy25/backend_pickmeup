<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Rider;

class Feedback extends Model
{
    use HasFactory;

    protected $primaryKey = 'feedback_id';
    protected $table = 'feedbacks';

    protected $fillable = [
        'sender_id',
        'sender_type',
        'recipient_id',
        'recipient_type',
        'comment',
        'rating',
        'ride_id',
    ];

    public function sender()
    {
        return $this->morphTo();
    }

    public function recipient()
    {
        return $this->morphTo();
    }

    public function getSenderNameAttribute()
    {
        if ($this->sender_type === 'App\Models\User') {
            return $this->sender->first_name . ' ' . $this->sender->last_name;
        } elseif ($this->sender_type === 'App\Models\Rider') {
            return $this->sender->user->first_name . ' ' . $this->sender->user->last_name;
        }
        return 'Unknown';
    }

    public function getRecipientNameAttribute()
    {
        if ($this->recipient_type === 'App\Models\User') {
            return $this->recipient->first_name . ' ' . $this->recipient->last_name;
        } elseif ($this->recipient_type === 'App\Models\Rider') {
            return $this->recipient->user->first_name . ' ' . $this->recipient->user->last_name;
        }
        return 'Unknown';
    }
}