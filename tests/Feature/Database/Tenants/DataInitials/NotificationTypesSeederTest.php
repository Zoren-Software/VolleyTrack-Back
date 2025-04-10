<?php

namespace Tests\Feature\Database\Tenants\DataInitials;

use App\Models\NotificationType;
use App\Models\User;
use Database\Seeders\Tenants\UserTableSeeder;
use Illuminate\Support\Facades\DB;

class NotificationTypesSeederTest extends DataAbstract
{
    public function setUp(): void
    {
        parent::setUp();

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        User::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->seed([
            UserTableSeeder::class,
        ]);
    }

    /**
     * @test
     */
    public function notificationTypesAreSeeded(): void
    {
        $expectedKeys = [
            'account_confirmation',
            'training_created',
            'training_cancelled',
        ];

        foreach ($expectedKeys as $key) {
            $this->assertDatabaseHas('notification_types', ['key' => $key]);
        }

        $this->assertDatabaseCount('notification_types', count($expectedKeys));
    }

    /**
     * @test
     */
    public function notificationSettingsAreCreatedForEachUser(): void
    {
        $types = NotificationType::where('is_active', true)->get();

        User::chunk(100, function ($users) use ($types) {
            foreach ($users as $user) {
                foreach ($types as $type) {
                    $this->assertDatabaseHas('notification_settings', [
                        'user_id' => $user->id,
                        'notification_type_id' => $type->id,
                    ]);
                }
            }
        });
    }
}
