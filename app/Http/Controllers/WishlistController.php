<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function toggle(Request $request)
    {
        $data = $request->validate([
            'plant_id' => ['required', 'integer'],
        ]);

        $plant = collect(PlantController::plants())->firstWhere('id', $data['plant_id']);

        if (!$plant) {
            return response()->json(['message' => 'Plant not found.'], 404);
        }

        $wishlist = session('wishlist', []);
        $plantId = (int) $data['plant_id'];

        if (in_array($plantId, $wishlist, true)) {
            $wishlist = array_values(array_diff($wishlist, [$plantId]));
            $wishlisted = false;
        } else {
            $wishlist[] = $plantId;
            $wishlisted = true;
        }

        session(['wishlist' => $wishlist]);

        return response()->json([
            'message' => $wishlisted ? 'Plant added to wishlist.' : 'Plant removed from wishlist.',
            'wishlisted' => $wishlisted,
            'wishlist' => $wishlist,
        ]);
    }

    public function view()
    {
        $wishlist = session('wishlist', []);
        $plants = array_values(array_filter(PlantController::plants(), function ($plant) use ($wishlist) {
            return in_array($plant['id'], $wishlist, true);
        }));

        return view('wishlist', [
            'plants' => $plants,
            'wishlist' => $wishlist,
        ]);
    }
}
