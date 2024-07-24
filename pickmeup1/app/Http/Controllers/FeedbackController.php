<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index()
    {
        $feedbacks = Feedback::with(['sender', 'recipient'])->get()->map(function ($feedback) {
            return [
                'feedback_id' => $feedback->feedback_id,
                'sender_name' => $feedback->sender_name,
                'recipient_name' => $feedback->recipient_name,
                'comment' => $feedback->comment,
                'rating' => $feedback->rating,
                'ride_id' => $feedback->ride_id,
                'sender_type' => $feedback->sender_type,
                'created_at' => $feedback->created_at,
            ];
        });

        return response()->json($feedbacks);
    }
}