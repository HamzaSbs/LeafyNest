<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\WishlistController;
use App\Http\Middleware\AdminMiddleware;

Route::get('/', function () {
    return view('index', [
        'wishlist' => session('wishlist', []),
    ]);
})->name('home');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::get('/register', function () {
    return view('signup');
})->name('register');

Route::post('/register', [RegisteredUserController::class, 'store']);

Route::get('/browse', [PlantController::class, 'index'])->name('browse');
Route::get('/plants', [PlantController::class, 'index'])->name('plants');
Route::get('/plants-browse', [PlantController::class, 'guestIndex'])->name('guest.browse');

Route::middleware('auth')->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/cart', [CartController::class, 'view'])->name('cart.view');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/{plantId}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{plantId}', [CartController::class, 'remove'])->name('cart.remove');

Route::get('/wishlist', [WishlistController::class, 'view'])->name('wishlist.view');
Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

Route::post('/order/place', [OrderController::class, 'placeOrder'])->name('order.place');
Route::get('/order-confirmation/{orderId?}', [OrderController::class, 'orderConfirmation'])->name('order.confirmation');
Route::get('/order-history', [OrderController::class, 'orderHistory'])->name('order.history');

Route::get('/admin/login', [AdminController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.submit');

Route::middleware([AdminMiddleware::class])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard.alt');
    Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');

    Route::get('/plants', [AdminController::class, 'plantsIndex'])->name('admin.plants.index');
    Route::get('/plants/create', [AdminController::class, 'plantsCreate'])->name('admin.plants.create');
    Route::post('/plants', [AdminController::class, 'plantsStore'])->name('admin.plants.store');
    Route::get('/plants/{id}/edit', [AdminController::class, 'plantsEdit'])->name('admin.plants.edit');
    Route::put('/plants/{id}', [AdminController::class, 'plantsUpdate'])->name('admin.plants.update');
    Route::delete('/plants/{id}', [AdminController::class, 'plantsDestroy'])->name('admin.plants.destroy');
    Route::post('/plants/{id}/stock', [AdminController::class, 'plantsUpdateStock'])->name('admin.plants.update-stock');

    Route::get('/categories', [CategoryController::class, 'index'])->name('admin.categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');

    Route::get('/suppliers', [SupplierController::class, 'index'])->name('admin.suppliers.index');
    Route::post('/suppliers', [SupplierController::class, 'store'])->name('admin.suppliers.store');
    Route::put('/suppliers/{id}', [SupplierController::class, 'update'])->name('admin.suppliers.update');
    Route::delete('/suppliers/{id}', [SupplierController::class, 'destroy'])->name('admin.suppliers.destroy');

    Route::get('/orders', [AdminController::class, 'ordersIndex'])->name('admin.orders.index');
    Route::post('/orders/{orderId}/status', [AdminController::class, 'ordersUpdateStatus'])->name('admin.orders.update-status');
    Route::delete('/orders/{orderId}', [AdminController::class, 'ordersDestroy'])->name('admin.orders.destroy');

    Route::get('/low-stock', [AdminController::class, 'lowStockIndex'])->name('admin.low-stock');
});
