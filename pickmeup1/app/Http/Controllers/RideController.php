<?php

namespace App\Http\Controllers;

use App\Models\RideLocation;
use App\Models\RideHistory;
use Illuminate\Http\Request;

class RideController extends Controller
{

    public function saveRideLocation(Request $request)
    {
        $ride = RideHistory::where('user_id', $request->user_id)
            ->latest()
            ->first();
        
        if (!$ride) {
            return response()->json(['message' => 'Ride not found'], 404);
        }

        $rideLocation = new RideLocation();
        $rideLocation->ride_id = $ride->ride_id;
        $rideLocation->customer_latitude = $request->customer_latitude;
        $rideLocation->customer_longitude = $request->customer_longitude;
        $rideLocation->dropoff_latitude = $request->dropoff_latitude;
        $rideLocation->dropoff_longitude = $request->dropoff_longitude;
        $rideLocation->save();

        return response()->json(['message' => 'Ride location saved successfully']);
    }

    // public function saveRideLocation(Request $request)
    // {
    //     $request->validate([
    //         'user_id' => 'required|exists:users,user_id',
    //         'pickup_location' => 'required|string',
    //         'pickup_address' => 'required|string',
    //         'dropoff_location' => 'required|string',
    //         'dropoff_address' => 'required|string',
    //     ]);

    //     try {
    //         $rideLocation = RideLocation::create([
    //             'user_id' => $request->input('user_id'),
    //             'pickup_location' => $request->input('pickup_location'),
    //             'pickup_address' => $request->input('pickup_address'),
    //             'dropoff_location' => $request->input('dropoff_location'),
    //             'dropoff_address' => $request->input('dropoff_address'),
    //         ]);

    //         return response()->json(['success' => true, 'message' => 'Ride location saved successfully!', 'data' => $rideLocation], 201);
    //     } catch (\Exception $e) {
    //         return response()->json(['success' => false, 'message' => 'Failed to save ride location.', 'error' => $e->getMessage()], 500);
    //     }
    // }
}
