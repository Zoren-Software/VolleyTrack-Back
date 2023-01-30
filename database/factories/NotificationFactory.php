<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification>
 */
class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id' => $this->faker->uuid,
            'type' => 'App\Notifications\Training\TrainingNotification',
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => 1,
            'data' => '[]',
        ];
    }

    public function setNotifiableId($notifiableId)
    {
        return $this->state(function() use ($notifiableId) {
            return [
                'notifiable_id' => $notifiableId,
            ];
        });
    }
}
