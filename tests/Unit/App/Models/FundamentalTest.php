<?php

namespace Tests\Unit\App\Models;

use App\Models\Fundamental;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tests\TestCase;
use Spatie\Activitylog\LogOptions;

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

    /**
     * A basic unit test relation getActivitylogOptions.
     *
     * @return void
     */
    public function test_get_activitylog_options()
    {
        $user = new Fundamental();
        $this->assertInstanceOf(LogOptions::class, $user->getActivitylogOptions());
    }
}
