<?php

namespace Database\Factories;

use App\Models\Warehouse;
use App\Models\WarehouseLocation;
use Illuminate\Database\Eloquent\Factories\Factory;

class WarehouseLocationFactory extends Factory
{
    protected $model = WarehouseLocation::class;

    public function definition(): array
    {
        return [
            'warehouse_id' => Warehouse::factory(),
            'code' => $this->faker->unique()->regexify('[A-Z]{3}-[0-9]{2}'),
            'name' => $this->faker->words(2, true),
            'is_active' => true,
        ];
    }
}
