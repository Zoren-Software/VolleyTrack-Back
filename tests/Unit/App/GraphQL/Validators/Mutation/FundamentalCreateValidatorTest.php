<?php

namespace Tests\Unit\App\GraphQL\Validators\Mutation;

use App\GraphQL\Validators\Mutation\FundamentalCreateValidator;
use Tests\TestCase;

class FundamentalCreateValidatorTest extends TestCase
{
    /**
     * A basic unit test messages.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function messages()
    {
        $validator = new FundamentalCreateValidator;

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
        $validator = new FundamentalCreateValidator;

        $this->assertIsArray($validator->rules());
        $this->assertNotEmpty($validator->rules());
    }
}
