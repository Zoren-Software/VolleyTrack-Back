<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class OwnsPassword implements Rule
{
    /**
     * @var int
     */
    private $userId;

    /**
     * @param  int  $userId
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return (int) $this->userId === auth()->id() || $value == null;
    }

    /**
     * @return string
     */
    public function message()
    {
        return trans('UserEdit.owns_password');
    }
}
