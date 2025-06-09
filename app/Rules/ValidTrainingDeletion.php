<?php

namespace App\Rules;

use App\Models\Training;
use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Database\Eloquent\Builder;

class ValidTrainingDeletion implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @codeCoverageIgnore
     *
     * @param  string  $attribute
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function __invoke($attribute, mixed $value, \Closure $fail): void
    {
        if (!is_iterable($value)) {
            $fail('O valor informado para exclusão de treinos deve ser uma lista de IDs.');

            return;
        }

        foreach ($value as $id) {
            if (!is_scalar($id)) {
                continue; // Ou você pode lançar erro aqui, se quiser
            }

            $training = Training::with(['confirmationsTraining' => function (Builder $query) {
                $query->where('presence', true);
            }])->find($id);

            if ($training && $training->confirmationsTraining->isNotEmpty()) {
                $fail('O treino com ID ' . (string) $id . ' não pode ser deletado pois possui confirmações de presença neste treino.');
            }
        }
    }
}
