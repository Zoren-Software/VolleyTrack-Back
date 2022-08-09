<?php

namespace Tests\Unit\App\Models;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic unit test make password.
     *
     * @return void
     */
    public function test_make_password()
    {
        $password = 'password';
        $user = new User();
        $user->makePassword($password);
        $this->assertTrue(Hash::check($password, $user->password));
    }
}
