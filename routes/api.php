<?php

use App\Http\Controllers\Api\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(["auth:sanctum"])->get("/user", function (Request $request) {
    return $request->user();
});

Route::get("/", function() {
    return response()->json(["message" => "API is running"]);
});

Route::group(["prefix" => "tasks", "middleware" => ["auth:sanctum"]], function() {
    Route::get("/", [TaskController::class, "index"]);
    Route::post("/", [TaskController::class, "store"]);
    Route::get("/{id}", [TaskController::class, "show"]);
    Route::put("/{id}", [TaskController::class, "update"]);
    Route::delete("/{id}", [TaskController::class, "destroy"]);
});
