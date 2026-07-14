<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PlantController extends Controller
{
    public static function plants(): array
    {
        return [
            ['id'=>1,'name'=>'Monstera Deliciosa','category'=>'Indoor','supplier'=>'GreenGrow','sunlight'=>'Partial','pot_size'=>'Medium','price'=>850,'stock_qty'=>12,'season'=>'All Year','care_instructions'=>'Bright, indirect light. Water weekly.','image'=>'MOnstera Deli.jpg'],
            ['id'=>2,'name'=>'Echeveria Succulent','category'=>'Succulent','supplier'=>'SuccuShop','sunlight'=>'Full','pot_size'=>'Small','price'=>320,'stock_qty'=>3,'season'=>'Summer','care_instructions'=>'Full sun, minimal water.','image'=>'echeveria succul.jpg'],
            ['id'=>3,'name'=>'Peace Lily','category'=>'Indoor','supplier'=>'LeafySupplies','sunlight'=>'Low','pot_size'=>'Medium','price'=>490,'stock_qty'=>8,'season'=>'All Year','care_instructions'=>'Low light, keep soil moist.','image'=>'peace-lily.jpg'],
            ['id'=>4,'name'=>'Fiddle Leaf Fig','category'=>'Indoor','supplier'=>'UrbanTrees','sunlight'=>'Partial','pot_size'=>'Large','price'=>1200,'stock_qty'=>6,'season'=>'Spring','care_instructions'=>'Bright filtered light, moderate watering.','image'=>'fiddle leaf.jpg'],
            ['id'=>5,'name'=>'Snake Plant','category'=>'Indoor','supplier'=>'GreenGrow','sunlight'=>'Low','pot_size'=>'Medium','price'=>620,'stock_qty'=>4,'season'=>'All Year','care_instructions'=>'Tolerates low light, water sparingly.','image'=>'indoor_plants.jpg'],
            ['id'=>6,'name'=>'Outdoor Fern','category'=>'Outdoor','supplier'=>'GardenFresh','sunlight'=>'Partial','pot_size'=>'Large','price'=>750,'stock_qty'=>10,'season'=>'Monsoon','care_instructions'=>'Shade, regular moisture.','image'=>'outdoor_plant.jpg'],
            ['id'=>7,'name'=>'Succulent Mix','category'=>'Succulent','supplier'=>'SuccuShop','sunlight'=>'Full','pot_size'=>'Small','price'=>220,'stock_qty'=>2,'season'=>'Summer','care_instructions'=>'Bright sun, little water.','image'=>'succulents.jpeg'],
            ['id'=>8,'name'=>'Rose Bush','category'=>'Flowering','supplier'=>'BloomFarm','sunlight'=>'Full','pot_size'=>'Large','price'=>980,'stock_qty'=>7,'season'=>'Spring','care_instructions'=>'Full sun, regular feeding.','image'=>'flowering_plants.jpg'],
            ['id'=>9,'name'=>'Herb Pack','category'=>'Herbs','supplier'=>'HerbalCo','sunlight'=>'Full','pot_size'=>'Small','price'=>150,'stock_qty'=>15,'season'=>'All Year','care_instructions'=>'Sunny spot, regular harvesting.','image'=>'herbs.jpg'],
            ['id'=>10,'name'=>'Olive Tree','category'=>'Tree','supplier'=>'TreeHouse','sunlight'=>'Full','pot_size'=>'Large','price'=>2500,'stock_qty'=>1,'season'=>'Spring','care_instructions'=>'Plenty of sun, infrequent deep watering.','image'=>'tree.png'],
            ['id'=>11,'name'=>'Ficus Bonsai','category'=>'Indoor','supplier'=>'BonsaiWorld','sunlight'=>'Partial','pot_size'=>'Small','price'=>1400,'stock_qty'=>5,'season'=>'All Year','care_instructions'=>'Bright indirect light, careful watering.','image'=>'MOnstera Deli.jpg'],
            ['id'=>12,'name'=>'Peaceful Palm','category'=>'Indoor','supplier'=>'Palmers','sunlight'=>'Partial','pot_size'=>'Medium','price'=>900,'stock_qty'=>0,'season'=>'All Year','care_instructions'=>'Indirect light, moderate watering.','image'=>'peace-lily.jpg'],
        ];
    }

    public function index(Request $request)
    {
        $plants = self::plants();
        $wishlist = session('wishlist', []);

        $filtered = array_values(array_filter($plants, function ($p) use ($request) {
            if ($request->filled('category') && strcasecmp($p['category'], $request->query('category')) !== 0) {
                return false;
            }
            if ($request->filled('sunlight') && strcasecmp($p['sunlight'], $request->query('sunlight')) !== 0) {
                return false;
            }
            if ($request->filled('pot_size') && strcasecmp($p['pot_size'], $request->query('pot_size')) !== 0) {
                return false;
            }
            if ($request->filled('season') && strcasecmp($p['season'], $request->query('season')) !== 0) {
                return false;
            }
            if ($request->filled('min_price') && is_numeric($request->query('min_price')) && $p['price'] < (float)$request->query('min_price')) {
                return false;
            }
            if ($request->filled('max_price') && is_numeric($request->query('max_price')) && $p['price'] > (float)$request->query('max_price')) {
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

        // derive filter options from the dataset for the sidebar
        $categories = array_values(array_unique(array_map(fn($x)=>$x['category'],$plants)));
        $sunlights = array_values(array_unique(array_map(fn($x)=>$x['sunlight'],$plants)));
        $potSizes = array_values(array_unique(array_map(fn($x)=>$x['pot_size'],$plants)));
        $seasons = array_values(array_unique(array_map(fn($x)=>$x['season'],$plants)));

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
}
