<?php

namespace Database\Factories;

use App\Models\Position;
use App\Models\Training;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ScoutFundamentalTraining>
 */
class ScoutFundamentalTrainingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'player_id' => User::factory(),
            'training_id' => Training::factory(),
            'position_id' => Position::factory(),
        ];
    }
}
