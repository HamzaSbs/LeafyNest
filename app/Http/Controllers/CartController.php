<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Plant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Request $request): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Please sign in to use the cart.'], 401);
        }

        $data = $request->validate([
            'plant_id' => ['required', 'integer', 'exists:plants,plant_id'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $plant = Plant::findOrFail($data['plant_id']);
        $userId = auth()->id();
        $quantity = $data['quantity'] ?? 1;

        $cart = Cart::where('user_id', $userId)
            ->where('plant_id', $plant->plant_id)
            ->first();

        if ($cart) {
            $cart->quantity += $quantity;
            $cart->save();
        } else {
            $cart = Cart::create([
                'user_id' => $userId,
                'plant_id' => $plant->plant_id,
                'quantity' => $quantity,
            ]);
        }

        return response()->json([
            'message' => 'Plant added to cart.',
            'cart' => $this->cartSummary($userId),
        ]);
    }

    public function update(Request $request, int $plantId): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Please sign in to use the cart.'], 401);
        }

        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cart = Cart::where('user_id', auth()->id())
            ->where('plant_id', $plantId)
            ->first();

        if (!$cart) {
            return response()->json(['message' => 'Cart item not found.'], 404);
        }

        $cart->quantity = $data['quantity'];
        $cart->save();

        return response()->json([
            'message' => 'Cart updated.',
            'cart' => $this->cartSummary(auth()->id()),
        ]);
    }

    public function remove(int $plantId): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Please sign in to use the cart.'], 401);
        }

        Cart::where('user_id', auth()->id())
            ->where('plant_id', $plantId)
            ->delete();

        return response()->json([
            'message' => 'Item removed from cart.',
            'cart' => $this->cartSummary(auth()->id()),
        ]);
    }

    public function view()
    {
        $summary = auth()->check()
            ? $this->cartSummary(auth()->id())
            : ['items' => [], 'subtotal' => 0, 'total' => 0];

        return view('cart', [
            'cartItems' => $summary['items'],
            'subtotal' => $summary['subtotal'],
            'total' => $summary['total'],
        ]);
    }

    private function cartSummary(int $userId): array
    {
        $items = Cart::with('plant.category', 'plant.supplier')
            ->where('user_id', $userId)
            ->get()
            ->map(function (Cart $cart) {
                $plant = $cart->plant;
                $price = (float) ($plant?->price ?? 0);
                $rowTotal = $price * (int) $cart->quantity;

                return [
                    'id' => (int) $cart->plant_id,
                    'plant_id' => (int) $cart->plant_id,
                    'name' => $plant?->name,
                    'price' => $price,
                    'image' => $plant?->image,
                    'category' => $plant?->category?->name,
                    'supplier' => $plant?->supplier?->name,
                    'quantity' => (int) $cart->quantity,
                    'row_total' => $rowTotal,
                ];
            })
            ->values()
            ->all();

        $subtotal = array_sum(array_column($items, 'row_total'));

        return [
            'items' => $items,
            'subtotal' => $subtotal,
            'total' => $subtotal,
        ];
    }
}