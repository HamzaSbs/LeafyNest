<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Plant;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            CategorySeeder::class,
            SupplierSeeder::class,
            PlantSeeder::class,
        ]);
    }
}
