<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function placeOrder(): RedirectResponse
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please sign in to place an order.');
        }

        $cartItems = Cart::with('plant')
            ->where('user_id', auth()->id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.view')->with('error', 'Your cart is empty.');
        }

        $total = $cartItems->reduce(function ($carry, $item) {
            return $carry + ((float) $item->plant->price * (int) $item->quantity);
        }, 0.0);

        $order = DB::transaction(function () use ($cartItems, $total) {
            $order = Order::create([
                'user_id' => auth()->id(),
                'order_date' => now(),
                'status' => 'Pending',
                'total_amount' => $total,
            ]);

            foreach ($cartItems as $item) {
                $plant = $item->plant;
                $qty = (int) $item->quantity;
                $unitPrice = (float) $plant->price;

                OrderItem::create([
                    'order_id' => $order->order_id,
                    'plant_id' => $plant->plant_id,
                    'quantity' => $qty,
                    'unit_price' => $unitPrice,
                ]);

                // decrement stock
                $plant->stock_qty = max(0, (int) $plant->stock_qty - $qty);
                $plant->save();
            }

            Cart::where('user_id', auth()->id())->delete();

            return $order;
        });

        return redirect()->route('order.confirmation', ['orderId' => $order->order_id]);
    }

    public function orderConfirmation(?string $orderId = null)
    {
        $order = null;

        if ($orderId !== null && auth()->check()) {
            $orderModel = Order::with('items.plant')
                ->where('order_id', $orderId)
                ->where('user_id', auth()->id())
                ->first();

            if ($orderModel) {
                $order = [
                    'order_id' => (string) $orderModel->order_id,
                    'date' => $orderModel->order_date?->format('Y-m-d H:i'),
                    'status' => $orderModel->status,
                    'total' => (float) $orderModel->total_amount,
                    'user_name' => $orderModel->user?->name ?? 'there',
                    'items' => $orderModel->items->map(fn (OrderItem $it) => [
                        'id' => (int) $it->plant_id,
                        'name' => $it->plant?->name,
                        'image' => $it->plant?->image,
                        'price' => (float) $it->unit_price,
                        'quantity' => (int) $it->quantity,
                        'row_total' => (float) $it->unit_price * (int) $it->quantity,
                    ])->all(),
                ];
            }
        }

        return view('order-confirmation', [
            'order' => $order,
        ]);
    }

    public function orderHistory()
    {
        $orders = Order::with('items.plant')
            ->where('user_id', auth()->id())
            ->orderByDesc('order_date')
            ->get()
            ->map(function (Order $order) {
                return [
                    'order_id' => (string) $order->order_id,
                    'date' => $order->order_date?->format('Y-m-d H:i'),
                    'status' => $order->status,
                    'total' => (float) $order->total_amount,
                    'items' => $order->items->map(fn (OrderItem $it) => [
                        'id' => (int) $it->plant_id,
                        'name' => $it->plant?->name,
                        'image' => $it->plant?->image,
                        'price' => (float) $it->unit_price,
                        'quantity' => (int) $it->quantity,
                        'row_total' => (float) $it->unit_price * (int) $it->quantity,
                    ])->all(),
                ];
            })
            ->all();

        return view('order-history', [
            'orders' => $orders,
        ]);
    }
}