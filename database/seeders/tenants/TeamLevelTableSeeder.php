<?php

namespace Database\Seeders\Tenants;

use App\Models\TeamLevel;
use Illuminate\Database\Seeder;

class TeamLevelTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teamLevels = [
            ['name' => 'Bronze', 'description' => 'Nível inicial de competição'],
            ['name' => 'Prata', 'description' => 'Nível intermediário de competição'],
            ['name' => 'Ouro', 'description' => 'Nível avançado de competição'],
            ['name' => 'Elite', 'description' => 'Nível de alta performance ou seleções'],
        ];

        foreach ($teamLevels as $level) {
            TeamLevel::updateOrCreate(
                ['name' => $level['name']],
                $level
            );
        }
    }
}
