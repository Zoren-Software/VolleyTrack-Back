<?php

namespace Tests\Unit\GraphQL\Validators\Mutation;

use App\GraphQL\Validators\Mutation\UserCreateValidator;
use Nuwave\Lighthouse\Execution\Arguments\ArgumentSet;
use Tests\TestCase;


class UserCreateValidatorTest extends TestCase
{
    /**
     * A basic unit test messages.
     *
     * @return void
     */
    public function test_messages()
    {
        $validator = new UserCreateValidator();

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
        $args = new ArgumentSet();
        $args->toArray('id');

        $validator = new UserCreateValidator();
        $validator->setArgs($args);

        $this->assertIsArray($validator->rules());
        $this->assertNotEmpty($validator->rules());
    }
}
