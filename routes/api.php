<?php

use App\Http\Controllers\API\V1\Auth\LoginController;
use App\Http\Controllers\API\V1\Auth\LogoutController;
use App\Http\Controllers\API\V1\Auth\MeController;
use App\Http\Controllers\API\V1\Auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//I Use apiPrefix: 'api/v1' in  middleware 
//This means that the middleware will be applied for all routes implies that all API endpoints
//will be accessible under the /api/v1 path. 



// Authentication Routes
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);
// Route::post('/logout', [LogoutController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {

    // // Profile Routes
    Route::post('logout', [LogoutController::class, 'logout']);
    Route::get('/me', [MeController::class, 'me']);
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
