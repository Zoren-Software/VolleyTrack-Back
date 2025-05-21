<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TeamLevel>
 */
class TeamLevelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name() . ' TEAM LEVEL',
            'description' => $this->faker->text(),
        ];
    }

    public function setAttributes(array $attributes)
    {
        return $this->state(function (array $attributesOriginal) use ($attributes) {
            return array_merge($attributesOriginal, $attributes);
        });
    }
}
