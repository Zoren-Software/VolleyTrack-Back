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
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function user()
    {
        $userInformation = new UserInformation;
        $this->assertInstanceOf(BelongsTo::class, $userInformation->user());
    }
}
