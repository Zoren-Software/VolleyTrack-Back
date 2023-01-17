<?php

namespace Tests\Unit\App\GraphQL\Validators\Mutation;

use App\GraphQL\Validators\Mutation\SpecificFundamentalCreateValidator;
use Tests\TestCase;

class SpecificFundamentalCreateValidatorTest extends TestCase
{
    /**
     * A basic unit test messages.
     *
     * @test
     * @return void
     */
    public function messages()
    {
        $validator = new SpecificFundamentalCreateValidator();

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
        $validator = new SpecificFundamentalCreateValidator();

        $this->assertIsArray($validator->rules());
        $this->assertNotEmpty($validator->rules());
    }
}
