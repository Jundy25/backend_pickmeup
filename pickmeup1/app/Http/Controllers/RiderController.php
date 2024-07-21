<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Rider;
use App\Models\RideHistory;
use Illuminate\Http\Request;

class RiderController extends Controller
{
    public function getRiders()
    {
        $riders = User::where('role_id', User::ROLE_RIDER)
            ->with('rider:verification_status,user_id')
            ->get(['first_name', 'last_name', 'mobile_number', 'status', 'user_id']);

        return response()->json($riders);
    }

}