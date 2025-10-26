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
    // Route::get('/product-images', [ProductImageController::class, 'index'])->name('product-images.index'); // List all product images
    // Route::post('/product-images', [ProductImageController::class, 'store'])->name('product-images.store'); // Create a new product image
    // Route::get('/product-images/{product_image}', [ProductImageController::class, 'show'])->name('product-images.show'); // Show a specific product image
    // Route::put('/product-images/{product_image}', [ProductImageController::class, 'update'])->name('product-images.update'); // Update a product image
    // Route::delete('/product-images/{product_image}', [ProductImageController::class, 'destroy'])->name('product-images.destroy'); // Delete a product image


    // // Product Image Management Routes
    // Route::prefix('products/{product}')->group(function () {
    //     Route::get('/images', [ProductImageController::class, 'index'])->name('product-images.index'); // List all images for a product
    //     // the url will be like this : /api/v1/products/1/images
    //     // http://e-commerce-backend-api.test/api/v1/products/1/images
    //     Route::post('/images', [ProductImageController::class, 'store'])->name('product-images.store'); // Add a new image to a product
    //     // the url will be like this : /api/v1/products/1/images
    //     // http://e-commerce-backend-api.test/api/v1/products/1/images
    //     Route::get('/images/{image}', [ProductImageController::class, 'show'])->name('product-images.show'); // Show a specific image of a product
    //     Route::put('/images/{image}', [ProductImageController::class, 'update'])->name('product-images.update'); // Update a specific image of a product
    //     Route::delete('/images/{image}', [ProductImageController::class, 'destroy'])->name('product-images.destroy'); // Delete a specific image of a product
    //     Route::patch('images/{image}/primary', [ProductImageController::class, 'setPrimary'])->name('product-images.setPrimary'); // Set an image as primary

    // });

    // Product Image Management Routes
    // Route::prefix('products/{product}')->group(function () {
    //     Route::get('/images', [ProductImageController::class, 'index'])->name('product-images.index'); // List all images for a product
    //     Route::post('/images', [ProductImageController::class, 'store'])->name('product-images.store'); // Add a new image to a product
    //     Route::get('/images/{image}', [ProductImageController::class, 'show'])->name('product-images.show'); // Show a specific image of a product
    //     Route::put('/images/{image}', [ProductImageController::class, 'update'])->name('product-images.update'); // Update a specific image of a product
    //     Route::delete('/images/{image}', [ProductImageController::class, 'destroy'])->name('product-images.destroy'); // Delete a specific image of a product
    //     Route::patch('images/{image}/primary', [ProductImageController::class, 'setPrimary'])->name('product-images.setPrimary'); // Set an image as primary

    // });

    // Route::prefix('products/{product}')->group(function () {
    //     Route::get('images', [ProductImageController::class, 'index'])->name('product-images.index'); // عرض كل الصور
    //     Route::post('images', [ProductImageController::class, 'store'])->name('product-images.store'); // رفع صورة أو أكثر
    //     Route::get('images/{image}', [ProductImageController::class, 'show'])->name('product-images.show'); // عرض صورة واحدة
    //     Route::put('images/{image}', [ProductImageController::class, 'update'])->name('product-images.update'); // تعديل صورة
    //     Route::delete('images/{image}', [ProductImageController::class, 'destroy'])->name('product-images.destroy'); // حذف صورة
    //     Route::patch('images/{image}/primary', [ProductImageController::class, 'setPrimary'])->name('product-images.setPrimary'); // تعيين صورة رئيسية
    // });

    // Final Product Image Management Routes
    // Route::prefix('products/{product}')->group(function () {
    //     Route::get('images', [ProductImageController::class, 'index'])->name('product-images.index'); // عرض كل الصور
    //     Route::post('images', [ProductImageController::class, 'store'])->name('product-images.store'); // رفع صورة أو أكثر
    //     Route::get('images/{image}', [ProductImageController::class, 'show'])->name('product-images.show'); // عرض صورة واحدة
    //     Route::put('images/{image}', [ProductImageController::class, 'update'])->name('product-images.update'); // تعديل صورة
    //     Route::delete('images/{image}', [ProductImageController::class, 'destroy'])->name('product-images.destroy'); // حذف صورة
    //     Route::patch('images/{image}/primary', [ProductImageController::class, 'setPrimary'])->name('product-images.setPrimary'); // تعيين صورة رئيسية
    // });
    // Final Product Image Management Routes



    // ✅ راوت ثابت خارج مجموعة {product}
    Route::get('products-with-images', [ProductImageController::class, 'listProductsWithImages'])->name('product-images.products-with-images');

    // ✅ مجموعة خاصة بالمنتج الواحد
    Route::prefix('products/{product}')->group(function () {
        Route::get('images', [ProductImageController::class, 'index'])->name('product-images.index');
        Route::post('images', [ProductImageController::class, 'store'])->name('product-images.store');
        Route::get('images/{image}', [ProductImageController::class, 'show'])->name('product-images.show');
        Route::put('images/{image}', [ProductImageController::class, 'update'])->name('product-images.update');
        Route::delete('images/{image}', [ProductImageController::class, 'destroy'])->name('product-images.destroy');
        Route::patch('images/{image}/primary', [ProductImageController::class, 'setPrimary'])->name('product-images.setPrimary');
    });
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
