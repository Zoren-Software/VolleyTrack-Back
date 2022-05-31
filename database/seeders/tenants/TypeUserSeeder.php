<?php

namespace Database\Seeders;

use App\Models\TypeUser;
use Illuminate\Database\Seeder;

class TypeUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TypeUser::updateOrCreate(
            [
                'id' => 1,
            ],
            [
                'name' => 'Administrador',
            ]
        );

        TypeUser::updateOrCreate(
            [
                'id' => 2,
            ],
            [
                'name' => 'Técnico',
            ]
        );

        TypeUser::updateOrCreate(
            [
                'id' => 3
            ],
            [
                'name' => 'Jogador',
            ]
        );
    }
}
