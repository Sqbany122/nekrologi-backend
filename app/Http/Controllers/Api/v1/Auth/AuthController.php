<?php

namespace App\Http\Controllers\api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        try {
            if(!Auth::attempt($request->only(['email', 'password']))){
                return response()->json([
                    'status_code' => 422,
                    'message' => trans('messages.invalid_credentials'),
                ], 422);
            }

            $user = User::where('email', $request->email)->first();
            $token = $user->createToken('Api-auth-token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'access_token' => $token
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function register(RegisterRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            $token = $user->createToken('Api-auth-token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'access_token' => $token
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function reset_password(Request $request)
    {

    }
}
