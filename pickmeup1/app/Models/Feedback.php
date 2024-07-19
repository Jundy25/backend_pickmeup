<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $primaryKey = 'feedback_id';

    protected $fillable = [
        'sender_id',
        'sender_type',
        'recipient_id',
        'recipient_type',
        'comments',
        'rating',
    ];

    public function sender()
    {
        return $this->morphTo();
    }

    public function recipient()
    {
        return $this->morphTo();
    }
}
