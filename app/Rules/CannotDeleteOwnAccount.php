<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CannotDeleteOwnAccount implements Rule
{
    private $userIds;

    public function __construct($userIds)
    {
        $this->userIds = $userIds;
    }

    public function passes($attribute, $value)
    {
        return !in_array(auth()->id(), $this->userIds);
    }


    public function message()
    {
        return trans('UserDelete.cannot_delete_own_account');
    }
}
