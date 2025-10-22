<?php

use App\Http\Controllers\API\V1\Auth\LoginController;
use App\Http\Controllers\API\V1\Auth\LogoutController;
use App\Http\Controllers\API\V1\Auth\ProfileController;
use App\Http\Controllers\API\V1\Auth\RegisterController;
use App\Http\Controllers\API\V1\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

//I Use apiPrefix: 'api/v1' in  middleware 



// Authentication Routes
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    // // Profile Routes
    Route::post('logout', [LogoutController::class, 'logout']);

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/update-password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');


    // // User Management Routes
    Route::get('/users', [UserController::class, 'index'])->name('users.index'); // List all users
    Route::post('/users', [UserController::class, 'store'])->name('users.store'); // Create a new user
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show'); // Show a specific user
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update'); // Update a user
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy'); // Delete a user
});




// route for test 
Route::get('/test', function (Request $request) {
    return response()->json([
        'status' => 'success',
        'message' => 'Data retrieved successfully',
        'data' => [
            'id' => 1,
            'name' => 'John Doe',
            'email' => 'john.doe@example.com'
        ]
    ]);
});
// http://localhost/api/v1/test
