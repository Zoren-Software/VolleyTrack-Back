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
    public function definition()
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
            'description' => $this->faker->text,
            'date_start' => $dateStart,
            'date_end' => $dateEnd,
        ];
    }
}
