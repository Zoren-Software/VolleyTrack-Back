<?php

namespace Tests\Unit\App\Models;

use App\Models\FundamentalsSpecificFundamentals;
use Spatie\Activitylog\LogOptions;
use Tests\TestCase;

class FundamentalsSpecificFundamentalsTest extends TestCase
{
    /**
     * A basic unit test relation getActivitylogOptions.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function get_activitylog_options()
    {
        $fundamentalSpecificFundamentals = new FundamentalsSpecificFundamentals;
        $this->assertInstanceOf(LogOptions::class, $fundamentalSpecificFundamentals->getActivitylogOptions());
    }
}
