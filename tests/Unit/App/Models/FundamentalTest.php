<?php

namespace Tests\Unit\App\Models;

use App\Models\Fundamental;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tests\TestCase;

class FundamentalTest extends TestCase
{
    /**
     * A basic unit test relation users.
     *
     * @return void
     */
    public function test_specific_fundamentals()
    {
        $fundamental = new Fundamental();
        $this->assertInstanceOf(HasMany::class, $fundamental->specificFundamental());
    }

    /**
     * A basic unit test relation user.
     *
     * @return void
     */
    public function test_user()
    {
        $fundamental = new Fundamental();
        $this->assertInstanceOf(BelongsTo::class, $fundamental->user());
    }
}
