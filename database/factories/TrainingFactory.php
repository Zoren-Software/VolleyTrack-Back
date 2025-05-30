<?php

namespace Database\Factories;

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
            'user_id' => 1,
            'team_id' => 1,
            'status' => true,
            'description' => $this->faker->text,
            'date_start' => $dateStart,
            'date_end' => $dateEnd,
        ];
    }

    /**
     * @param int $teamId
     * 
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
     * @param bool $status
     * 
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
