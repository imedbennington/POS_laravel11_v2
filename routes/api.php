<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\WaiterController;
use App\Http\Controllers\Provider2Controller;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SettingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::apiResource('waiters', WaiterController::class);
Route::apiResource('admins', AdminController::class);
Route::apiResource('providers', Provider2Controller::class);
Route::apiResource('products', ProductController::class);

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
