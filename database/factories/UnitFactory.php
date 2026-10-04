<?php

namespace Database\Factories;

use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

class UnitFactory extends Factory
{
    protected $model = Unit::class;

    public function definition(): array
    {
        $units = [
            ['code' => 'PCS', 'name' => 'Pieces', 'symbol' => 'pcs'],
            ['code' => 'BOX', 'name' => 'Box', 'symbol' => 'box'],
            ['code' => 'KG', 'name' => 'Kilogram', 'symbol' => 'kg'],
            ['code' => 'LTR', 'name' => 'Liter', 'symbol' => 'L'],
            ['code' => 'MTR', 'name' => 'Meter', 'symbol' => 'm'],
        ];

        return $this->faker->randomElement($units);
    }
}
