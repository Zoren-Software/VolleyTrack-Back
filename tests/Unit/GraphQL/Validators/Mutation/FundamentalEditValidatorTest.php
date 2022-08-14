<?php

namespace Tests\Unit\GraphQL\Validators\Mutation;

use App\GraphQL\Validators\Mutation\FundamentalEditValidator;
use Nuwave\Lighthouse\Execution\Arguments\ArgumentSet;
use Tests\TestCase;

class FundamentalEditValidatorTest extends TestCase
{
    /**
     * A basic unit test messages.
     *
     * @return void
     */
    public function test_messages()
    {
        $validator = new FundamentalEditValidator();

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

        $validator = new FundamentalEditValidator();
        $validator->setArgs($args);

        $this->assertIsArray($validator->rules());
        $this->assertNotEmpty($validator->rules());
    }
}
