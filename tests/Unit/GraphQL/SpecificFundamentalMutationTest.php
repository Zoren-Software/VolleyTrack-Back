<?php

namespace Tests\Unit\GraphQL;

use App\GraphQL\Mutations\SpecificFundamentalMutation;
use App\Models\SpecificFundamental;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use PHPUnit\Framework\TestCase;

class SpecificFundamentalMutationTest extends TestCase
{
    /**
     * A basic unit test create specific fundamental.
     *
     * @dataProvider createSpecificFundamentalProvider
     * @return void
     */
    public function test_create_specific_fundamental(array $data)
    {
        $graphQLContext = $this->createMock(GraphQLContext::class);
        $specificFundamental = $this->createMock(SpecificFundamental::class);

        $specificFundamental->expects($this->once())
            ->method('save');

        $specificFundamentalMutation = new SpecificFundamentalMutation($specificFundamental);
        $specificFundamentalMutation->create(null, $data, $graphQLContext);
    }

    public function createSpecificFundamentalProvider()
    {
        return [
            'create using fundamental_id' => [
                'data' => [
                    'name' => 'Teste',
                    'user_id' => 1,
                    'fundamental_id' => [1],
                ],
            ],
            'create not using fundamental_id' => [
                'data' => [
                    'name' => 'Teste',
                    'user_id' => 1,
                ],
            ]
        ];
    }

    /**
     * A basic unit test edit specific fundamental.
     *
     * @dataProvider editSpecificFundamentalProvider
     * @return void
     */
    public function test_edit_specific_fundamental(array $data)
    {
        $graphQLContext = $this->createMock(GraphQLContext::class);
        $specificFundamental = $this->createMock(SpecificFundamental::class);

        $specificFundamental->expects($this->once())
            ->method('save');

        $specificFundamentalMutation = new SpecificFundamentalMutation($specificFundamental);
        $specificFundamentalMutation->edit(null, $data, $graphQLContext);
    }

    public function editSpecificFundamentalProvider()
    {
        return [
            'edit using fundamental_id' => [
                'data' => [
                    'id' => 1,
                    'name' => 'Teste',
                    'user_id' => 1,
                    'fundamental_id' => [1],
                ],
            ],
            'edit not using fundamental_id' => [
                'data' => [
                    'id' => 1,
                    'name' => 'Teste',
                    'user_id' => 1,
                ],
            ]
        ];
    }
}
