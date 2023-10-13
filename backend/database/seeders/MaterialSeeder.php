<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Material;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define an array of real material names
        $materialNames = [
            'Brick',
            'Concrete',
            'Wood',
            'Steel',
            'Aluminum',            
            'Ceramic',            
            'Stone',
            'Granite',
            'Marble',            
            'Vinyl',
            'Rubber',
            'Clay',
            'Porcelain',                                   
            'Cement',
            'Gypsum'          
        ];

        // Create and seed records for each material name
        foreach ($materialNames as $materialName) {
            Material::create([
                'name' => $materialName,
                // Add other fields and their values here
            ]);
        }
    }
}
