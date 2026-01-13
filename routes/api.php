<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UserController;

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

Route::group(["prefix" => "users", "middleware" => ["auth:sanctum"]], function() {
    Route::get("/", [UserController::class, "index"]);
    Route::post("/", [UserController::class, "store"]);
    Route::get("/{id}", [UserController::class, "show"]);
    Route::put("/{id}", [UserController::class, "update"]);
    Route::delete("/{id}", [UserController::class, "destroy"]);
});
