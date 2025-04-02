<?php

namespace Database\Seeders\Tenants;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\NotificationType;
use App\Models\NotificationSetting;

class NotificationSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $users = User::all();
        $types = NotificationType::where('is_active', true)->get();

        foreach ($users as $user) {
            foreach ($types as $type) {
                NotificationSetting::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'notification_type_id' => $type->id,
                    ],
                    [
                        'via_email' => true,
                        'via_system' => $type->allow_system,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
