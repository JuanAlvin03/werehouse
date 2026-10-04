<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'sku' => $this->faker->unique()->regexify('[A-Z]{3}-[0-9]{5}'),
            'name' => $this->faker->words(3, true),
            'category_id' => ProductCategory::factory(),
            'unit_id' => Unit::factory(),
            'barcode' => $this->faker->unique()->ean13(),
            'description' => $this->faker->sentence(),
            'is_active' => true,
        ];
    }
}
