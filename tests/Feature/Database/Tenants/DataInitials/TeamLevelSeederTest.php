<?php

namespace Tests\Feature\Database\Tenants\DataInitials;

use App\Models\TeamLevel;
use Database\Seeders\Tenants\TeamLevelTableSeeder;
use Illuminate\Support\Facades\DB;

class TeamLevelSeederTest extends DataAbstract
{
    protected function setUp(): void
    {
        parent::setUp();

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        TeamLevel::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->seed([
            TeamLevelTableSeeder::class,
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function team_levels_are_seeded(): void
    {
        $expectedLevels = [
            'Bronze',
            'Prata',
            'Ouro',
            'Elite',
        ];

        foreach ($expectedLevels as $level) {
            $this->assertDatabaseHas('team_levels', ['name' => $level]);
        }

        $this->assertDatabaseCount('team_levels', count($expectedLevels));
    }
}
