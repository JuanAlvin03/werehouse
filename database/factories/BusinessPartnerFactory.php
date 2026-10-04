<?php

namespace Database\Factories;

use App\Models\BusinessPartner;
use Illuminate\Database\Eloquent\Factories\Factory;

class BusinessPartnerFactory extends Factory
{
    protected $model = BusinessPartner::class;

    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->regexify('[A-Z]{2}-[0-9]{5}'),
            'name' => $this->faker->company(),
            'partner_type' => $this->faker->randomElement(['SUPPLIER', 'CUSTOMER', 'BOTH']),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->companyEmail(),
            'address' => $this->faker->address(),
            'is_active' => true,
        ];
    }
}
