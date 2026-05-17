<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition(): array
    {
        return [
            'profile_id' => Profile::factory(),
            'address_line_1' => fake()->streetAddress(),
            'address_line_2' => fake()->secondaryAddress(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'postal_code' => fake()->postcode(),
            'country' => fake()->country(),
            'is_primary' => true,
        ];
    }

    public function primary(): static
    {
        return $this->state(fn () => ['is_primary' => true]);
    }

    public function billing(): static
    {
        return $this->state(fn () => ['type' => 'billing']);
    }

    public function shipping(): static
    {
        return $this->state(fn () => ['type' => 'shipping']);
    }
}
