<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OptionGroupController;
use App\Http\Controllers\Api\OptionController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\SellerRequestController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

//PRODUCTS
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/products/seller/{sellerId}', [ProductController::class, 'productsBySeller']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/products', [ProductController::class, 'store'])->middleware('role:seller');
    Route::post('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);
});

//CART
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/cart', [CartController::class, 'getCart']);
    Route::post('/cart/add', [CartController::class, 'addToCart']);
    Route::put('/cart/update/{id}', [CartController::class, 'updateCartItem']);
    Route::delete('/cart/remove/{id}', [CartController::class, 'removeFromCart']);
    Route::delete('/cart/clear', [CartController::class, 'clearCart']);
});

//OPTION GROUPS - CRUD
Route::post('/option-group', [OptionGroupController::class, 'createOptionGroup']);
Route::get('/option-group/{groupId}', [OptionGroupController::class, 'index']);
Route::put('/option-group/{groupId}', [OptionGroupController::class, 'update']);
Route::delete('/option-group/{groupId}', [OptionGroupController::class, 'destroy']);

//OPTIONS - CRUD
Route::post('/option', [OptionController::class, 'store']);
Route::put('/option/{id}', [OptionController::class, 'update']);
Route::delete('/option/{id}', [OptionController::class, 'destroy']);

Route::middleware('auth:sanctum')->post('/checkout', [CheckoutController::class, 'checkout']);
Route::middleware('auth:sanctum')->get('/seller/orders', [OrderController::class, 'sellerOrderHistory']);

//Payment Simulation
Route::post('/checkout/pay', [PaymentController::class, 'pay']);
Route::post('/pickup/verify', [PaymentController::class, 'verifyPickupCode']);


//User to Seller
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/seller/apply', [SellerRequestController::class, 'apply']);

    //admin only
    Route::get('/seller/requests', [SellerRequestController::class, 'index'])->middleware('role:admin');
    Route::post('/seller/requests/{id}/approve', [SellerRequestController::class, 'approve'])->middleware('role:admin');
    Route::post('/seller/requests/{id}/reject', [SellerRequestController::class, 'reject'])->middleware('role:admin');
});
