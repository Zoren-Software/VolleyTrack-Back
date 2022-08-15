<?php

namespace Tests\Unit\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic unit test make password.
     *
     * @return void
     */
    public function test_make_password()
    {
        $password = 'password';
        $user = new User();
        $user->makePassword($password);
        $this->assertTrue(Hash::check($password, $user->password));
    }

    /**
     * A basic unit test relation positions.
     *
     * @return void
     */
    public function test_positions()
    {
        $user = new User();
        $this->assertInstanceOf(BelongsToMany::class, $user->positions());
    }

    /**
     * A basic unit test relation positions.
     *
     * @dataProvider hasPermissionsViaRolesDataProvider
     *
     * @return void
     */
    public function test_has_permissions_via_roles($namePermission, $permissions, $expected)
    {
        $user = new User();
        $this->assertEquals($expected, $user->hasPermissionsViaRoles($namePermission, $permissions));
    }

    public function hasPermissionsViaRolesDataProvider()
    {
        return [
            'has permission' => [
                'namePermission' => 'list-role-administrador',
                'permissions' => ['list-role-administrador'],
                'expected' => true,
            ],
            'has permission and with more than one permissions' => [
                'namePermission' => 'list-role-administrador',
                'permissions' => ['list-role-administrador', 'list-role-technician', 'list-role-player'],
                'expected' => true,
            ],
            'not has permission' => [
                'namePermission' => 'list-role-administrador',
                'permissions' => ['list-role-player'],
                'expected' => false,
            ],
            'not has permission and no permission' => [
                'namePermission' => 'list-role-administrador',
                'permissions' => [],
                'expected' => false,
            ],
            'not has permission and with more than one permissions' => [
                'namePermission' => 'list-role-administrador',
                'permissions' => ['list-role-technician', 'list-role-player', 'list-role-player'],
                'expected' => false,
            ],
        ];
    }

    /**
     * A basic unit test hasPermissionRole.
     *
     * @return void
     */
    public function test_has_permission_role()
    {
        $userMock = $this->createMock(User::class);
        $permissionMock = $this->createMock(Permission::class);

        $userMock
            ->expects($this->once())
            ->method('getPermissionsViaRoles')
            ->willReturn(collect([$permissionMock]));

        $this->be($userMock);

        $user = new User();
        $user->hasPermissionRole('list-role-administrador');
    }
}
