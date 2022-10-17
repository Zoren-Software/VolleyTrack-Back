<?php

namespace Tests\Unit\App\GraphQL\Validators\Mutation;

use Tests\TestCase;
use App\GraphQL\Validators\Mutation\ConfigEditValidator;

class ConfigEditValidatorTest extends TestCase
{
    /**
     * A basic unit test messages.
     *
     * @return void
     */
    public function test_messages()
    {
        $validator = new ConfigEditValidator();

        $this->assertIsArray($validator->messages());
        $this->assertNotEmpty($validator->messages());
    }
}
