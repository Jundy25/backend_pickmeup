<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\RideHistory;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function getAdmin()
    {
        $admin = User::where('role_id', User::ROLE_ADMIN)->get(['first_name', 'last_name', 'mobile_number', 'status']);
        return response()->json($admin);
    }

}