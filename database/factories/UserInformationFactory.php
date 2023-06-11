<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserInformationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'cpf' => $this->faker->numberBetween(10000000000, 99999999999),
            'rg' => $this->faker->numberBetween(100000000, 999999999),
            'phone' => $this->faker->phoneNumber(),
        ];
    }
}
