<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use Illuminate\Http\Request;

class PlantController extends Controller
{
    /**
     * Returns plants as plain associative arrays so existing blade templates
     * (which expect keys like id/name/category/price/image/etc.) keep working.
     */
    public static function plants(): array
    {
        return Plant::with(['category', 'supplier'])
            ->orderBy('plant_id')
            ->get()
            ->map(fn (Plant $p) => self::toArray($p))
            ->all();
    }

    public static function toArray(Plant $p): array
    {
        return [
            'id' => (int) $p->plant_id,
            'name' => $p->name,
            'category' => $p->category?->name,
            'category_id' => (int) $p->category_id,
            'supplier' => $p->supplier?->name,
            'supplier_id' => (int) $p->supplier_id,
            'price' => (float) $p->price,
            'stock_qty' => (int) $p->stock_qty,
            'stock' => (int) $p->stock_qty,
            'description' => $p->description,
            'care_instructions' => $p->care_instructions,
            'image' => $p->image,
            'sunlight' => $p->sunlight,
            'pot_size' => $p->pot_size,
            'season' => $p->season,
        ];
    }

    public function index(Request $request)
    {
        $plants = self::plants();
        $wishlist = [];

        if (auth()->check()) {
            $wishlist = auth()->user()
                ->wishlistItems()
                ->pluck('plant_id')
                ->map(fn ($id) => (int) $id)
                ->all();
        }

        $filtered = array_values(array_filter($plants, function ($p) use ($request) {
            if ($request->filled('category') && strcasecmp($p['category'], $request->query('category')) !== 0) {
                return false;
            }
            if ($request->filled('sunlight') && strcasecmp($p['sunlight'] ?? '', $request->query('sunlight')) !== 0) {
                return false;
            }
            if ($request->filled('pot_size') && strcasecmp($p['pot_size'] ?? '', $request->query('pot_size')) !== 0) {
                return false;
            }
            if ($request->filled('season') && strcasecmp($p['season'] ?? '', $request->query('season')) !== 0) {
                return false;
            }
            if ($request->filled('min_price') && is_numeric($request->query('min_price')) && $p['price'] < (float) $request->query('min_price')) {
                return false;
            }
            if ($request->filled('max_price') && is_numeric($request->query('max_price')) && $p['price'] > (float) $request->query('max_price')) {
                return false;
            }
            if ($request->filled('search')) {
                $search = $request->query('search');
                if (stripos($p['name'], $search) === false) {
                    return false;
                }
            }
            return true;
        }));

        $categories = array_values(array_unique(array_filter(array_map(fn ($x) => $x['category'] ?? null, $plants))));
        $sunlights = array_values(array_unique(array_filter(array_map(fn ($x) => $x['sunlight'] ?? null, $plants))));
        $potSizes = array_values(array_unique(array_filter(array_map(fn ($x) => $x['pot_size'] ?? null, $plants))));
        $seasons = array_values(array_unique(array_filter(array_map(fn ($x) => $x['season'] ?? null, $plants))));

        return view('browse', [
            'plants' => $filtered,
            'filters' => $request->all(),
            'categories' => $categories,
            'sunlights' => $sunlights,
            'potSizes' => $potSizes,
            'seasons' => $seasons,
            'wishlist' => $wishlist,
        ]);
    }

    /**
     * Returns the canonical filter option lists derived from every plant in the DB.
     * Used by both the public browse page and the admin Add/Edit Plant form so the
     * admin dropdowns always match the browse-page dropdowns exactly.
     */
    public static function filterOptions(): array
    {
        $plants = self::plants();

        return [
            'categories' => array_values(array_unique(array_filter(array_map(fn ($x) => $x['category'] ?? null, $plants)))),
            'sunlights'  => array_values(array_unique(array_filter(array_map(fn ($x) => $x['sunlight'] ?? null, $plants)))),
            'potSizes'   => array_values(array_unique(array_filter(array_map(fn ($x) => $x['pot_size'] ?? null, $plants)))),
            'seasons'    => array_values(array_unique(array_filter(array_map(fn ($x) => $x['season'] ?? null, $plants)))),
        ];
    }
}
