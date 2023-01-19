<?php

namespace Tests\Unit\App\Models;

use App\Models\FundamentalsSpecificFundamentals;
use Spatie\Activitylog\LogOptions;
use Tests\TestCase;

class FundamentalsSpecificFundamentalsTest extends TestCase
{
    /**
     * A basic unit test relation getActivitylogOptions.
     * @test
     * @return void
     */
    public function getActivitylogOptions()
    {
        $fundamentalSpecificFundamentals = new FundamentalsSpecificFundamentals();
        $this->assertInstanceOf(LogOptions::class, $fundamentalSpecificFundamentals->getActivitylogOptions());
    }
}
