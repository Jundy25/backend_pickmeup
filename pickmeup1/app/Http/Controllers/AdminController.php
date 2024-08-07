<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\RideHistory;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateUserRequest;

class AdminController extends Controller
{
    public function getAdmin()
    {
        $admin = User::where('role_id', User::ROLE_ADMIN)->get(['user_id', 'first_name', 'last_name', 'email', 'mobile_number', 'date_of_birth', 'user_name','gender', 'status']);
        return response()->json($admin);
    }


    public function updateStatus(Request $request, $user_id)
    {
        $request->validate([
            'status' => 'required|in:Active,Disabled',
        ]);

        $user = User::findOrFail($user_id); // Ensure the user exists
        $user->status = $request->status;
        $user->save();

        return response()->json([
            'message' => 'User status updated successfully',
            'user' => $user
        ]);
    }

    public function show($user_id)
    {
        $admin = User::where('user_id', $user_id)->where('role_id', 2)->first();
        if ($admin) {
            return response()->json($admin);
        }
        return response()->json(['message' => 'Admin not found'], 404);
    }


    public function update(Request $request, $user_id)
    {
        $admin = User::find($user_id);

        if ($admin && $admin->role_id == 2) {
            $validatedData = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'user_name' => 'required|string|max:255|unique:users,user_name,' . $admin->id,
                'email' => 'required|string|email|max:255|unique:users,email,' . $admin->id,
                'password' => 'nullable|string|min:8|confirmed',
                'gender' => 'required|string',
                'date_of_birth' => 'required|date',
                'mobile_number' => 'required|string|max:20',
            ]);

            $admin->first_name = $validatedData['first_name'];
            $admin->last_name = $validatedData['last_name'];
            $admin->user_name = $validatedData['user_name'];
            $admin->email = $validatedData['email'];
            if (!empty($validatedData['password'])) {
                $admin->password = Hash::make($validatedData['password']);
            }
            $admin->gender = $validatedData['gender'];
            $admin->date_of_birth = $validatedData['date_of_birth'];
            $admin->mobile_number = $validatedData['mobile_number'];
            $admin->save();

            return response()->json($admin);
        }

        return response()->json(['message' => 'Admin not found'], 404);
    }


    public function accountUpdate(UpdateUserRequest $request, User $user)
    {
        try {
            $userDetails = $user->find($request->user()->user_id);

            if (! $userDetails) {
                return response(["message" => "User not found"], 404);
            }

            $userDetails->update($request->validated());

            return response(["message" => "User Successfully Updated"], 200);
        } catch (\Throwable $th) {
            return response(["message" => $th->getMessage()], 400);
        }
    }

}