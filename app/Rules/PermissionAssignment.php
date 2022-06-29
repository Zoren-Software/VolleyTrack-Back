<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ImplicitRule;
use App\Models\Role;

class PermissionAssignment implements ImplicitRule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $values)
    {
        foreach($values as $value) {
            if (!Role::find($value)) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('PermissionAssignment.validation_message_error');
    }
}
