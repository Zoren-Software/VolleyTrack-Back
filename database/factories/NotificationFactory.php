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
            'data' => '{"training":{"id":1,"team_id":1,"user_id":1,"name":"Ms. Arvilla Hilpert I TRAINING","description":"At maxime aliquam nulla. In quam dolorum sed. Nihil qui repudiandae velit.","status":1,"date_start":"2024-02-04 01:15:10","date_end":"2024-02-04 03:28:14","deleted_at":null,"created_at":"2024-02-02T23:28:31.000000Z","updated_at":"2024-02-02T23:28:31.000000Z","team":{"id":38,"user_id":1,"name":"Sr. T\u00e9o Josu\u00e9 Guerra TEAM","created_at":"2024-02-02T23:28:30.000000Z","updated_at":"2024-02-02T23:28:30.000000Z","deleted_at":null}},"confirmationTraining":{"id":191,"user_id":3,"player_id":178,"training_id":20,"team_id":38,"status":"pending","presence":0,"created_at":"2024-02-02T23:28:31.000000Z","updated_at":"2024-02-02T23:28:31.000000Z","deleted_at":null},"message":"Notifica\u00e7\u00e3o de Treino"}',
        ];
    }

    public function setNotifiableId($notifiableId)
    {
        return $this->state(function () use ($notifiableId) {
            return [
                'notifiable_id' => $notifiableId,
            ];
        });
    }

    public function setTypeNotification($type)
    {
        if($type == "TrainingNotification") {
            return $this->state(function () {
                return [
                    'type' => 'App\Notifications\Training\TrainingNotification',
                    'data' => '{"training":{"id":1,"team_id":1,"user_id":1,"name":"Ms. Arvilla Hilpert I TRAINING","description":"At maxime aliquam nulla. In quam dolorum sed. Nihil qui repudiandae velit.","status":1,"date_start":"2024-02-04 01:15:10","date_end":"2024-02-04 03:28:14","deleted_at":null,"created_at":"2024-02-02T23:28:31.000000Z","updated_at":"2024-02-02T23:28:31.000000Z","team":{"id":38,"user_id":1,"name":"Sr. T\u00e9o Josu\u00e9 Guerra TEAM","created_at":"2024-02-02T23:28:30.000000Z","updated_at":"2024-02-02T23:28:30.000000Z","deleted_at":null}},"confirmationTraining":{"id":191,"user_id":3,"player_id":178,"training_id":20,"team_id":38,"status":"pending","presence":0,"created_at":"2024-02-02T23:28:31.000000Z","updated_at":"2024-02-02T23:28:31.000000Z","deleted_at":null},"message":"Notifica\u00e7\u00e3o de Treino"}',
                ];
            });
        } elseif($type == "CancelTrainingNotification") {
            return $this->state(function () {
                return [
                    'type' => 'App\Notifications\Training\TrainingNotification',
                    'data' => '{"training":{"id":1,"team_id":1,"user_id":1,"name":"Ms. Arvilla Hilpert I TRAINING","description":"At maxime aliquam nulla. In quam dolorum sed. Nihil qui repudiandae velit.","status":1,"date_start":"2024-02-04 01:15:10","date_end":"2024-02-04 03:28:14","deleted_at":null,"created_at":"2024-02-02T23:28:31.000000Z","updated_at":"2024-02-02T23:28:31.000000Z","team":{"id":38,"user_id":1,"name":"Sr. T\u00e9o Josu\u00e9 Guerra TEAM","created_at":"2024-02-02T23:28:30.000000Z","updated_at":"2024-02-02T23:28:30.000000Z","deleted_at":null}},"confirmationTraining":{"id":191,"user_id":3,"player_id":178,"training_id":20,"team_id":38,"status":"pending","presence":0,"created_at":"2024-02-02T23:28:31.000000Z","updated_at":"2024-02-02T23:28:31.000000Z","deleted_at":null},"message":"Notifica\u00e7\u00e3o de Treino"}',
                ];
            });
        } elseif($type == "ConfirmationTrainingNotification") {
            return $this->state(function () {
                return [
                    'type' => 'App\Notifications\Training\TrainingNotification',
                    'data' => '{"training":{"id":1,"team_id":1,"user_id":1,"name":"Ms. Arvilla Hilpert I TRAINING","description":"At maxime aliquam nulla. In quam dolorum sed. Nihil qui repudiandae velit.","status":1,"date_start":"2024-02-04 01:15:10","date_end":"2024-02-04 03:28:14","deleted_at":null,"created_at":"2024-02-02T23:28:31.000000Z","updated_at":"2024-02-02T23:28:31.000000Z","team":{"id":38,"user_id":1,"name":"Sr. T\u00e9o Josu\u00e9 Guerra TEAM","created_at":"2024-02-02T23:28:30.000000Z","updated_at":"2024-02-02T23:28:30.000000Z","deleted_at":null}},"confirmationTraining":{"id":191,"user_id":3,"player_id":178,"training_id":20,"team_id":38,"status":"pending","presence":0,"created_at":"2024-02-02T23:28:31.000000Z","updated_at":"2024-02-02T23:28:31.000000Z","deleted_at":null},"message":"Notifica\u00e7\u00e3o de Treino"}',
                ];
            });
        }
    }
}
