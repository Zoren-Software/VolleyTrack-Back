<?php

namespace Tests\Unit\App\GraphQL\Mutations;

use App\GraphQL\Mutations\SpecificFundamentalMutation;
use App\Models\Fundamental;
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
    public function test_create_specific_fundamental(array $data, $fundamental): void
    {
        $graphQLContext = $this->createMock(GraphQLContext::class);
        $specificFundamental = $this->createMock(SpecificFundamental::class);

        $specificFundamental->method('fundamentals')->willReturn($fundamental);

        $specificFundamental->expects($this->once())
            ->method('save');

        $specificFundamentalMutation = new SpecificFundamentalMutation($specificFundamental);
        $specificFundamentalMutation->create(null, $data, $graphQLContext);
    }

    public function createSpecificFundamentalProvider(): array
    {
        return [
            'create using fundamental_id' => [
                'data' => [
                    'name' => 'Teste',
                    'user_id' => 1,
                    'fundamental_id' => [1],
                ],
                'fundamental' => $this->createMock(Fundamental::class),
            ],
            'create not using fundamental_id' => [
                'data' => [
                    'name' => 'Teste',
                    'user_id' => 1,
                ],
                'fundamental' => null,
            ]
        ];
    }

    /**
     * A basic unit test edit specific fundamental.
     *
     * @dataProvider editSpecificFundamentalProvider
     * @return void
     */
    public function test_edit_specific_fundamental(array $data, $fundamental): void
    {
        $graphQLContext = $this->createMock(GraphQLContext::class);
        $specificFundamental = $this->createMock(SpecificFundamental::class);

        $specificFundamental->method('fundamentals')->willReturn($fundamental);

        $specificFundamental->expects($this->once())
            ->method('save');

        $specificFundamentalMutation = new SpecificFundamentalMutation($specificFundamental);
        $specificFundamentalMutation->edit(null, $data, $graphQLContext);
    }

    public function editSpecificFundamentalProvider(): array
    {
        return [
            'edit using fundamental_id' => [
                'data' => [
                    'id' => 1,
                    'name' => 'Teste',
                    'user_id' => 1,
                    'fundamental_id' => [1],
                ],
                'fundamental' => $this->createMock(Fundamental::class),
            ],
            'edit not using fundamental_id' => [
                'data' => [
                    'id' => 1,
                    'name' => 'Teste',
                    'user_id' => 1,
                ],
                'fundamental' => null,
            ]
        ];
    }
}
