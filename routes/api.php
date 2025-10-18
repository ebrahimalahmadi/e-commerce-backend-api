<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// route for test 
// I use in middleware the apiPrefix: 'api/v1',  for use in all routes
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
// http://e-commerce-backend-api.test/api/v1/test
