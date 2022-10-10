<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;
use App\Models\SpecificFundamental;

class RelationshipSpecificFundamental implements InvokableRule
{
    private $fundamentalIds;

    public function __construct($fundamentalIds)
    {
        $this->fundamentalIds = $fundamentalIds;
    }

    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $specificFundamentalIds, $fail)
    {
        $specificFundamentals = SpecificFundamental::whereIn('id', $specificFundamentalIds)->get();

        foreach ($specificFundamentals as $specificFundamental) {
            $fundamentalIds = $specificFundamental->fundamentals->pluck('id')->toArray();

            if (!array_intersect($fundamentalIds, $this->fundamentalIds)) {
                $fail('TrainingEdit.specific_fundamentals_not_relationship')->translate();
            }
        }
    }
}
