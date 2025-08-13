<?php

namespace App\Http\Controllers\mobileapp;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\User;

class AuthController extends Controller
{
    
    public function login(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'username' => 'required|username',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid credentials'], 422);
        }

        // Check credentials
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Retrieve authenticated user
        $user = Auth::user();

        // Create token
        $token = $user->createToken('HRMSApp')->accessToken;

        // Return success response with token
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }
}
