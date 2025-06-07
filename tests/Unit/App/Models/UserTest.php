<?php

namespace Tests\Unit\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Hash;
use Spatie\Activitylog\LogOptions;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic unit test make password.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function make_password()
    {
        $password = 'password';
        $user = new User;
        $user->makePassword($password);
        $this->assertTrue(Hash::check($password, $user->password));
    }

    /**
     * A basic unit test relation positions.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function positions()
    {
        $user = new User;
        $this->assertInstanceOf(BelongsToMany::class, $user->positions());
    }

    /**
     * A basic unit test relation positions.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('hasPermissionsViaRolesDataProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function has_permissions_via_roles($namePermission, $permissions, $expected)
    {
        $user = new User;
        $this->assertEquals($expected, $user->hasPermissionsViaRoles($namePermission, $permissions));
    }

    public static function hasPermissionsViaRolesDataProvider()
    {
        return [
            'has permission' => [
                'namePermission' => 'view-role-admin',
                'permissions' => ['view-role-admin'],
                'expected' => true,
            ],
            'has permission and with more than one permissions' => [
                'namePermission' => 'view-role-admin',
                'permissions' => ['view-role-admin', 'list-role-technician', 'list-role-player'],
                'expected' => true,
            ],
            'not has permission' => [
                'namePermission' => 'view-role-admin',
                'permissions' => ['list-role-player'],
                'expected' => false,
            ],
            'not has permission and no permission' => [
                'namePermission' => 'view-role-admin',
                'permissions' => [],
                'expected' => false,
            ],
            'not has permission and with more than one permissions' => [
                'namePermission' => 'view-role-admin',
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
    #[\PHPUnit\Framework\Attributes\Test]
    public function has_permission_role()
    {
        $userMock = $this->createMock(User::class);
        $permissionMock = $this->createMock(Permission::class);

        $userMock
            ->expects($this->once())
            ->method('getPermissionsViaRoles')
            ->willReturn(collect([$permissionMock]));

        $this->be($userMock);

        $user = new User;
        $user->hasPermissionRole('view-role-admin');
    }

    /**
     * A basic unit test relation getActivitylogOptions.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function get_activitylog_options()
    {
        $user = new User;
        $this->assertInstanceOf(LogOptions::class, $user->getActivitylogOptions());
    }

    /**
     * A basic unit test relation teams.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function teams()
    {
        $user = new User;
        $this->assertInstanceOf(BelongsToMany::class, $user->teams());
    }

    /**
     * A basic unit test relation playerConfirmationsTraining
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function player_confirmations_training()
    {
        $user = new User;
        $this->assertInstanceOf(HasMany::class, $user->playerConfirmationsTraining());
    }

    /**
     * A basic unit test relation userConfirmationsTraining
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function user_confirmations_training()
    {
        $user = new User;
        $this->assertInstanceOf(HasMany::class, $user->userConfirmationsTraining());
    }

    /**
     * A basic unit test relation information
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function information()
    {
        $user = new User;
        $this->assertInstanceOf(HasOne::class, $user->information());
    }
}
