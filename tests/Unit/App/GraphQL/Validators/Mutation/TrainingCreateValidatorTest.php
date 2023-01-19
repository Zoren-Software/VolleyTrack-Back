<?php

namespace Tests\Unit\App\GraphQL\Validators\Mutation;

use App\GraphQL\Validators\Mutation\TrainingCreateValidator;
use Mockery\MockInterface;
use Nuwave\Lighthouse\Execution\Arguments\ArgumentSet;
use Tests\TestCase;

class TrainingCreateValidatorTest extends TestCase
{
    /**
     * A basic unit test messages.
     *
     * @test
     *
     * @return void
     */
    public function messages()
    {
        $validator = new TrainingCreateValidator();

        $this->assertIsArray($validator->messages());
        $this->assertNotEmpty($validator->messages());
    }

    /**
     * A basic unit test rules.
     *
     * @test
     *
     * @return void
     */
    public function rules()
    {
        $validator = new TrainingCreateValidator();
        $validator->setArgs($this->mock(ArgumentSet::class, function (MockInterface $mock) {
            $mock->shouldReceive('toArray')->andReturn([
                'fundamentalId' => [1, 2, 3],
            ]);
        }));

        $this->assertIsArray($validator->rules());
        $this->assertNotEmpty($validator->rules());
    }
}
