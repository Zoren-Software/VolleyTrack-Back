<?php

namespace Database\Seeders\Tenants;

use App\Models\Config;
use Illuminate\Database\Seeder;

class ConfigTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $configs = [
            1 => [
                'user_id' => 1,
                'name_tenant' => 'Test',
                'language_id' => 1,
            ],
        ];

        foreach ($configs as $id => $config) {
            Config::updateOrCreate(
                [
                    'id' => $id,
                ],
                $config
            );
        }
    }
}
