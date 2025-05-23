<?php

namespace Database\Seeders\Tenants;

use App\Models\TeamCategory;
use Illuminate\Database\Seeder;

class TeamCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teamCategories = [
            ['name' => 'Sub-13', 'description' => 'Atletas até 13 anos', 'min_age' => 0, 'max_age' => 13],
            ['name' => 'Sub-15', 'description' => 'Atletas de 14 a 15 anos', 'min_age' => 14, 'max_age' => 15],
            ['name' => 'Sub-17', 'description' => 'Atletas de 16 a 17 anos', 'min_age' => 16, 'max_age' => 17],
            ['name' => 'Sub-19', 'description' => 'Atletas de 18 a 19 anos', 'min_age' => 18, 'max_age' => 19],
            ['name' => 'Adulto', 'description' => 'A partir de 20 anos', 'min_age' => 20, 'max_age' => null],
            ['name' => 'Master', 'description' => 'Veteranos acima de 35 anos', 'min_age' => 35, 'max_age' => null],
        ];

        foreach ($teamCategories as $categoria) {
            TeamCategory::updateOrCreate(
                ['name' => $categoria['name']],
                $categoria
            );
        }
    }
}
