<?php

namespace Tests\Unit\App\Models;

use App\Models\Training;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Tests\TestCase;

class TrainingTest extends TestCase
{
    /**
     * A basic unit test relation getActivitylogOptions.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function get_activitylog_options()
    {
        $training = new Training;
        $this->assertInstanceOf(LogOptions::class, $training->getActivitylogOptions());
    }

    /**
     * A basic unit test relation user.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function relation_user()
    {
        $training = new Training;
        $this->assertInstanceOf(BelongsTo::class, $training->user());
    }

    /**
     * A basic unit test relation team.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function relation_team()
    {
        $training = new Training;
        $this->assertInstanceOf(BelongsTo::class, $training->team());
    }

    /**
     * A basic unit test relation fundamentals.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function relation_fundamentals()
    {
        $training = new Training;
        $this->assertInstanceOf(BelongsToMany::class, $training->fundamentals());
    }

    /**
     * A basic unit test relation specificFundamentals.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function relation_specific_fundamentals()
    {
        $training = new Training;
        $this->assertInstanceOf(BelongsToMany::class, $training->specificFundamentals());
    }

    /**
     * A basic unit test relation confirmationsTraining.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function relation_confirmations_training()
    {
        $training = new Training;
        $this->assertInstanceOf(HasMany::class, $training->confirmationsTraining());
    }

    /**
     * A basic unit test range date notification.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('rangeDateNotificationProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function range_date_notification(string $startDate, string $dateToday, string $dateLimit, bool $expected)
    {
        $training = new Training;
        $this->assertEquals($expected, $training->rangeDateNotification($startDate, $dateToday, $dateLimit));
    }

    /**
     * @return array
     */
    public static function rangeDateNotificationProvider()
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
