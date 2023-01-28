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
     * @test
     *
     * @return void
     */
    public function user()
    {
        $confirmationTraining = new ConfirmationTraining();
        $this->assertInstanceOf(BelongsTo::class, $confirmationTraining->user());
    }

    /**
     * A basic unit test relation player.
     *
     * @test
     *
     * @return void
     */
    public function player()
    {
        $confirmationTraining = new ConfirmationTraining();
        $this->assertInstanceOf(BelongsTo::class, $confirmationTraining->player());
    }

    /**
     * A basic unit test relation training.
     *
     * @test
     *
     * @return void
     */
    public function training()
    {
        $confirmationTraining = new ConfirmationTraining();
        $this->assertInstanceOf(BelongsTo::class, $confirmationTraining->training());
    }

    /**
     * A basic unit test relation team.
     *
     * @test
     *
     * @return void
     */
    public function team()
    {
        $confirmationTraining = new ConfirmationTraining();
        $this->assertInstanceOf(BelongsTo::class, $confirmationTraining->team());
    }

    /**
     * A basic unit test relation scopeStatus.
     *
     * @test
     * 
     * @dataProvider scopeFilterProvider
     *
     * @return void
     */
    public function scopeStatus($parameter)
    {
        $confirmationTraining = new ConfirmationTraining();
        $class = $parameter === null ? ConfirmationTraining::class : Builder::class;

        $this->assertInstanceOf($class, $confirmationTraining->scopeStatus($confirmationTraining, $parameter));
    }

    /**
     * A basic unit test relation scopePresence.
     *
     * @test
     * 
     * @dataProvider scopeFilterProvider
     *
     * @return void
     */
    public function scopePresence($parameter)
    {
        $confirmationTraining = new ConfirmationTraining();
        $class = $parameter === null ? ConfirmationTraining::class : Builder::class;

        $this->assertInstanceOf($class, $confirmationTraining->scopePresence($confirmationTraining, $parameter));
    }

    /**
     * A basic unit test relation scopePlayer.
     *
     * @test
     * 
     * @dataProvider scopeFilterProvider
     *
     * @return void
     */
    public function scopePlayer($parameter)
    {
        $confirmationTraining = new ConfirmationTraining();
        $class = $parameter === null ? ConfirmationTraining::class : Builder::class;

        $this->assertInstanceOf($class, $confirmationTraining->scopePlayer($confirmationTraining, $parameter));
    }

    /**
     * A basic unit test relation scopeTeam.
     *
     * @test
     * 
     * @dataProvider scopeFilterProvider
     *
     * @return void
     */
    public function scopeTeam($parameter)
    {
        $confirmationTraining = new ConfirmationTraining();
        $class = $parameter === null ? ConfirmationTraining::class : Builder::class;

        $this->assertInstanceOf($class, $confirmationTraining->scopeTeam($confirmationTraining, $parameter));
    }

    /**
     * A basic unit test relation scopeTraining.
     *
     * @test
     * 
     * @dataProvider scopeFilterProvider
     *
     * @return void
     */
    public function scopeTraining($parameter)
    {
        $confirmationTraining = new ConfirmationTraining();
        $class = $parameter === null ? ConfirmationTraining::class : Builder::class;

        $this->assertInstanceOf($class, $confirmationTraining->scopeTraining($confirmationTraining, $parameter));
    }

    /**
     * A basic unit test relation scopeUser.
     *
     * @test
     * 
     * @dataProvider scopeFilterProvider
     *
     * @return void
     */
    public function scopeUser($parameter)
    {
        $confirmationTraining = new ConfirmationTraining();
        $class = $parameter === null ? ConfirmationTraining::class : Builder::class;

        $this->assertInstanceOf($class, $confirmationTraining->scopeUser($confirmationTraining, $parameter));
    }

    /**
     * A dataProvider scopeFilterProvider.
     *
     * @return void
     */
    public function scopeFilterProvider()
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
     * @test
     * 
     * @dataProvider listProvider
     * 
     * @return void
     */

    public function list($args)
    {
        $confirmationTraining = new ConfirmationTraining();

        $this->assertInstanceOf(Builder::class, $confirmationTraining->list($args));
    }

    /**
     * A dataProvider listProvider.
     *
     * @return void
     */
    public function listProvider()
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
