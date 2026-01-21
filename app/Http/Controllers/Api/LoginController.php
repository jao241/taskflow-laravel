<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(LoginRequest $request) {
        $data = $request->validated();

        $user = User::where('email', $data['email'])
        ->first();

        if (!$user) {
            return response()->json(["message" => "User not found"], 404);
        }

        $correctPassword = Hash::check($data['password'], $user->password);

        if (!$correctPassword) {
            return response()->json(["message" => "Invalid credentials"], 401);
        }

        $accessToken = $user->createToken('authToken')->plainTextToken;

        return response()->json(["message" => $accessToken], 200);
    }
}
