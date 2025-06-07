<?php

namespace Tests\Unit\App\Models;

use App\Models\FundamentalsTrainings;
use Spatie\Activitylog\LogOptions;
use Tests\TestCase;

class FundamentalsTrainingsTest extends TestCase
{
    /**
     * A basic unit test relation getActivitylogOptions.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function get_activitylog_options()
    {
        $fundamentalsTrainings = new FundamentalsTrainings;
        $this->assertInstanceOf(LogOptions::class, $fundamentalsTrainings->getActivitylogOptions());
    }
}
