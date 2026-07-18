<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use App\Models\Wishlist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function toggle(Request $request): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Please sign in to use the wishlist.'], 401);
        }

        $data = $request->validate([
            'plant_id' => ['required', 'integer', 'exists:plants,plant_id'],
        ]);

        $userId = auth()->id();
        $plantId = (int) $data['plant_id'];

        $existing = Wishlist::where('user_id', $userId)
            ->where('plant_id', $plantId)
            ->first();

        if ($existing) {
            $existing->delete();
            $wishlisted = false;
        } else {
            Wishlist::create([
                'user_id' => $userId,
                'plant_id' => $plantId,
                'added_at' => now(),
            ]);
            $wishlisted = true;
        }

        $ids = Wishlist::where('user_id', $userId)->pluck('plant_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        return response()->json([
            'message' => $wishlisted ? 'Plant added to wishlist.' : 'Plant removed from wishlist.',
            'wishlisted' => $wishlisted,
            'wishlist' => $ids,
        ]);
    }

    public function view()
    {
        $ids = [];
        if (auth()->check()) {
            $ids = auth()->user()->wishlistItems()->pluck('plant_id')
                ->map(fn ($id) => (int) $id)
                ->all();
        }

        $plants = Plant::with(['category', 'supplier'])
            ->whereIn('plant_id', $ids)
            ->get()
            ->map(fn (Plant $p) => PlantController::toArray($p))
            ->all();

        return view('wishlist', [
            'plants' => $plants,
            'wishlist' => $ids,
        ]);
    }
}