<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Training>
 */
class TrainingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dateStart = $this->faker
            ->dateTimeBetween('now', '+2 days')
            ->format('Y-m-d H:i:s');

        $dateEnd = $this->faker
            ->dateTimeBetween($dateStart . ' +2 hours', $dateStart . ' +3 hours')
            ->format('Y-m-d H:i:s');

        return [
            'name' => $this->faker->city . ' TRAINING',
            'user_id' => User::factory(),
            'team_id' => Team::factory(),
            'status' => true,
            'description' => $this->faker->text,
            'date_start' => $dateStart,
            'date_end' => $dateEnd,
        ];
    }

    /**
     * @return Factory<\App\Models\Training>
     */
    public function setTeamId(int $teamId): Factory
    {
        return $this->state(function () use ($teamId) {
            return [
                'team_id' => $teamId,
            ];
        });
    }

    /**
     * @return Factory<\App\Models\Training>
     */
    public function setStatus(bool $status): Factory
    {
        return $this->state(function () use ($status) {
            return [
                'status' => $status,
            ];
        });
    }
}
