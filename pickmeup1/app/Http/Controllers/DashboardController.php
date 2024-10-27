<?php

namespace App\Http\Controllers;

use App\Events\DashboardUpdated;
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

        $counts = [
            'active_riders' => $activeRidersCount,
            'disabled_riders' => $disabledRidersCount,
            'customers' => $customersCount,
            'completed_rides' => $completedRidesCount
        ];

        // Fetch RideHistory records, ordered by latest to oldest
        $bookings = RideHistory::with(['user', 'rider'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Broadcast the event
        broadcast(new DashboardUpdated($counts, $bookings));

        return response()->json([
            'counts' => $counts,
            'bookings' => $bookings,
        ]);
    }
}