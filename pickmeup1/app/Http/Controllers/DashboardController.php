<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\RideHistory;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getCounts()
    {
        $activeRidersCount = User::where('role_id', User::ROLE_RIDER)
            ->where('status', 'Active')
            ->count();

        $disabledRidersCount = User::where('role_id', User::ROLE_RIDER)
            ->where('status', 'Disabled')
            ->count();
    
        $customersCount = User::where('role_id', User::ROLE_CUSTOMER)->count();
        
        $completedRidesCount = RideHistory::where('status', 'Completed')->count();

        return response()->json([
            'active_riders' => $activeRidersCount,
            'disabled_riders' => $disabledRidersCount,
            'customers' => $customersCount,
            'completed_rides' => $completedRidesCount
        ]);
    }
}