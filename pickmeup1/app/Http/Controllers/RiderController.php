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
            ->get(['user_id', 'first_name', 'last_name', 'mobile_number', 'status', 'user_id', 'email', 'date_of_birth']);

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