<?php

namespace App\Rules;

use App\Models\Training;
use Illuminate\Contracts\Validation\Rule;

class CheckTrainingCancelled implements Rule
{
    private ?int $trainingId;

    private ?Training $training;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(
        ?int $trainingId,
        Training $training = null
    ) {
        $this->trainingId = $trainingId;
        $this->training = $training ?? new Training();
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
        $this->training = $this->training->find($this->trainingId);

        return $this->training->status;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('CheckTrainingCancelled.message_error');
    }
}
