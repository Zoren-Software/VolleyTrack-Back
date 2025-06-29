<?php

namespace Tests\Feature\Database\Tenants\DataInitials;

use App\Models\TeamCategory;
use Database\Seeders\Tenants\TeamCategoryTableSeeder;
use Illuminate\Support\Facades\DB;

class TeamCategorySeederTest extends DataAbstract
{
    protected function setUp(): void
    {
        parent::setUp();

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        TeamCategory::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->seed([
            TeamCategoryTableSeeder::class,
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function team_categories_are_seeded(): void
    {
        $expectedCategories = [
            'Sub-13',
            'Sub-15',
            'Sub-17',
            'Sub-19',
            'Adulto',
            'Master',
        ];

        foreach ($expectedCategories as $category) {
            $this->assertDatabaseHas('team_categories', ['name' => $category]);
        }

        $this->assertDatabaseCount('team_categories', count($expectedCategories));
    }
}
