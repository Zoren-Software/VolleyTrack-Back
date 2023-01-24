<?php

namespace Tests\Unit\App\GraphQL\Mutations;

use App\GraphQL\Mutations\UserMutation;
use App\Models\Position;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Mockery\MockInterface;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Tests\TestCase;

class UserMutationTest extends TestCase
{
    /**
     * A basic unit test create and edit user.
     *
     * @dataProvider userProvider
     *
     * @test
     *
     * @return void
     */
    public function userMake($data)
    {
        $graphQLContext = $this->createMock(GraphQLContext::class);
        $userMock = $this->mock(User::class, function (MockInterface $mock) use ($data) {
            $role = $this->createMock(BelongsToMany::class);
            $position = $this->createMock(Position::class);
            $team = $this->createMock(Team::class);

            if (isset($data['id'])) {
                $mock->shouldReceive('findOrFail')
                    ->once()
                    ->with($data['id'])
                    ->andReturn($mock);
            }

            $mock->shouldReceive('setAttribute')
                ->with('name', $data['name'])
                ->once()
                ->andReturn($mock);

            $mock->shouldReceive('setAttribute')
                ->with('email', $data['email'])
                ->once()
                ->andReturn($mock);

            $mock->shouldReceive('makePassword')
                ->with($data['password'])
                ->once()
                ->andReturn($mock);

            $mock->shouldReceive('save')
                ->once()
                ->andReturn($mock);

            $mock->shouldReceive('roles')
                ->once()
                ->andReturn($role);
            $mock->shouldReceive('syncWithoutDetaching')
                ->with([$role]);

            $mock->shouldReceive('positions')
                ->once()
                ->andReturn($position);
            $mock->shouldReceive('syncWithoutDetaching')
                ->with([$position]);

            $mock->shouldReceive('teams')
                ->once()
                ->andReturn($team);

            $mock->shouldReceive('syncWithoutDetaching')
                ->with([$team]);
        });

        $specificFundamentalMutation = new UserMutation($userMock);
        $userReturn = $specificFundamentalMutation->make(
            null,
            $data,
            $graphQLContext
        );

        $this->assertEquals($userMock, $userReturn);
    }

    public function userProvider()
    {
        return [
            'send data create with all options, success' => [
                'data' => [
                    'id' => null,
                    'name' => 'Teste',
                    'email' => 'test@example.com',
                    'password' => '123456',
                    'roleId' => [1],
                    'positionId' => [1],
                    'teamId' => [1],
                ],
            ],
            'send data edit with all options, success' => [
                'data' => [
                    'id' => 1,
                    'name' => 'Teste',
                    'email' => 'test@example.com',
                    'password' => '123456',
                    'roleId' => [1],
                    'positionId' => [1],
                    'teamId' => [1],
                ],
            ],
        ];
    }

    /**
     * A basic unit test in delete user.
     *
     * @dataProvider userDeleteProvider
     *
     * @test
     *
     * @return void
     */
    public function userDelete($data, $numberFind, $numberDelete)
    {
        $graphQLContext = $this->createMock(GraphQLContext::class);
        $user = $this->mock(User::class, function (MockInterface $mock) use ($data, $numberFind, $numberDelete) {
            $mock->shouldReceive('findOrFail')
                ->times($numberFind)
                ->with(1)
                ->andReturn($mock);

            if (count($data) > 1) {
                $mock->shouldReceive('findOrFail')
                    ->times(1)
                    ->with(2)
                    ->andReturn($mock);
            }

            $mock->shouldReceive('delete')
                ->times($numberDelete)
                ->andReturn(true);
        });

        $userMutation = new UserMutation($user);
        $userMutation->delete(
            null,
            [
                'id' => $data,
            ],
            $graphQLContext
        );
    }

    public function userDeleteProvider()
    {
        return [
            'send data delete, success' => [
                'data' => [1],
                'numberFind' => 1,
                'numberDelete' => 1,
            ],
            'send data delete multiple users, success' => [
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
