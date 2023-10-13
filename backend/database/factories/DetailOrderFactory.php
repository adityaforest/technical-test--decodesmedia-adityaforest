<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\PurchaseOrder;
use App\Models\Material;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DetailOrder>
 */
class DetailOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'purchase_order_id' => PurchaseOrder::factory(),
            'material_id' => $this->faker->numberBetween(1,15),
            'amount' => $this->faker->numberBetween(0, 100),
            'received' => 0,            
        ];
    }
}
