<?php

use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me'])->name('me');

    // Retrieve all users
    Route::get('/users', [UserController::class, 'index'])
        ->can('viewAny', User::class)
        ->name('users.index');

    // Retrieve a specific user
    Route::get('/users/{user}', [UserController::class, 'show'])
        ->can('view', 'user')
        ->name('users.show');

    // Create a user
    Route::post('/users', [UserController::class, 'store'])
        ->can('create', User::class)
        ->name('users.store');

    // Update a user
    Route::put('/users/{user}', [UserController::class, 'update'])
        ->can('update', 'user')
        ->name('users.update');

    // Delete a user
    Route::delete('/users/{user}', [UserController::class, 'destroy'])
        ->can('delete', 'user')
        ->name('users.destroy');

    Route::apiResource('roles', RoleController::class)->except('show');
    Route::apiResource('/posts', PostController::class);

});
