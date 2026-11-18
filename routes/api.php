<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post("/login", [AuthController::class, "login"]);
Route::post("/register", [AuthController::class, "register"]);

Route::group([
    "middleware" => "auth:sanctum"
], function () {
    Route::post("logout", [AuthController::class, "logout"]);

    Route::get('/me', function (Request $request) {
        return $request->user();
    });

    Route::apiResource("users", UserController::class)->whereUuid("user");

    Route::get("rooms/mine", [RoomController::class, "getRoomByUserId"]);
    Route::apiResource("rooms", RoomController::class)->whereUuid("room");
});
