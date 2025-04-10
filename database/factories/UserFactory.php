<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserInformation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        //NOTE - Verificar manualmente se o email faker gerado é único, se não for, gerar outro
        $email = $this->faker->unique()->safeEmail('users', 'email');
        while (User::where('email', $email)->exists()) {
            $email = $this->faker->unique()->safeEmail('users', 'email');
        }

        return [
            'name' => $this->faker->name(),
            'email' => $email,
            'email_verified_at' => null,
            'password' => Hash::make('password'),
            'set_password_token' => Str::random(60),
            'remember_token' => Str::random(10),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            UserInformation::factory()->create(['user_id' => $user->id]);
        });
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
