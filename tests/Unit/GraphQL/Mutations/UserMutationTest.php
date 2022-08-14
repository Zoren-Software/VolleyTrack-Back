<?php

namespace Tests\Unit\GraphQL\Mutations;

use App\GraphQL\Mutations\UserMutation;
use App\Models\User;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use PHPUnit\Framework\TestCase;

class UserMutationTest extends TestCase
{
    /**
     * A basic unit test in create user.
     *
     * @dataProvider createUserProvider
     *
     * @return void
     */
    public function test_user_create($method)
    {
        $graphQLContext = $this->createMock(GraphQLContext::class);
        $user = $this->createMock(User::class);

        $user->expects($this->once())
            ->method($method);

        $userMutation = new UserMutation($user);

        $userMutation->create(
            null,
            [
                'name' => 'Teste',
                'email' => 'teste@gmail.com',
                'password' => '123456',
                'roleId' => 1,
            ],
            $graphQLContext
        );
    }

    public function createUserProvider()
    {
        return [
            'using method save' => [
                'method' => 'save',
            ],
            'using method makePassword' => [
                'method' => 'makePassword',
            ],
            'using method roles' => [
                'method' => 'roles',
            ],
        ];
    }

    /**
     * A basic unit test in edit user.
     *
     * @dataProvider editUserProvider
     * @return void
     */
    public function test_user_edit($method)
    {
        $graphQLContext = $this->createMock(GraphQLContext::class);
        $user = $this->createMock(User::class);

        $user->expects($this->once())
            ->method($method);

        $userMutation = new UserMutation($user);

        $userMutation->edit(
            null,
            [
                'id' => 1,
                'name' => 'Teste',
                'email' => 'teste@gmail.com',
                'password' => '123456',
                'roleId' => 1,
            ],
            $graphQLContext
        );
    }

    public function editUserProvider()
    {
        return [
            'using method makePassword' => [
                'method' => 'makePassword',
            ],
            'using method save' => [
                'method' => 'save',
            ],
            'using method roles' => [
                'method' => 'roles',
            ],
        ];
    }
}
