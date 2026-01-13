<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use Exception;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     * @authenticated
     * @response 200 [
     *  {
     *  "id": 1,
     *  "title": "Sample Task",
     *  "description": "This is a sample task description.",
     *  "status": "pending",
     *  }
     * ]
     */
    public function index()
    {
        $tasks = Task::get();
        return response()->json($tasks);
    }

    /**
     * Store a newly created resource in storage.
     * @authenticated
     * @response 200 {
     *  "id": 1,
     *  "title": "Sample Task",
     *  "description": "This is a sample task description.",
     *  "status": "pending",
     *  }
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $validatedData = $request->validate([
                'title' => 'required|string',
                'description' => 'nullable|string',
                'status' => 'required',
                'user_id' => 'required|exists:users,id',
            ]);

            $newTask = Task::create($validatedData);
            DB::commit();

            return response()->json($newTask, 201);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(["message" => "Failed to create task", "error" => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     * @authenticated
     * @response 200 {
     *  "id": 1,
     *  "title": "Sample Task",
     *  "description": "This is a sample task description.",
     *  "status": "pending",
     *  }
     */
    public function show(string $id)
    {
        $task = Task::find($id);

        if ($task) {
            $this->authorize('view', $task);

            return response()->json($task);
        } else {
            return response()->json(["message" => "Task not found"], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     * @authenticated
     * @response 200 {
     *  "id": 1,
     *  "title": "Sample Task",
     *  "description": "This is a sample task description.",
     *  "status": "pending",
     *  }
     */
    public function update(Request $request, string $id)
    {
        DB::beginTransaction();

        try {
            $task = Task::find($id);

            if ($task) {

                $validatedData = $request->validate([
                    'title' => 'sometimes|required|string',
                    'description' => 'sometimes|nullable|string',
                    'status' => 'sometimes|required',
                ]);

                $task->update($validatedData);
                DB::commit();

                return response()->json($task);
            } else {
                DB::commit();

                return response()->json(["message" => "Task not found"], 404);
            }
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(["message" => "Failed to update task", "error" => $e->getMessage()], 500);
        }

    }

    /**
     * Remove the specified resource from storage.
     * @authenticated
     * @response 204
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();

        $task = Task::find($id);

        if ($task) {
            $task->delete();
            DB::commit();

            return response()->json(["message" => "Task deleted successfully"], 204);
        } else {
            DB::commit();

            return response()->json(["message" => "Task not found"], 404);
        }
    }
}
