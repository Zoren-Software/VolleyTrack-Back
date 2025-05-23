<?php

namespace Tests\Unit\App\GraphQL\Validators\Mutation;

use App\GraphQL\Validators\Mutation\PositionEditValidator;
use Nuwave\Lighthouse\Execution\Arguments\ArgumentSet;
use Tests\TestCase;

class PositionEditValidatorTest extends TestCase
{
    /**
     * A basic unit test messages.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function messages()
    {
        $validator = new PositionEditValidator;

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
        $args = new ArgumentSet;
        $args->toArray('id');

        $validator = new PositionEditValidator;
        $validator->setArgs($args);

        $this->assertIsArray($validator->rules());
        $this->assertNotEmpty($validator->rules());
    }
}
