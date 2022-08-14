<?php

namespace Tests\Unit\App\Models;

use App\Models\SpecificFundamental;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Tests\TestCase;

class SpecificFundamentalTest extends TestCase
{
    /**
     * A basic unit test relation users.
     *
     * @return void
     */
    public function test_user()
    {
        $specificFundamental = new SpecificFundamental();
        $this->assertInstanceOf(BelongsTo::class, $specificFundamental->user());
    }

    /**
     * A basic unit test relation users.
     *
     * @return void
     */
    public function test_fundamentals()
    {
        $specificFundamental = new SpecificFundamental();
        $this->assertInstanceOf(BelongsToMany::class, $specificFundamental->fundamentals());
    }
}
