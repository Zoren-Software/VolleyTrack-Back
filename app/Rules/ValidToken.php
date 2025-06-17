<?php

namespace App\Rules;

use App\Models\Central\ExternalAccessToken;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidToken implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!ExternalAccessToken::where('token', $value)->exists()) {
            $fail(trans('validation.token_invalid'));
        }
    }
}
