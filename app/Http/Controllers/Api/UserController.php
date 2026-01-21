<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * @authenticated
     * @response 200 [
     * {
     * "id': 1,
     * "name": "admin",
     * "email": "admin@admin.com",
     * }
     * ]
     */
    public function index()
    {
        $users = User::all();

        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     * @authenticated
     * @response 201 {
     * "id": 1,
     * "name: "João",
     * "email": "joao@example.com",
     * }
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $validatedData = $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8'
            ]);

            $validatedData['remember_token'] = Str::random(10);

            $user = User::create($validatedData);

            DB::commit();
            return response()->json($user, 201);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['message' => 'Failed to create user', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     * @authenticated
     * @response 200 {
     * "id": 1,
     * "name": "João",
     * "email": "joao@example.com",
     * }
     */
    public function show(User $user)
    {
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     * @authenticated
     * @response 200 {
     * "id": 1,
     * "name": "João Vitor",
     * "email": "joao@example.com",
     * }
     */
    public function update(Request $request, User $user)
    {
        DB::beginTransaction();

        try {
            $validatedData = $request->validate([
                'name' => 'sometimes|required|string',
                'email' => 'sometimes|required|email|unique:users,email,',
                'password' => 'sometimes|required|string|min:8',
            ]);

            if (!!$validatedData["password"]) {
                $validatedData["password"] = bcrypt($validatedData["password"]);
            }

            $user->update($validatedData);

            DB::commit();

            return response()->json($user);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['message' => 'Failed to update user', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @authenticated
     * @response 204
     */
    public function destroy(User $user)
    {
        DB::beginTransaction();

        try {
            $user->delete();

            DB::commit();

            return response()->json(['message' => 'User deleted successfully'], 204);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['message' => 'Failed to delete user', 'error' => $e->getMessage()], 500);
        }
    }
}
