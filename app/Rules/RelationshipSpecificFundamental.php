<?php

namespace App\Rules;

use App\Models\SpecificFundamental;
use Illuminate\Contracts\Validation\InvokableRule;

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
     * @codeCoverageIgnore
     *
     * @param  string  $attribute
     * @param  mixed  $specificFundamentalIds
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
