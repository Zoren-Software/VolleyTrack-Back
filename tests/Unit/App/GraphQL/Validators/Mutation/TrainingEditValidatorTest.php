<?php

namespace Tests\Unit\App\GraphQL\Validators\Mutation;

use App\GraphQL\Validators\Mutation\TrainingEditValidator;
use Mockery\MockInterface;
use Nuwave\Lighthouse\Execution\Arguments\ArgumentSet;
use Tests\TestCase;

class TrainingEditValidatorTest extends TestCase
{
    /**
     * A basic unit test messages.
     *
     * @return void
     */
    public function test_messages()
    {
        $validator = new TrainingEditValidator();

        $this->assertIsArray($validator->messages());
        $this->assertNotEmpty($validator->messages());
    }

    /**
     * A basic unit test rules.
     *
     * @return void
     */
    public function test_rules()
    {
        $validator = new TrainingEditValidator();
        $validator->setArgs($this->mock(ArgumentSet::class, function (MockInterface $mock) {
            $mock->shouldReceive('toArray')->andReturn([
                'fundamentalId' => [1, 2, 3],
            ]);
        }));

        $this->assertIsArray($validator->rules());
        $this->assertNotEmpty($validator->rules());
    }
}
