<?php

namespace Database\Factories;

use App\Models\NotificationSetting;
use App\Models\NotificationType;
use App\Models\Position;
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
        // NOTE - Verificar manualmente se o email faker gerado é único, se não for, gerar outro
        $email = $this->faker->unique()->safeEmail();
        while (User::where('email', $email)->exists()) {
            $email = $this->faker->unique()->safeEmail();
        }

        return [
            'name' => $this->faker->name(),
            'email' => $email,
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'set_password_token' => Str::random(60),
            'remember_token' => Str::random(10),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            // Cria o UserInformation
            UserInformation::factory()->create(['user_id' => $user->id]);

            // NOTE Cria os NotificationSettings padrões para o usuário
            // Deve ser igual ao que está no UserObserver
            // e no NotificationSettingsSeeder que deve apenas executar uma vez no deploy
            $types = NotificationType::where('is_active', true)->get();

            $positions = Position::first();
            $user->positions()->attach($positions);

            foreach ($types as $type) {
                if ($type->id != null) {
                    NotificationSetting::updateOrCreate(
                        [
                            'user_id' => $user->id,
                            'notification_type_id' => $type->id,
                        ],
                        [
                            'via_email' => false,
                            'via_system' => $type->allow_system,
                            'is_active' => true,
                        ]
                    );
                } 
            }
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
