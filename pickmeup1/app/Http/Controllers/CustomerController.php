<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\RideHistory;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function getCustomers()
    {
        $customers = User::where('role_id', User::ROLE_CUSTOMER)->get(['first_name', 'last_name', 'mobile_number', 'status']);
        return response()->json($customers);
    }

}