<?php

namespace Tests\Unit\App\GraphQL\Validators\Mutation;

use App\GraphQL\Validators\Mutation\TeamEditValidator;
use Nuwave\Lighthouse\Execution\Arguments\ArgumentSet;
use Tests\TestCase;

class TeamEditValidatorTest extends TestCase
{
    /**
     * A basic unit test messages.
     * @test
     * @return void
     */
    public function messages()
    {
        $validator = new TeamEditValidator();

        $this->assertIsArray($validator->messages());
        $this->assertNotEmpty($validator->messages());
    }

    /**
     * A basic unit test rules.
     * @test
     * @return void
     */
    public function rules()
    {
        $args = new ArgumentSet();
        $args->toArray('id');

        $validator = new TeamEditValidator();
        $validator->setArgs($args);

        $this->assertIsArray($validator->rules());
        $this->assertNotEmpty($validator->rules());
    }
}
