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
            $token = $request->user()->createToken('Personal Access Token')->plainTextToken;
            
            return response(['token' => $token, 'role' => $request->user()->role_id], 200);
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
            
            return response(['token' => $token, 'role' => $user->role_id], 200);
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
    public function createAccount(UserStoreRequest $request)
{
    $credentials = $request->only(['email', 'mobile_number']);
    try {
        $this->model->create($request->all());
        return response(['message' => "Successfully created"], 201);
    } catch (\Throwable $e) {
        // Log the error message to understand what's going wrong
        \Log::error('User creation failed: ' . $e->getMessage());
        return response(['errors' => $e->getMessage()], 400);
    }
}



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

    public function text(Request $credentials){
        $phonenum = $credentials->only(['mobile_number']);
        $email = $credentials->only(['email']);
        
         // Generate a random 4-digit number
        $randomNumber = rand(1000, 9999);

        // Create the message using the random number
        $message = "Your OTP code is: " . $randomNumber;
        $apiURL = "8gprrd.api.infobip.com";
        $apiKey = "2db44b4c40f78de1ca10449c921a1e48-2d77bd07-7047-4cbe-9ac0-54520fec118e";


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
}