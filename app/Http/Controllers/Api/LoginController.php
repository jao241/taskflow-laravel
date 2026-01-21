<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request) {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            $user = User::where('email', $request->email)
            ->first();

            if (!$user) {
                return response()->json(["message" => "User not found"], 404);
            }

            $correctPassword = Hash::check($request->password, $user->password);

            if (!$correctPassword) {
                return response()->json(["message" => "Invalid credentials"], 401);
            }

            $accessToken = $user->createToken('authToken')->plainTextToken;

            return response()->json(["message" => $accessToken], 200);
        } catch (Exception $error) {
            return response()->json([["message" => "Login failed", "error" => $error->getMessage()]], 500);
        }
    }
}
