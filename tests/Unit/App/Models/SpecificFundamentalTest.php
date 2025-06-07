<?php

namespace Tests\Unit\App\Models;

use App\Models\SpecificFundamental;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Activitylog\LogOptions;
use Tests\TestCase;

class SpecificFundamentalTest extends TestCase
{
    /**
     * A basic unit test relation users.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function user()
    {
        $specificFundamental = new SpecificFundamental;
        $this->assertInstanceOf(BelongsTo::class, $specificFundamental->user());
    }

    /**
     * A basic unit test relation users.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function fundamentals()
    {
        $specificFundamental = new SpecificFundamental;
        $this->assertInstanceOf(BelongsToMany::class, $specificFundamental->fundamentals());
    }

    /**
     * A basic unit test relation getActivitylogOptions.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function get_activitylog_options()
    {
        $specificFundamental = new SpecificFundamental;
        $this->assertInstanceOf(LogOptions::class, $specificFundamental->getActivitylogOptions());
    }
}
