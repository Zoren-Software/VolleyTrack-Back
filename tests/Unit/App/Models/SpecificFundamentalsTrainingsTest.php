<?php

namespace Tests\Unit\App\Models;

use App\Models\SpecificFundamentalsTrainings;
use Spatie\Activitylog\LogOptions;
use Tests\TestCase;

class SpecificFundamentalsTrainingsTest extends TestCase
{
    /**
     * A basic unit test relation getActivitylogOptions.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function get_activitylog_options()
    {
        $specificFundamentalsTrainings = new SpecificFundamentalsTrainings;
        $this->assertInstanceOf(LogOptions::class, $specificFundamentalsTrainings->getActivitylogOptions());
    }
}
