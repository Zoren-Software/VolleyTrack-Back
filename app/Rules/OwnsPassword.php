<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class OwnsPassword implements Rule
{
    private $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    public function passes($attribute, $value)
    {
        return (int) $this->userId === auth()->id() || $value == null;
    }

    public function message()
    {
        return trans('UserEdit.owns_password');
    }
}
