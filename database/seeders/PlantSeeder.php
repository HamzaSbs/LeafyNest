<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Plant;
use App\Models\Supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlantSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $indoor = Category::where('name', 'Indoor')->firstOrFail();
        $outdoor = Category::where('name', 'Outdoor')->firstOrFail();
        $succulent = Category::where('name', 'Succulent')->firstOrFail();
        $flowering = Category::where('name', 'Flowering')->firstOrFail();
        $air = Category::where('name', 'Air-purifying')->firstOrFail();

        $gg = Supplier::where('name', 'Green Garden Co.')->firstOrFail();
        $lr = Supplier::where('name', 'Leafy Roots Nursery')->firstOrFail();
        $pp = Supplier::where('name', 'Plant Park BD')->firstOrFail();

        $plants = [
            ['Monstera Deliciosa', $indoor->category_id, $gg->supplier_id, 1850.00, 12, 'Bright Indirect', 'Medium', 'Summer', 'monstera.jpg', 'A bold tropical favorite with split leaves.', 'Water when top inch of soil is dry; bright, indirect light.'],
            ['Snake Plant', $indoor->category_id, $lr->supplier_id, 950.00, 4, 'Low Light', 'Small', 'All Season', 'snake-plant.jpg', 'Forgiving air-purifier.', 'Water sparingly; tolerates low light.'],
            ['Peace Lily', $air->category_id, $pp->supplier_id, 1450.00, 6, 'Low Light', 'Medium', 'Spring', 'peace-lily.jpg', 'Elegant white blooms.', 'Keep soil moist; tolerates shade.'],
            ['Aloe Vera', $succulent->category_id, $gg->supplier_id, 650.00, 20, 'Full Sun', 'Small', 'All Season', 'aloe-vera.jpg', 'Healing succulent.', 'Bright light; water sparingly.'],
            ['Rose Plant', $flowering->category_id, $lr->supplier_id, 1250.00, 3, 'Full Sun', 'Medium', 'Spring', 'rose.jpg', 'Classic fragrant rose.', 'Full sun; prune regularly.'],
            ['Tulsi (Holy Basil)', $outdoor->category_id, $pp->supplier_id, 350.00, 25, 'Full Sun', 'Small', 'Summer', 'tulsi.jpg', 'Sacred medicinal herb.', 'Full sun; daily watering.'],
            ['Jade Plant', $succulent->category_id, $gg->supplier_id, 850.00, 5, 'Bright Indirect', 'Small', 'All Season', 'jade.jpg', 'Long-living prosperity symbol.', 'Bright light; water when dry.'],
            ['Areca Palm', $indoor->category_id, $lr->supplier_id, 2450.00, 2, 'Bright Indirect', 'Large', 'All Season', 'areca-palm.jpg', 'Lush tropical indoor palm.', 'Bright, indirect light; keep moist.'],
            ['Spider Plant', $air->category_id, $pp->supplier_id, 750.00, 8, 'Indirect Light', 'Small', 'All Season', 'spider-plant.jpg', 'Easy-care air purifier.', 'Indirect light; water weekly.'],
            ['Money Plant', $indoor->category_id, $gg->supplier_id, 550.00, 15, 'Indirect Light', 'Small', 'All Season', 'money-plant.jpg', 'Lucky indoor climber.', 'Indirect light; water when dry.'],
            ['Lavender', $flowering->category_id, $lr->supplier_id, 1150.00, 7, 'Full Sun', 'Small', 'Spring', 'lavender.jpg', 'Fragrant purple blooms.', 'Full sun; dry between waterings.'],
            ['Bougainvillea', $outdoor->category_id, $pp->supplier_id, 1650.00, 1, 'Full Sun', 'Large', 'Summer', 'bougainvillea.jpg', 'Vivid climber.', 'Full sun; minimal water.'],
        ];

        foreach ($plants as [$name, $categoryId, $supplierId, $price, $stock, $sunlight, $potSize, $season, $image, $description, $care]) {
            Plant::updateOrCreate(
                ['name' => $name],
                [
                    'category_id' => $categoryId,
                    'supplier_id' => $supplierId,
                    'price' => $price,
                    'stock_qty' => $stock,
                    'sunlight' => $sunlight,
                    'pot_size' => $potSize,
                    'season' => $season,
                    'image' => $image,
                    'description' => $description,
                    'care_instructions' => $care,
                ]
            );
        }
    }
}
