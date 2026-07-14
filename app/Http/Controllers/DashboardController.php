<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $cartItems = session('cart', []);
        $wishlist = session('wishlist', []);
        $orders = session('orders', []);
        $user = Auth::user();

        $cartItemCount = array_sum(array_map(function ($item) {
            return (int) ($item['quantity'] ?? 1);
        }, $cartItems));

        return view('dashboard', [
            'userName' => $user?->name ?? session('customer_name', 'Plant Lover'),
            'cartItemCount' => $cartItemCount,
            'wishlistCount' => count($wishlist),
            'orders' => $orders,
        ]);
    }
}
