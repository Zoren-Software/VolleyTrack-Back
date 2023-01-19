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
     * @test
     *
     * @return void
     */
    public function specificFundamentals()
    {
        $fundamental = new Fundamental();
        $this->assertInstanceOf(HasMany::class, $fundamental->specificFundamental());
    }

    /**
     * A basic unit test relation user.
     *
     * @test
     *
     * @return void
     */
    public function user()
    {
        $fundamental = new Fundamental();
        $this->assertInstanceOf(BelongsTo::class, $fundamental->user());
    }

    /**
     * A basic unit test relation getActivitylogOptions.
     *
     * @test
     *
     * @return void
     */
    public function getActivitylogOptions()
    {
        $fundamental = new Fundamental();
        $this->assertInstanceOf(LogOptions::class, $fundamental->getActivitylogOptions());
    }

    /**
     * A basic unit test relation trainings.
     *
     * @test
     *
     * @return void
     */
    public function trainings()
    {
        $fundamental = new Fundamental();
        $this->assertInstanceOf(BelongsToMany::class, $fundamental->trainings());
    }
}
