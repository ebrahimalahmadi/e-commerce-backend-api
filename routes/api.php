<?php

use App\Http\Controllers\API\V1\Admin\CategoryController;
use App\Http\Controllers\API\V1\Admin\OrderController;
use App\Http\Controllers\API\V1\Admin\OrderItemController;
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

    // // // Profile Routes
    Route::controller(ProfileController::class)->group(function () { {

            Route::get('/profile', 'show')->name('profile.show');
            Route::put('/profile', 'update')->name('profile.update');
            Route::put('/update-password', 'updatePassword')->name('profile.updatePassword');
        }
    });

    // // User Management Routes
    Route::controller(UserController::class)->group(function () { {

            Route::get('/users', 'index')->name('users.index'); // List all users
            Route::post('/users', 'store')->name('users.store'); // Create a new user
            Route::get('/users/{user}', 'show')->name('users.show'); // Show a specific user
            Route::put('/users/{user}', 'update')->name('users.update'); // Update a user
            Route::delete('/users/{user}', 'destroy')->name('users.destroy'); // Delete a user
        }
    });

    // // Category Management Routes
    Route::controller(CategoryController::class)->group(function () { {

            Route::get('/categories', 'index')->name('categories.index'); // List all categories
            Route::post('/categories', 'store')->name('categories.store'); // Create a new category
            Route::get('/categories/{category}', 'show')->name('categories.show'); // Show a specific category
            Route::put('/categories/{category}', 'update')->name('categories.update'); // Update a category
            Route::delete('/categories/{category}', 'destroy')->name('categories.destroy'); // Delete a category
        }
    });

    // // Product Management Routes
    Route::controller(ProductController::class)->group(function () { {

            Route::get('/products', 'index')->name('products.index'); // List all products
            Route::post('/products', 'store')->name('products.store'); // Create a new product
            Route::get('/products/{product}', 'show')->name('products.show'); // Show a specific product
            Route::put('/products/{product}', 'update')->name('products.update'); // Update a product
            Route::delete('/products/{product}', 'destroy')->name('products.destroy'); // Delete a product

            // // Product Image Management Routes
            Route::post('/products/{product}/images', 'uploadImages')->name('products.store'); // Upload images for a product
            Route::delete('/products/{product}/images/delete', 'deleteImages')->name('products.images.destroy'); // Delete images for a product
            Route::patch('/products/{product}/images/{image}/primary', 'setPrimaryImage')->name('products.images.setPrimary'); // Set an image as primary
        }
    });


    // Order Routes
    Route::apiResource('orders', OrderController::class);

    // Order Status Update
    Route::post('orders/{order}/status', [OrderController::class, 'updateStatus']); // Update Order Status

    // User Orders
    Route::get('user/orders', [OrderController::class, 'userOrders']); // User Orders

    // Order Items Routes (متداخلة مع الطلبات)
    Route::prefix('orders/{order}/items')->group(function () {
        Route::post('/', [OrderItemController::class, 'store']); // Add an item to an order
        Route::put('/{item}', [OrderItemController::class, 'update']); // Update an item in an order
        Route::delete('/{item}', [OrderItemController::class, 'destroy']); // Remove an item from an order
    });
});

route::get('/test', function () {
    return response()->json(['message' => 'Welcome to the API']);
});
