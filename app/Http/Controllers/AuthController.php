<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    // POST /api/auth/register
    public function register(Request $request){
        $validated=$request->validate([
            'name' => 'required|string|max:191',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6'
        ]);

        $user=User::create([
            'name'=> $validated['name'],
            'email'=>$validated['email'],
            'password'=>bcrypt($validated['password']),
            'role'=>'user',
            'solde'=>0,
        ]);

        $token=auth('api')->login($user);

        return response()->json([
            'message' => 'User registered successfully',
            'token'   => $token,
            'user'    => $user,
        ], 201);
    }

    public function login(Request $request){
        $credentials=$request->validate([
            'email'=>'required|email',
            'password'=>'required|string',
        ]);
        if (!$token = auth('api')->attempt($credentials)){
            return response()->json([
                'message' => 'Invalid email or password',
            ], 401);
        }
        return response()->json([
            'message' => 'Login successful',
            'token'   => $token,
            'user'    => auth('api')->user(),
        ], 200);
    }

    public function me()
    {
        return response()->json([
            'data'    => auth('api')->user(),
            'message' => 'Profile retrieved successfully',
        ], 200);
    }

    public function logout()
    {
        auth('api')->logout();
        return response()->json([
            'message' => 'Logged out successfully',
        ], 200);
    }
}
