<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::get();
        return response()->json($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $newTask = Task::create($request->all());
            DB::commit();

            return response()->json($newTask, 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(["message" => "Failed to create task", "error" => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
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
     */
    public function update(Request $request, string $id)
    {
        DB::beginTransaction();

        try {
            $task = Task::find($id);

            if ($task) {
                $task->update($request->all());
                DB::commit();

                return response()->json($task);
            } else {
                DB::commit();

                return response()->json(["message" => "Task not found"], 404);
            }
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(["message" => "Failed to update task", "error" => $e->getMessage()], 500);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();

        try {
            $task = Task::find($id);

            if ($task) {
                $task->delete();
                DB::commit();

                return response()->json(["message" => "Task deleted successfully"], 204);
            } else {
                DB::commit();

                return response()->json(["message" => "Task not found"], 404);
            }
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(["message" => "Failed to delete task", "error" => $e->getMessage()], 500);
        }
    }
}
