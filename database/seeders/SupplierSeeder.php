<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $suppliers = [
            ['name' => 'Green Garden Co.', 'contact' => '01711-100100'],
            ['name' => 'Leafy Roots Nursery', 'contact' => '01711-200200'],
            ['name' => 'Plant Park BD', 'contact' => '01711-300300'],
        ];

        foreach ($suppliers as $row) {
            Supplier::updateOrCreate(
                ['name' => $row['name']],
                ['contact' => $row['contact']]
            );
        }
    }
}
