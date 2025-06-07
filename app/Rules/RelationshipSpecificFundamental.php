<?php

namespace App\Rules;

use App\Models\SpecificFundamental;
use Illuminate\Contracts\Validation\InvokableRule;

class RelationshipSpecificFundamental implements InvokableRule
{
    /**
     * @var array<int>
     */
    private $fundamentalIds;

    /**
     * @param  array<int>  $fundamentalIds
     */
    public function __construct($fundamentalIds)
    {
        $this->fundamentalIds = $fundamentalIds;
    }

    /**
     * Run the validation rule.
     *
     * @codeCoverageIgnore
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function __invoke(string $attribute, mixed $specificFundamentalIds, \Closure $fail): void
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
