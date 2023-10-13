<?php

namespace Database\Seeders;

use App\Models\Stock;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($materialId = 1; $materialId <= 15; $materialId++) {
            Stock::create([
                'material_id' => $materialId,
                'stock' => 0
            ]);
        }
    }
}
