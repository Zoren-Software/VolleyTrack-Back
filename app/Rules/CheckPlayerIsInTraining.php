<?php

namespace App\Rules;

use App\Models\ConfirmationTraining;
use Illuminate\Contracts\Validation\Rule;

class CheckPlayerIsInTraining implements Rule
{
    private int $playerId;
    private int $trainingId;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(int $playerId, int $trainingId)
    {
        $this->playerId = $playerId;
        $this->trainingId = $trainingId;
    }

    /**
     * Checks if the player is related in training.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return ConfirmationTraining::where('player_id', $this->playerId)
            ->where('training_id', $this->trainingId)
            ->first() != null;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('CheckPlayerIsInTraining.message_error');
    }
}
