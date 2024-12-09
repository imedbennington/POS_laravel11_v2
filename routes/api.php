<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\WaiterController;
use App\Http\Controllers\Provider2Controller;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\FoodDrinkController;
use App\Http\Controllers\FoodOrderController;
use App\Http\Controllers\RatingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::apiResource('waiters', WaiterController::class);
Route::apiResource('admins', AdminController::class);
Route::apiResource('providers', Provider2Controller::class);
Route::apiResource('products', ProductController::class);
//only admin and waiters -- middleware
Route::apiResource('drinks_food', FoodDrinkController::class);
//only clients -- middlware
Route::apiResource('food-orders', FoodOrderController::class);
//Route::apiResource('tables', TableController::class);
Route::post('/tables/store', [TableController::class, 'store'])->name('table.store');

/*
// Routes for admins
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('admin/tables', [TableController::class, 'index']);
    Route::post('admin/tables', [TableController::class, 'store']);
    Route::put('admin/tables/{id}', [TableController::class, 'update']);
});

// Routes for waiters
Route::middleware(['auth', 'role:waiter'])->group(function () {
    Route::get('waiter/tables', [TableController::class, 'index']);
    Route::post('waiter/tables', [TableController::class, 'store']);
    Route::put('waiter/tables/{id}', [TableController::class, 'update']);
});
*/
Route::get('/settings', [SettingController::class, 'edit'])->name('admin.settings.edit');
Route::post('/settings/update', [SettingController::class, 'update'])->name('admin.settings.update');
Route::post('/settings/store', [SettingController::class, 'store'])->name('admin.settings.store');
/*
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('admin/settings', [SettingController::class, 'edit'])->name('admin.settings.edit');
    Route::post('admin/settings', [SettingController::class, 'update'])->name('admin.settings.update');
});
*/
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('logout');
Route::post('/reserve', [ReservationController::class,'makeReservation'])->name('reserve');

/*
Route::middleware(['auth:admin'])->group(function () {
    Route::post('/order', [ProductController::class, 'order']);
});*/
Route::get('orders', [AdminController::class, 'getAllOrders'])->name('orders.getAll');
Route::post('orders', [AdminController::class, 'placeOrder'])->name('placeOrder');
Route::post('orders/{orderId}/cancel', [AdminController::class, 'cancelOrder'])->name('cancelOrder');
Route::post('/order', [ProductController::class, 'order']);


//*************ratings******************* */
Route::apiResource('ratings', RatingController::class);
Route::post('/rate/{id}', [RatingController::class, 'store']);

Route::middleware(['auth'])->group(function () {
    Route::resource('tables', TableController::class);
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::post('tables', [TableController::class, 'store']);  // Apply middleware only for store method
});
