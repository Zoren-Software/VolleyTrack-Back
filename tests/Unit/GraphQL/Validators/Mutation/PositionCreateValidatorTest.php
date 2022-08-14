<?php

namespace Tests\Unit\GraphQL\Validators\Mutation;

use App\GraphQL\Validators\Mutation\PositionCreateValidator;
use Tests\TestCase;

class PositionCreateValidatorTest extends TestCase
{
    /**
     * A basic unit test messages.
     *
     * @return void
     */
    public function test_messages()
    {
        $validator = new PositionCreateValidator();

        $this->assertIsArray($validator->messages());
    }
}
