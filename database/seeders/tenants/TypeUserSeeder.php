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
        TypeUser::create([
            'id' => 1,
            'name' => 'Administrador',
        ]);

        TypeUser::create([
            'id' => 2,
            'name' => 'Técnico',
        ]);

        TypeUser::create([
            'id' => 3,
            'name' => 'Jogador',
        ]);
    }
}
