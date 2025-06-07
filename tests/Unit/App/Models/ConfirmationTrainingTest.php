<?php

namespace Tests\Unit\App\Models;

use App\Models\ConfirmationTraining;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\TestCase;

class ConfirmationTrainingTest extends TestCase
{
    /**
     * A basic unit test relation user.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function user()
    {
        $confirmationTraining = new ConfirmationTraining;
        $this->assertInstanceOf(BelongsTo::class, $confirmationTraining->user());
    }

    /**
     * A basic unit test relation player.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function player()
    {
        $confirmationTraining = new ConfirmationTraining;
        $this->assertInstanceOf(BelongsTo::class, $confirmationTraining->player());
    }

    /**
     * A basic unit test relation training.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function training()
    {
        $confirmationTraining = new ConfirmationTraining;
        $this->assertInstanceOf(BelongsTo::class, $confirmationTraining->training());
    }

    /**
     * A basic unit test relation team.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function team()
    {
        $confirmationTraining = new ConfirmationTraining;
        $this->assertInstanceOf(BelongsTo::class, $confirmationTraining->team());
    }

    /**
     * A basic unit test relation scopeStatus.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('scopeFilterProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function scope_status($parameter)
    {
        $confirmationTraining = new ConfirmationTraining;
        $class = $parameter === null ? ConfirmationTraining::class : Builder::class;

        $this->assertInstanceOf($class, $confirmationTraining->scopeStatus($confirmationTraining, $parameter));
    }

    /**
     * A basic unit test relation scopePresence.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('scopeFilterProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function scope_presence($parameter)
    {
        $confirmationTraining = new ConfirmationTraining;
        $class = $parameter === null ? ConfirmationTraining::class : Builder::class;

        $this->assertInstanceOf($class, $confirmationTraining->scopePresence($confirmationTraining, $parameter));
    }

    /**
     * A basic unit test relation scopePlayer.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('scopeFilterProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function scope_player($parameter)
    {
        $confirmationTraining = new ConfirmationTraining;
        $class = $parameter === null ? ConfirmationTraining::class : Builder::class;

        $this->assertInstanceOf($class, $confirmationTraining->scopePlayer($confirmationTraining, $parameter));
    }

    /**
     * A basic unit test relation scopeTeam.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('scopeFilterProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function scope_team($parameter)
    {
        $confirmationTraining = new ConfirmationTraining;
        $class = $parameter === null ? ConfirmationTraining::class : Builder::class;

        $this->assertInstanceOf($class, $confirmationTraining->scopeTeam($confirmationTraining, $parameter));
    }

    /**
     * A basic unit test relation scopeTraining.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('scopeFilterProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function scope_training($parameter)
    {
        $confirmationTraining = new ConfirmationTraining;
        $class = $parameter === null ? ConfirmationTraining::class : Builder::class;

        $this->assertInstanceOf($class, $confirmationTraining->scopeTraining($confirmationTraining, $parameter));
    }

    /**
     * A basic unit test relation scopeUser.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('scopeFilterProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function scope_user($parameter)
    {
        $confirmationTraining = new ConfirmationTraining;
        $class = $parameter === null ? ConfirmationTraining::class : Builder::class;

        $this->assertInstanceOf($class, $confirmationTraining->scopeUser($confirmationTraining, $parameter));
    }

    /**
     * A dataProvider scopeFilterProvider.
     *
     * @return void
     */
    public static function scopeFilterProvider()
    {
        return [
            'parameter with value' => [
                'parameter' => true,
            ],
            'parameter without value' => [
                'parameter' => null,
            ],
        ];
    }

    /**
     * A basic unit test method list.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('listProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function list($args)
    {
        $confirmationTraining = new ConfirmationTraining;

        $this->assertInstanceOf(Builder::class, $confirmationTraining->list($args));
    }

    /**
     * A dataProvider listProvider.
     *
     * @return void
     */
    public static function listProvider()
    {
        return [
            'parameter with value' => [
                'args' => [
                    'status' => 'confirmed',
                    'presence' => false,
                    'player_id' => 1,
                    'user_id' => 1,
                    'team_id' => 1,
                    'training_id' => 1,
                ],
            ],
            'parameter without value' => [
                'args' => [
                    'status' => null,
                    'presence' => null,
                    'player_id' => null,
                    'user_id' => null,
                    'team_id' => null,
                    'training_id' => null,
                ],
            ],
        ];
    }
}
