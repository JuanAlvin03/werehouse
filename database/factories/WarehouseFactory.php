<?php

namespace Database\Factories;

use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

class WarehouseFactory extends Factory
{
    protected $model = Warehouse::class;

    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->regexify('WH-[A-Z]{3}'),
            'name' => $this->faker->city() . ' Warehouse',
            'address' => $this->faker->address(),
            'is_active' => true,
        ];
    }
}
