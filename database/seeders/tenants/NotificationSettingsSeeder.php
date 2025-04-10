<?php

namespace Database\Seeders\Tenants;

use App\Models\NotificationSetting;
use App\Models\NotificationType;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
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
