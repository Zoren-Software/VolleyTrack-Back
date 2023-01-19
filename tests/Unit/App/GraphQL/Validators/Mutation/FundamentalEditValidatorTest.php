<?php

namespace Tests\Unit\App\GraphQL\Validators\Mutation;

use App\GraphQL\Validators\Mutation\FundamentalEditValidator;
use Nuwave\Lighthouse\Execution\Arguments\ArgumentSet;
use Tests\TestCase;

class FundamentalEditValidatorTest extends TestCase
{
    /**
     * A basic unit test messages.
     *
     * @test
     *
     * @return void
     */
    public function messages()
    {
        $validator = new FundamentalEditValidator();

        $this->assertIsArray($validator->messages());
        $this->assertNotEmpty($validator->messages());
    }

    /**
     * A basic unit test rules.
     *
     * @test
     *
     * @return void
     */
    public function rules()
    {
        $args = new ArgumentSet();
        $args->toArray('id');

        $validator = new FundamentalEditValidator();
        $validator->setArgs($args);

        $this->assertIsArray($validator->rules());
        $this->assertNotEmpty($validator->rules());
    }
}
