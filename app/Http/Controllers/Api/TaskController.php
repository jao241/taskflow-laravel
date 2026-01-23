<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
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
     *
     * @response 200 {
     *  "id": 1,
     *  "title": "Sample Task",
     *  "description": "This is a sample task description.",
     *  "status": "pending",
     *  }
     *
     * @response 500 {
     *  "message": "Failed to create task",
     * }
     *
     * @response 422 {
     *	"message": "The title field is required.",
     *	"errors": {
     *		"title": [
     *			"The title field is required."
     *		],
     *	}
     * }
     *
     * @response 422 {
     *	"message": "The status field is required.",
     *	"errors": {
     *		"status": [
     *			"The status field is required."
     *		],
     *	}
     * }
     *
     * @response 422 {
     *	"message": "The user_id field is required.",
     *	"errors": {
     *		"user_id": [
     *			"The user_id field is required."
     *		],
     *	}
     * }
     */
    public function store(StoreTaskRequest $request)
    {
        $data = $request->validated();

        DB::beginTransaction();

        try {
            $newTask = Task::create($data);
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
    public function show(Task $task)
    {
        return response()->json($task);
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
     *
     * @response 500 {
     *  "message": "Failed to create task",
     * }
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $data = $request->validated();

        DB::beginTransaction();

        try {
            $task->update($data);
            DB::commit();

            return response()->json($task);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(["message" => "Failed to update task", "error" => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @authenticated
     * @response 204 {
     *  "message": "Task deleted successfully",
     * }
     */
    public function destroy(Task $task)
    {
        DB::beginTransaction();

        $task->delete();
        DB::commit();

        return response()->json(["message" => "Task deleted successfully"], 204);
    }
}
