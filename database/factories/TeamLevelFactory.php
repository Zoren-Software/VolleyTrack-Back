<?php

namespace Database\Factories;

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
    public function definition(): array
    {
        return [
            'name' => $this->faker->name() . ' TEAM LEVEL',
            'description' => $this->faker->text(),
        ];
    }

    /**
     * @param  array<string, mixed>  $attributes
     * @return Factory<\App\Models\TeamLevel>
     */
    public function setAttributes(array $attributes): Factory
    {
        return $this->state(function (array $attributesOriginal) use ($attributes) {
            return array_merge($attributesOriginal, $attributes);
        });
    }
}
