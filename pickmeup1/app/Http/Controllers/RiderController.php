<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Rider;
use App\Models\RequirementPhoto;
use App\Models\Requirement;
use App\Models\RideHistory;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class RiderController extends Controller
{
    public function getRiders()
    {
        $riders = User::where('role_id', User::ROLE_RIDER)
            ->whereHas('rider', function($query) {
                $query->where('verification_status', 'Verified');
            })
            ->with(['rider:verification_status,user_id,rider_id', 'rider.requirementPhotos' => function($query) {
                $query->whereIn('requirement_id', [3, 7]) // Assuming 1 is for license and 2 is for OR
                    ->select('rider_id', 'requirement_id', 'photo_url');
            }])
            ->get(['user_id', 'first_name', 'last_name', 'mobile_number', 'status', 'email', 'date_of_birth']);

        return response()->json($riders);
    }

    public function getRidersRequirements()
    {
        // Fetch riders with their related user data and requirement photos
        $riders = Rider::with([
            'user', // Load the associated User model
            'requirementphotos' // Load the associated RequirementPhoto model
        ])->get();

        // Return the data as JSON
        return response()->json($riders);
    }

    public function upload(Request $request)
    {
        $ride = Rider::where('user_id', $user_id);


        // Validate the request data
        $validated = $request->validate([
            'rider_id' => 'required|exists:riders,id',
            'requirement_id' => 'required|exists:requirements,id',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Store the file in the storage
        $path = $request->file('photo')->store('public/requirement_photos');

        // Save file path in the database
        $requirementPhoto = RequirementPhoto::create([
            'rider_id' => $validated['rider_id'],
            'requirement_id' => $validated['requirement_id'],
            'photo_url' => Storage::url($path),
        ]);

        return response()->json(['success' => true, 'file' => $requirementPhoto]);
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->status = $request->input('status');
            $user->save();

            return response()->json(['message' => 'User status updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update user status.'], 500);
        }
    }

    public function getAvailableRides()
    {
        $availableRides = RideHistory::where('ride_histories.status', 'Available') 
            ->join('users', 'ride_histories.user_id', '=', 'users.user_id')
            ->select('ride_histories.*', 'users.first_name', 'users.last_name')
            ->orderBy('ride_histories.created_at', 'desc')
            ->get();
    
        return response()->json($availableRides);
    }

    
    public function accept_ride(Request $request, $ride_id)
    {
        Log::info("Attempting to accept ride with ID: " . $ride_id);

        try {
            return DB::transaction(function () use ($ride_id, $request) {
                // Validate that user_id is provided
                $validated = $request->validate([
                    'user_id' => 'required|exists:users,user_id',
                ]);

                $user_id = $validated['user_id'];

                $ride = RideHistory::where('ride_id', $ride_id)
                                ->where('status', 'Available')
                                ->lockForUpdate()
                                ->first();

                if (!$ride) {
                    Log::warning("Ride not available for acceptance: " . $ride_id);
                    return response()->json(['error' => 'This ride is no longer available.'], 400);
                }

                // Update the ride status and assign the rider_id
                $ride->status = 'Occupied';
                $ride->rider_id = $user_id;
                $ride->save();

                Log::info("Ride accepted successfully: " . $ride_id);
                return response()->json(['message' => 'Ride Accepted Successfully.']);
            });
        } catch (\Exception $e) {
            Log::error("Failed to accept ride: " . $e->getMessage());
            return response()->json(['error' => 'Failed to accept ride. Please try again.'], 500);
        }
    }


    public function checkActiveRide($user_id)
    {
        $activeRide = RideHistory::where('rider_id', $user_id)
            ->whereIn('status', ['Occupied', 'In Transit'])
            ->with(['user', 'rider'])
            ->latest()
            ->first();

        return response()->json([
            'hasActiveRide' => $activeRide !== null,
            'rideDetails' => $activeRide
        ]);
    }


    public function finish_ride(Request $request, $ride_id)
    {
        Log::info("Attempting to accept ride with ID: " . $ride_id);

        try {
            return DB::transaction(function () use ($ride_id, $request) {
                $ride = RideHistory::where('ride_id', $ride_id)
                                ->where('status', 'In Transit')
                                ->lockForUpdate()
                                ->first();

                if (!$ride) {
                    Log::warning("Ride not available for acceptance: " . $ride_id);
                    return response()->json(['error' => 'This ride is no longer available.'], 400);
                }

                // Update the ride status and assign the rider_id
                $ride->status = 'Completed';
                $ride->save();

                Log::info("Ride accepted successfully: " . $ride_id);
                return response()->json(['message' => 'Ride Accepted Successfully.']);
            });
        } catch (\Exception $e) {
            Log::error("Failed to accept ride: " . $e->getMessage());
            return response()->json(['error' => 'Failed to accept ride. Please try again.'], 500);
        }
    }


}