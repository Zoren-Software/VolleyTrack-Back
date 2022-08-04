<?php

namespace Tests\Unit\GraphQL;

use App\GraphQL\Mutations\UserMutation;
use App\Models\User;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use PHPUnit\Framework\TestCase;

class UserMutationTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_user_create()
    {
        $graphQLContext = $this->createMock(GraphQLContext::class);
        $user = $this->createMock(User::class);

        $user->expects($this->once())
            ->method('save')
            ->willReturn(true);

        $userMutation = new UserMutation($user);

        $userMutation->create(null, [
            'name' => 'Teste',
            'email' => 'teste@gmail.com',
            'password' => '123456',
            'roleId' => 1,
        ], $graphQLContext);
    }
}
