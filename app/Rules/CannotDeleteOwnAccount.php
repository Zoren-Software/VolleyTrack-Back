<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CannotDeleteOwnAccount implements Rule
{
    /**
     * @var array<int>
     */
    private $userIds;

    /**
     * @param array<int> $userIds
     */
    public function __construct($userIds)
    {
        $this->userIds = $userIds;
    }

    /**
     * @param string $attribute
     * @param mixed $value
     * 
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return !in_array(auth()->id(), $this->userIds);
    }

    /**
     * @return string
     */
    public function message()
    {
        return trans('UserDelete.cannot_delete_own_account');
    }
}
