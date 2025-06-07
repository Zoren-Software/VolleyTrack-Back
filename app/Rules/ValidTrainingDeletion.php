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
        if (!is_iterable($value)) {
            $fail('O valor informado para exclusão de treinos deve ser uma lista de IDs.');

            return;
        }

        foreach ($value as $id) {
            /** @var \App\Models\Training|null $training */
            $training = Training::with(['confirmationsTraining' => function ($query) {
                $query->where('presence', true);
            }])->find($id);

            if ($training && $training->confirmationsTraining->isNotEmpty()) {
                $fail('O treino com ID ' . (string) $id . ' não pode ser deletado pois possui confirmações de presença neste treino.');
            }
        }
    }
}
