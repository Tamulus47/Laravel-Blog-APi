<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\validator;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|string',
            'phone' => 'required|string|unique:users,phone',
            'password' => 'required|string',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $validation->errors(),
            ], 422);
        }

        $user = User::create([
            'name' => $request['name'],
            'phone' => $request['phone'],
            'password' => Hash::make($request['password']),
        ]);

        $token = $user->createToken('access_token')->plainTextToken;

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'phone' => $user->phone,
            'access_token' => $token,
        ], 200);
    }

    public function login(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $validation->errors(),
            ], 422);
        }

        $user = User::where('phone', $request['phone'])->first();

        if (!$user || !Hash::check($request['password'], $user->password)) {
            return response()->json([
                'message' => 'The provided credentials are incorrect.',
            ], 401);
        }

        $token = $user->createToken('access_token')->plainTextToken;

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'phone' => $user->phone,
            'access_token' => $token,
        ], 200);
    }
}
