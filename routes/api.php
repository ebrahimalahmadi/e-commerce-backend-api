<?php

use App\Http\Controllers\API\V1\Admin\CategoryController;
use App\Http\Controllers\API\V1\Admin\ProductController;
use App\Http\Controllers\API\V1\Admin\ProductImageController;
use App\Http\Controllers\API\V1\Auth\LoginController;
use App\Http\Controllers\API\V1\Auth\LogoutController;
use App\Http\Controllers\API\V1\Auth\ProfileController;
use App\Http\Controllers\API\V1\Auth\RegisterController;
use App\Http\Controllers\API\V1\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//I Use apiPrefix: 'api/v1' in  middleware 


// Authentication Routes
Route::prefix('Auth')->group(function () {
    Route::post('/register', [RegisterController::class, 'register']); // Register a new user
    Route::post('/login', [LoginController::class, 'login']); // Login an existing user
});

Route::middleware('auth:sanctum')->group(function () {

    // //  logout Route
    Route::post('logout', [LogoutController::class, 'logout']);

    // // Profile Routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/update-password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');

    // // User Management Routes
    Route::get('/users', [UserController::class, 'index'])->name('users.index'); // List all users
    Route::post('/users', [UserController::class, 'store'])->name('users.store'); // Create a new user
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show'); // Show a specific user
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update'); // Update a user
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy'); // Delete a user

    // Category Management Routes
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index'); // List all categories
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store'); // Create a new category
    Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show'); // Show a specific category
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update'); // Update a category
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy'); // Delete a category

    // Product Management Routes
    Route::get('/products', [ProductController::class, 'index'])->name('products.index'); // List all products
    Route::post('/products', [ProductController::class, 'store'])->name('products.store'); // Create a new product
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show'); // Show a specific product
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update'); // Update a product
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy'); // Delete a product


    // Product Image Management Routes
    Route::prefix('products/{product}/images')->group(function () {
        Route::get('/', [ProductImageController::class, 'index'])->name('products.images.index'); // List all images for a product
        Route::post('/', [ProductImageController::class, 'store'])->name('products.images.store'); // Add a new image to a product
        Route::get('{image}', [ProductImageController::class, 'show'])->name('products.images.show'); // Show a specific image of a product
        Route::put('{image}', [ProductImageController::class, 'update'])->name('products.images.update'); // Update a specific image of a product
        Route::delete('{image}', [ProductImageController::class, 'destroy'])->name('products.images.destroy'); // Delete a specific image of a product
        Route::patch('{image}/primary', [ProductImageController::class, 'setPrimary'])->name('products.images.setPrimary'); // Set an image as primary
    });
    //  list all products with images
    Route::get('products-with-images', [ProductImageController::class, 'listProductsWithImages'])
        ->name('products.images.products-with-images');
});

route::get('/test', function () {
    return response()->json(['message' => 'Welcome to the API']);
});










// ===================================================
 // الحل الأفضل: استخدم POST مع override لطريقة PUT
 // في Postman:
 // غيّر نوع الطلب إلى POST.
 // في قسم Body > form-data، أرسل كل البيانات كالمعتاد.
 // أضف مفتاح جديد:
 // Key: _method
 // Value: PUT
 // Type: Text
 // Laravel سيتعرف على هذا المفتاح ويعامل الطلب كأنه PUT.
 // ===================================================
