<?php

namespace Tests\Unit\App\Models;

use App\Models\Training;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Activitylog\LogOptions;
use Tests\TestCase;

class TrainingTest extends TestCase
{
    /**
     * A basic unit test relation getActivitylogOptions.
     *
     * @test
     *
     * @return void
     */
    public function getActivitylogOptions()
    {
        $training = new Training();
        $this->assertInstanceOf(LogOptions::class, $training->getActivitylogOptions());
    }

    /**
     * A basic unit test relation user.
     *
     * @test
     *
     * @return void
     */
    public function relationUser()
    {
        $training = new Training();
        $this->assertInstanceOf(BelongsTo::class, $training->user());
    }

    /**
     * A basic unit test relation team.
     *
     * @test
     *
     * @return void
     */
    public function relationTeam()
    {
        $training = new Training();
        $this->assertInstanceOf(BelongsTo::class, $training->team());
    }

    /**
     * A basic unit test relation fundamentals.
     *
     * @test
     *
     * @return void
     */
    public function relationFundamentals()
    {
        $training = new Training();
        $this->assertInstanceOf(BelongsToMany::class, $training->fundamentals());
    }

    /**
     * A basic unit test relation specificFundamentals.
     *
     * @test
     *
     * @return void
     */
    public function relationSpecificFundamentals()
    {
        $training = new Training();
        $this->assertInstanceOf(BelongsToMany::class, $training->specificFundamentals());
    }

    /**
     * A basic unit test range date notification.
     *
     * @dataProvider rangeDateNotificationProvider
     *
     * @test
     *
     * @return void
     */
    public function rangeDateNotification(string $startDate, string $dateToday, string $dateLimit, bool $expected)
    {
        $training = new Training();
        $this->assertEquals($expected, $training->rangeDateNotification($startDate, $dateToday, $dateLimit));
    }

    public function rangeDateNotificationProvider()
    {
        $sameDate = '13/01/2023';

        return [
            'notification if training is on the day' => [
                'startDate' => $sameDate,
                'dateToday' => $sameDate,
                'dateLimit' => $sameDate,
                'expected' => true,
            ],
            'out-of-expected date range' => [
                'startDate' => '17/01/2023',
                'dateToday' => '16/01/2023',
                'dateLimit' => '14/01/2023',
                'expected' => false,
            ],
            'other out-of-expected date range' => [
                'startDate' => '14/01/2023',
                'dateToday' => '15/01/2023',
                'dateLimit' => '18/01/2023',
                'expected' => false,
            ],
            'in-expected date range' => [
                'startDate' => '19/01/2023',
                'dateToday' => '18/01/2023',
                'dateLimit' => '20/01/2023',
                'expected' => true,
            ],
        ];
    }
}
