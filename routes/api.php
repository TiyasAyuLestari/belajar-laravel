<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\TransactionController;

// ====================
// PUBLIC ROUTES
// ====================
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


// ====================
// PRIVATE ROUTES (PAKAI TOKEN)
// ====================
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    // CRUD
    Route::apiResource('/categories', CategoryController::class);
    Route::apiResource('/items', ItemController::class);
    Route::apiResource('/transactions', TransactionController::class);

    // USER LOGIN
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

});