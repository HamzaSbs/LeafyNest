<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function placeOrder()
    {
        $cartItems = session('cart', []);

        if (count($cartItems) === 0) {
            return redirect()->route('cart.view');
        }

        $items = array_values(array_map(function ($item) {
            $quantity = (int) ($item['quantity'] ?? 1);
            $price = (float) ($item['price'] ?? 0);
            $item['quantity'] = $quantity;
            $item['price'] = $price;
            $item['row_total'] = $price * $quantity;

            return $item;
        }, $cartItems));

        $total = array_sum(array_column($items, 'row_total'));
        $orders = session('orders', []);
        $order = [
            'order_id' => uniqid('LN-'),
            'user_name' => Auth::user()?->name ?? session('customer_name', 'Plant Lover'),
            'date' => now()->format('Y-m-d H:i'),
            'status' => 'Pending',
            'items' => $items,
            'total' => $total,
        ];

        $orders[] = $order;

        session([
            'orders' => $orders,
            'last_order' => $order,
        ]);
        session()->forget('cart');

        return redirect()->route('order.confirmation');
    }

    public function orderHistory()
    {
        return view('order-history', [
            'orders' => session('orders', []),
        ]);
    }
}
