<?php

namespace App\Rules;

use App\Models\Training;
use Illuminate\Contracts\Validation\InvokableRule;

class ValidTrainingDeletion implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @codeCoverageIgnore
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        foreach ($value as $id) {
            /** @var \App\Models\Training|null $training */
            $training = Training::with(['confirmationsTraining' => function ($query) {
                $query->where('presence', true);
            }])->find($id);

            if ($training && $training->confirmationsTraining->isNotEmpty()) {
                $fail("O treino com ID $id não pode ser deletado pois possui confirmações de presença neste treino.");
            }
        }
    }
}
