<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

// Route to create a new user (POST request)
// Calls the 'store' method in UserController
Route::post('/users', [UserController::class, 'store']);

// Route to retrieve a list of users (GET request)
// Calls the 'index' method in UserController
Route::get('/users', [UserController::class, 'index']);

// Protected route to get the authenticated user's info
// Only accessible if the request has a valid Sanctum API token
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});