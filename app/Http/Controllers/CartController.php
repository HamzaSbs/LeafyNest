<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $data = $request->validate([
            'plant_id' => ['required', 'integer'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $plant = collect(PlantController::plants())->firstWhere('id', $data['plant_id']);

        if (!$plant) {
            return response()->json(['message' => 'Plant not found.'], 404);
        }

        $cart = session('cart', []);
        $plantId = (string) $plant['id'];
        $quantity = $data['quantity'] ?? 1;

        if (isset($cart[$plantId])) {
            $cart[$plantId]['quantity'] += $quantity;
        } else {
            $cart[$plantId] = [
                'id' => $plant['id'],
                'name' => $plant['name'],
                'price' => $plant['price'],
                'image' => $plant['image'],
                'quantity' => $quantity,
            ];
        }

        session(['cart' => $cart]);

        return response()->json([
            'message' => 'Plant added to cart.',
            'cart' => $this->cartSummary($cart),
        ]);
    }

    public function update(Request $request, int $plantId)
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cart = session('cart', []);
        $key = (string) $plantId;

        if (!isset($cart[$key])) {
            return response()->json(['message' => 'Cart item not found.'], 404);
        }

        $cart[$key]['quantity'] = $data['quantity'];
        session(['cart' => $cart]);

        return response()->json([
            'message' => 'Cart updated.',
            'cart' => $this->cartSummary($cart),
        ]);
    }

    public function remove(int $plantId)
    {
        $cart = session('cart', []);
        unset($cart[(string) $plantId]);
        session(['cart' => $cart]);

        return response()->json([
            'message' => 'Item removed from cart.',
            'cart' => $this->cartSummary($cart),
        ]);
    }

    public function view()
    {
        $cart = $this->cartSummary(session('cart', []));

        return view('cart', [
            'cartItems' => $cart['items'],
            'subtotal' => $cart['subtotal'],
            'total' => $cart['total'],
        ]);
    }

    private function cartSummary(array $cart): array
    {
        $items = array_values(array_map(function ($item) {
            $item['row_total'] = $item['price'] * $item['quantity'];

            return $item;
        }, $cart));

        $subtotal = array_sum(array_column($items, 'row_total'));

        return [
            'items' => $items,
            'subtotal' => $subtotal,
            'total' => $subtotal,
        ];
    }
}
