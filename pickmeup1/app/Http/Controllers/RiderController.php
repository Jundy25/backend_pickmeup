<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\RideHistory;
use Illuminate\Http\Request;

class RiderController extends Controller
{
    public function getRiders()
    {
        $riders = User::where('role_id', User::ROLE_RIDER)->get(['first_name', 'last_name', 'mobile_number', 'status']);
        return response()->json($riders);
    }

}