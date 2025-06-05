<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TeamCategory>
 */
class TeamCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name() . ' TEAM CATEGORY',
            'description' => $this->faker->text(),
            'min_age' => $this->faker->numberBetween(12, 19),
            'max_age' => $this->faker->numberBetween(35, 100),
        ];
    }

    /**
     * @param  array<string, mixed>  $attributes
     * @return Factory<\App\Models\TeamCategory>
     */
    public function setAttributes(array $attributes): Factory
    {
        return $this->state(function (array $attributesOriginal) use ($attributes) {
            return array_merge($attributesOriginal, $attributes);
        });
    }
}
