<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\LoginController;

Route::middleware(["auth:sanctum"])->get("/user", function (Request $request) {
    return $request->user();
});

Route::get("/", function() {
    return response()->json(["message" => "API is running"]);
});

Route::group(["prefix" => "auth"], function() {
    Route::post("/login", [LoginController::class, "login"]);
});

Route::group(["prefix" => "tasks", "middleware" => ["auth:sanctum"]], function() {
    Route::get("/", [TaskController::class, "index"]);
    Route::post("/", [TaskController::class, "store"]);
    Route::get("/{task}", [TaskController::class, "show"]);
    Route::put("/{task}", [TaskController::class, "update"]);
    Route::delete("/{task}", [TaskController::class, "destroy"]);
});

Route::group(["prefix" => "users", "middleware" => ["auth:sanctum"]], function() {
    Route::get("/", [UserController::class, "index"]);
    Route::post("/", [UserController::class, "store"]);
    Route::get("/{user}", [UserController::class, "show"]);
    Route::put("/{user}", [UserController::class, "update"]);
    Route::delete("/{user}", [UserController::class, "destroy"]);
});
