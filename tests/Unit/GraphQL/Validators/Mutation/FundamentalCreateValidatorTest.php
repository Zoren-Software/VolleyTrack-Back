<?php

namespace Tests\Unit\GraphQL\Validators\Mutation;

use App\GraphQL\Validators\Mutation\FundamentalCreateValidator;
use Tests\TestCase;

class FundamentalCreateValidatorTest extends TestCase
{
    /**
     * A basic unit test messages.
     *
     * @return void
     */
    public function test_messages()
    {
        $validator = new FundamentalCreateValidator();

        $this->assertIsArray($validator->messages());
        $this->assertNotEmpty($validator->messages());
    }
}
