<?php

namespace Tests\Unit\App\GraphQL\Validators\Mutation;

use App\GraphQL\Validators\Mutation\ConfirmTrainingValidator;
use Mockery\MockInterface;
use Nuwave\Lighthouse\Execution\Arguments\ArgumentSet;
use Tests\TestCase;

class ConfirmTrainingValidatorTest extends TestCase
{
    /**
     * A basic unit test messages.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function messages()
    {
        $validator = new ConfirmTrainingValidator;

        $this->assertIsArray($validator->messages());
        $this->assertNotEmpty($validator->messages());
    }

    /**
     * A basic unit test rules.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function rules()
    {
        $validator = new ConfirmTrainingValidator;
        $validator->setArgs($this->mock(ArgumentSet::class, function (MockInterface $mock) {
            $mock->shouldReceive('toArray')->andReturn([
                'playerId' => 1,
                'trainingId' => 1,
            ]);
        }));

        $this->assertIsArray($validator->rules());
        $this->assertNotEmpty($validator->rules());
    }
}
