<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Confirmation;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\LoginRequest;
use Infobip\Configuration;
use Infobip\Api\SmsApi;
use Infobip\Model\SmsDestination;
use Infobip\Model\SmsTextualMessage;
use Infobip\Model\SmsAdvancedTextualRequest;

require(__DIR__ . '/../../../vendor/autoload.php');

class AuthController extends Authenticatable
{

    public function __construct() {
        $this->model = new User();
    }

    /**
     *  /api/user/login
     * Store a newly created resource in storage.
     */

    public function showCustomer() {
        try {
            return User::where('role_id', 3)->get();
        } catch (\Throwable $th) {
            return response(["message" => $th->getMessage()]);
        }
    }

    public function showRider() {
        try {
            return User::where('role_id', 2)->get();
        } catch (\Throwable $th) {
            return response(["message" => $th->getMessage()]);
        }
    }

    /**
     *  /api/user/login
     * Store a newly created resource in storage.
     */
    public function loginAccount(LoginRequest $request)
    {
        try {
            $credentials = $request->only(['email', 'password']);

            if (!Auth::attempt($credentials)) {
                return response(['message' => "Account doesn't exist"], 404);
            }

            $user = $request->user();
            $token = $user->createToken('Personal Access Token')->plainTextToken;
            
            return response([
                'token' => $token,
                'role' => $user->role_id,
                'status' => $user->status,
                'user_id' => $user->user_id
            ], 200);
        } catch (\Throwable $th) {
            return response(['message' => $th->getMessage()], 400);
        }
    }



    public function loginAccountMobile(Request $request)
    {
        try {
            $credentials = $request->only(['user_name', 'password']);
    
            if (!Auth::attempt($credentials)) {
                return response(['message' => "Invalid username or password"], 401);
            }
    
            $user = $request->user();
            $token = $user->createToken('Personal Access Token')->plainTextToken;
            
            return response([
                'token' => $token,
                'role' => $user->role_id,
                'status' => $user->status,
                'user_id' => $user->user_id  // Include the user_id in the response
            ], 200);
        } catch (\Throwable $th) {
            return response(['message' => $th->getMessage()], 400);
        }
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

     
    

    // /api/user/signup
    // Create Account function

    // public function createAccount(UserStoreRequest $request)
    // {
    //     try {
    //         $this->model->create($request->all());
    //         return response(['message' => "Successfully created"], 201);
    //     } catch (\Throwable $e) {
    //         // Log the error message to understand what's going wrong
    //         \Log::error('User creation failed: ' . $e->getMessage());
    //         return response(['errors' => $e->getMessage()], 400);
    //     }
    // }



    public function logoutAccount(Request $request) {
        try {
            $request->user()->currentAccessToken()->delete();

            return response(['message' => 'Successfully logout'], 200);
        } catch (\Throwable $th) {
            return response(['message' => $th->getMessage()], 400);
        }
    }

    /**
     * /api/user/me
     */
    public function show(Request $request)
    {
        return response()->json($request->user(), 200);
    }

    public function text(UserStoreRequest $request){
        $phonenum = $request->only(['mobile_number']);
        $email = $request->only(['email']);
        
         // Generate a random 4-digit number
        $randomNumber = rand(1000, 9999);

        // Create the message using the random number
        $message = "Hi! Your OTP code is: " . $randomNumber. "You can use this to verify your mobile number to use Picke Me Up services. If you did not iniate this request, please ignore this message";
        $apiURL = "m321m6.api.infobip.com";
        $apiKey = "dd03aa5a4305d1ef6693a372a405d9ae-27b5b608-0bc7-45fb-befe-2e4700aae12c";


        $configuration = new Configuration(host: $apiURL, apiKey: $apiKey);
        $api = new SmsApi(config: $configuration);

        $destination = new SmsDestination(to: $phonenum);
        $themessage = new SmsTextualMessage(
            destinations: [$destination],
            text: $message,
            from: "Syntax Flow"
        );

        $requests = new SmsAdvancedTextualRequest(messages: [$themessage]);
        $response = $api->sendSmsMessage($requests);


        try{
            $otp = Confirmation::create([
            'email' => $email,
            'mobile_number' => $phonenum,
            'otp' => $randomNumber,
            'status' => "pending",
            
        ]);
        return response(['message' => 'Otp added successfully', 'otp' => $otp], 201);
        }catch (\Throwable $e) {
            return response(['errors' => $e->getMessage()], 400);
        }
            
    }




    public function sendOtp(UserStoreRequest $request)
{
    $phonenum = $request->mobile_number;

    // Generate a random 4-digit number
    $randomNumber = rand(1000, 9999);

    // Create the message using the random number
    $message = "Hi! Your OTP code is: " . $randomNumber . " You can use this to verify your mobile number to use Pick Me Up services. If you did not initiated this request, please ignore this message.";
    
    // Infobip API configuration (adjust this to your setup)
    $apiURL = "m321m6.api.infobip.com";
    $apiKey = "dd03aa5a4305d1ef6693a372a405d9ae-27b5b608-0bc7-45fb-befe-2e4700aae12c";

    $configuration = new Configuration(host: $apiURL, apiKey: $apiKey);
    $api = new SmsApi(config: $configuration);

    $destination = new SmsDestination(to: $phonenum);
    $themessage = new SmsTextualMessage(
        destinations: [$destination],
        text: $message,
        from: "Syntax Flow"
    );

    $requests = new SmsAdvancedTextualRequest(messages: [$themessage]);

    try {
        $response = $api->sendSmsMessage($requests);
        // Store OTP in database
        Confirmation::create([
            'email' => $request->email,
            'mobile_number' => $phonenum,
            'otp' => $randomNumber,
            'status' => "pending",
        ]);

        return response(['message' => 'OTP sent successfully.'], 201);
    } catch (\Throwable $e) {
        return response(['errors' => $e->getMessage()], 400);
    }
}

// 


// Add this method to your AuthController

public function verifyOtp(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'mobile_number' => 'required',
        'otp' => 'required|numeric'
    ]);

    // Check if OTP exists and is pending
    $confirmation = Confirmation::where('email', $request->email)
                                ->where('mobile_number', $request->mobile_number)
                                ->where('otp', $request->otp)
                                ->where('status', 'pending')
                                ->first();

    if (!$confirmation) {
        return response(['message' => 'Invalid OTP or OTP expired'], 400);
    }

    // Mark OTP as used
    $confirmation->status = 'verified';
    $confirmation->save();

    return response(['message' => 'OTP verified successfully'], 200);
}

public function createUser(UserStoreRequest $request)
{
    // Create the user only if OTP is verified
    $confirmation = Confirmation::where('email', $request->email)
                                ->where('mobile_number', $request->mobile_number)
                                ->where('status', 'verified')
                                ->first();

    if (!$confirmation) {
        return response(['message' => 'Please verify your OTP before creating an account'], 400);
    }

    try {
        User::create($request->all());
        return response(['message' => "Successfully created"], 201);
    } catch (\Throwable $e) {
        \Log::error('User creation failed: ' . $e->getMessage());
        return response(['errors' => $e->getMessage()], 400);
    }
}


}