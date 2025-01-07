<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try{
            $validator = Validator::make($request->all(),[
                'name' => 'required|string|max:255',
                'email' => 'email|required|max:255|unique:users',
                'password' => 'required|min:6|string',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation Error',
                    'errors' => $validator->errors(),
                ], 401);
            }
    
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);
    
            // $token = $user->createToken('auth_token')->plainTextToken;
    
            return response()->json([
                'status' => true,
                'message' => 'User created successfully',
                'token' => $user->createToken('auth_token')->plainTextToken,
            ],201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try{
            $validator = Validator::make($request->all(),[
                'email' => 'email|required|string',
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation Error',
                    'errors' => $validator->errors(),
                ], 401);
            }

            if (!Auth::attempt(($request->only(['email','password'])))) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            return response()->json([
                'status' => true,
                'message' => 'Login Success',
                'token' => $user->createToken('auth_token')->plainTextToken,
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);

        }
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logout Success',
            'data' => [],
        ],200);
    }
}
