<?php

namespace Tests\Unit\App\GraphQL\Validators\Mutation;

use App\GraphQL\Validators\Mutation\ConfigEditValidator;
use Tests\TestCase;

class ConfigEditValidatorTest extends TestCase
{
    /**
     * A basic unit test messages.
     *
     * @test
     * @return void
     */
    public function messages()
    {
        $validator = new ConfigEditValidator();

        $this->assertIsArray($validator->messages());
        $this->assertNotEmpty($validator->messages());
    }

    /**
     * A basic unit test rules.
     *
     * @test
     * @return void
     */
    public function rules()
    {
        $validator = new ConfigEditValidator();

        $this->assertIsArray($validator->rules());
        $this->assertNotEmpty($validator->rules());
    }
}
