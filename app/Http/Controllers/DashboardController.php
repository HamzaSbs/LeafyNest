<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $cartItemCount = $user
            ? (int) $user->cartItems()->sum('quantity')
            : 0;

        $wishlistCount = $user
            ? (int) $user->wishlistItems()->count()
            : 0;

        $orders = $user
            ? Order::with('items')
                ->where('user_id', $user->user_id)
                ->orderByDesc('order_date')
                ->orderByDesc('order_id')
                ->get()
                ->map(fn (Order $order) => [
                    'order_id' => (string) $order->order_id,
                    'date' => $order->order_date?->format('Y-m-d H:i'),
                    'status' => $order->status,
                    'total' => (float) $order->total_amount,
                ])
                ->all()
            : [];

        return view('dashboard', [
            'userName' => $user?->name ?? session('customer_name', 'Plant Lover'),
            'cartItemCount' => $cartItemCount,
            'wishlistCount' => $wishlistCount,
            'orders' => $orders,
        ]);
    }
}
