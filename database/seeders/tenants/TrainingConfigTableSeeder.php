<?php

namespace Database\Seeders\Tenants;

use App\Models\TrainingConfig;
use Illuminate\Database\Seeder;

class TrainingConfigTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $trainingConfigs = [
            1 => [
                'user_id' => 1,
                'config_id' => 1,
                'days_notification' => 1,
                'notification_team_by_email' => true,
                'notification_technician_by_email' => true,
            ],
        ];

        foreach ($trainingConfigs as $id => $config) {
            TrainingConfig::updateOrCreate(
                [
                    'id' => $id,
                ],
                $config
            );
        }
    }
}
