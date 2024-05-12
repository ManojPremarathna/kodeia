<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // register user and return token
    public function register(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'username' => 'required|max:255|unique:users,username',
                'password' => 'required|string|min:8'
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Data not valid',
                'errors'  => $validate->errors()
            ], 401);
        }

        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'User Created Successfully',
            'data'    => ['token' => $user->createToken("API TOKEN")->plainTextToken]
        ], 200);
    }

    // login user and retun token
    public function login(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'username' => 'required',
                'password' => 'required'
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation error',
                'errors'  => $validate->errors()
            ], 401);
        }

        if (!Auth::attempt($request->only(['username', 'password']))) {
            return response()->json([
                'status'  => false,
                'message' => 'Fail to authidicate',
                'errors'  => 'Username & Password does not match with our record.'
            ], 401);
        }

        $user = User::where('username', $request->username)->first();

        return response()->json([
            'status'  => true,
            'message' => 'User Logged in Successfully',
            'data'    => ['token' => $user->createToken("API TOKEN")->plainTextToken]
        ], 200);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Logged out Successfully',
            'data'    => []
        ], 200);
    }

}