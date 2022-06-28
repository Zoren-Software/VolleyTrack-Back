<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ImplicitRule;

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
    public function passes($attribute, $value)
    {
        // TODO - Verificar se o usuário tem acesso a role para atribuilá para outro usuário
        // testando com sail artisan test --filter UserTest::test_user_create                                                                                     ─╯

        dd($attribute, $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
