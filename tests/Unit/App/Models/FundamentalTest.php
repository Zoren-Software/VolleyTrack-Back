<?php

namespace Tests\Unit\App\Models;

use App\Models\Fundamental;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Tests\TestCase;

class FundamentalTest extends TestCase
{
    /**
     * A basic unit test relation users.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function specific_fundamentals()
    {
        $fundamental = new Fundamental;
        $this->assertInstanceOf(HasMany::class, $fundamental->specificFundamental());
    }

    /**
     * A basic unit test relation user.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function user()
    {
        $fundamental = new Fundamental;
        $this->assertInstanceOf(BelongsTo::class, $fundamental->user());
    }

    /**
     * A basic unit test relation getActivitylogOptions.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function get_activitylog_options()
    {
        $fundamental = new Fundamental;
        $this->assertInstanceOf(LogOptions::class, $fundamental->getActivitylogOptions());
    }

    /**
     * A basic unit test relation trainings.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function trainings()
    {
        $fundamental = new Fundamental;
        $this->assertInstanceOf(BelongsToMany::class, $fundamental->trainings());
    }
}
