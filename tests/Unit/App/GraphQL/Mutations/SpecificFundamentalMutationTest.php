<?php

namespace Tests\Unit\App\GraphQL\Mutations;

use App\GraphQL\Mutations\SpecificFundamentalMutation;
use App\Models\SpecificFundamental;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Mockery\MockInterface;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Tests\TestCase;

class SpecificFundamentalMutationTest extends TestCase
{
    /**
     * A basic unit test create and edit fundamental.
     *
     * @dataProvider specificFundamentalProvider
     *
     * @test
     *
     * @return void
     */
    public function specificFundamentalMake($data, $method)
    {
        $graphQLContext = $this->createMock(GraphQLContext::class);
        $specificFundamentalMock = $this->mock(
            SpecificFundamental::class,
            function (MockInterface $mock) use ($data, $method) {
                $fundamental = $this->createMock(BelongsToMany::class);

                if ($data['id']) {
                    $mock->shouldReceive('find')
                        ->once()
                        ->with($data['id'])
                        ->andReturn($mock);
                }

                $mock->shouldReceive($method)->with($data)->once()->andReturn($mock);
                $mock->shouldReceive('fundamentals')->once()->andReturn($fundamental);
                $mock->shouldReceive('syncWithoutDetaching')->with([$fundamental]);
            }
        );

        $specificFundamentalMutation = new SpecificFundamentalMutation($specificFundamentalMock);
        $specificFundamentalMockReturn = $specificFundamentalMutation->make(
            null,
            $data,
            $graphQLContext
        );

        $this->assertEquals($specificFundamentalMock, $specificFundamentalMockReturn);
    }

    public function specificFundamentalProvider()
    {
        return [
            'send data create, success' => [
                'data' => [
                    'id' => null,
                    'name' => 'Teste',
                    'fundamental_id' => [1],
                    'user_id' => 1,
                ],
                'method' => 'create',
            ],
            'send data edit, success' => [
                'data' => [
                    'id' => 1,
                    'name' => 'Teste',
                    'fundamental_id' => [1],
                    'user_id' => 1,
                ],
                'method' => 'update',
            ],
        ];
    }

    /**
     * A basic unit test in delete specificFundamental.
     *
     * @dataProvider specificFundamentalDeleteProvider
     *
     * @test
     *
     * @return void
     */
    public function specificFundamentalDelete($data, $numberFind, $numberDelete)
    {
        $graphQLContext = $this->createMock(GraphQLContext::class);
        
        $specificFundamental = $this->mock(SpecificFundamental::class, function (MockInterface $mock) use ($data, $numberFind, $numberDelete) {
            $mock->shouldReceive('findOrFail')
                ->times($numberFind)
                ->with(1)
                ->andReturn($mock);

            if(count($data) > 1) {
                $mock->shouldReceive('findOrFail')
                    ->times($numberFind)
                    ->with(2)
                    ->andReturn($mock);
            }
            
            $mock->shouldReceive('delete')
                ->times($numberDelete)
                ->andReturn(true);
        });

        $specificFundamentalMutation = new SpecificFundamentalMutation($specificFundamental);
        $specificFundamentalMutation->delete(
            null,
            [
                'id' => $data,
            ],
            $graphQLContext
        );
    }

    public function specificFundamentalDeleteProvider()
    {
        return [
            'send data delete, success' => [
                'data' => [1],
                'numberFind' => 1,
                'numberDelete' => 1,
            ],
            'send data delete multiple specific fundamentals, success' => [
                'data' => [1, 2],
                'numberFind' => 1,
                'numberDelete' => 2,
            ],
            'send data delete no items, success' => [
                'data' => [],
                'numberFind' => 0,
                'numberDelete' => 0,
            ],
        ];
    }
}
