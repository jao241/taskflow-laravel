<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
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
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();

        DB::beginTransaction();

        try {
            $data['remember_token'] = Str::random(10);

            $user = User::create($data);

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
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();

        $this->authorize('update', $user);

        DB::beginTransaction();

        try {
            if (isset($data["password"])) {
                $data["password"] = bcrypt($data["password"]);
            }

            $user->update($data);

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
