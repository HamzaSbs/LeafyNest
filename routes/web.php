<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\WishlistController;

Route::get('/', function () {
    return view('index', [
        'wishlist' => session('wishlist', []),
    ]);
});

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

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/cart', [CartController::class, 'view'])->name('cart.view');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/{plantId}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{plantId}', [CartController::class, 'remove'])->name('cart.remove');

Route::get('/wishlist', [WishlistController::class, 'view'])->name('wishlist.view');
Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
