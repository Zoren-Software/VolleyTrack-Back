<?php

namespace Database\Seeders\Tenants;

use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * Criando posições do voleibol
         */
        $positions = [
            1 => 'Central',
            2 => 'Levantador',
            3 => 'Libero',
            4 => 'Oposto',
            5 => 'Ponteiro',
        ];

        foreach ($positions as $id => $position) {
            Position::updateOrCreate([
                'id' => $id,
            ], [
                'name' => $position,
                'user_id' => 1,
            ]);
        }
    }
}
