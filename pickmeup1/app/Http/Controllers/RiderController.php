<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Rider;
use App\Models\RequirementPhoto;
use App\Models\Requirement;
use App\Models\RideHistory;
use Illuminate\Http\Request;

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
            'requirementPhotos' // Load the associated RequirementPhoto model
        ])->get();

        // Return the data as JSON
        return response()->json($riders);
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
}