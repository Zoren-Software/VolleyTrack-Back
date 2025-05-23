<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Team>
 */
class TeamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name() . ' TEAM',
            'user_id' => User::first()->id,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Team $team) {
            $team->players()->syncWithPivotValues(
                User::factory()->count(10)->create()->pluck('id'),
                ['role' => 'player']
            );

            $team->technicians()->syncWithPivotValues(
                User::factory()->count(2)->create()->pluck('id'),
                ['role' => 'technician']
            );
        });
    }

    public function setAttributes(array $attributes)
    {
        return $this->state(function (array $attributesOriginal) use ($attributes) {
            return array_merge($attributesOriginal, $attributes);
        });
    }
}
