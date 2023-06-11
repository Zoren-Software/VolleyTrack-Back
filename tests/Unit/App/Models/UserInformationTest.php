<?php

namespace Tests\Unit\App\Models;

use App\Models\UserInformation;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\TestCase;

class UserInformationTest extends TestCase
{
    /**
     * A basic unit test relation user.
     *
     * @test
     *
     * @return void
     */
    public function user()
    {
        $userInformation = new UserInformation();
        $this->assertInstanceOf(BelongsTo::class, $userInformation->user());
    }
}
