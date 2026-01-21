<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\LoginController;

// Resources routes
Route::apiResource("tasks", TaskController::class)->middleware("auth:sanctum");
Route::apiResource("users", UserController::class)->middleware("auth:sanctum");

// Individual routes
Route::get("/", function() {
    return response()->json(["message" => "API is running"]);
});

// Group routes
Route::group(["prefix" => "auth"], function() {
    Route::post("/login", [LoginController::class, "login"]);
});