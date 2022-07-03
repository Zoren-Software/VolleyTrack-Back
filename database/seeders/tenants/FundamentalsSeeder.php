<?php

namespace Database\Seeders;

use App\Models\Fundamentals;
use App\Models\SpecificFundamentals;
use Illuminate\Database\Seeder;

class FundamentalsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * Criando fundamentos do voleibol
         */
        $fundamentals = [
            [
                'id' => 1,
                'name' => 'Saque',
                'user_id' => 1,
            ],
            [
                'id' => 2,
                'name' => 'Recepção',
                'user_id' => 1,
            ],
            [
                'id' => 3,
                'name' => 'Levantamento',
                'user_id' => 1,
            ],
            [
                'id' => 4,
                'name' => 'Ataque',
                'user_id' => 1,
            ],
            [
                'id' => 5,
                'name' => 'Bloqueio',
                'user_id' => 1,
            ],
            [
                'id' => 6,
                'name' => 'Defesa',
                'user_id' => 1,
            ]
        ];

        /**
         * Criando fundamentos mais específicos do voleibol
         */
        $specificFundamentals = [
            [
                'id' => 1,
                'name' => 'Saque Viagem',
                'user_id' => 1,
                'fundamental_id' => [1],
            ],
            [
                'id' => 2,
                'name' => 'Saque por Baixo',
                'user_id' => 1,
                'fundamental_id' => [1],
            ],
            [
                'id' => 3,
                'name' => 'Saque por Cima',
                'user_id' => 1,
                'fundamental_id' => [1],
            ],
            [
                'id' => 4,
                'name' => 'Saque Flutuante',
                'user_id' => 1,
                'fundamental_id' => [1],
            ],
            [
                'id' => 5,
                'name' => 'Toque',
                'user_id' => 1,
                'fundamental_id' => [2, 3, 6],
            ],
            [
                'id' => 6,
                'name' => 'Manchete',
                'user_id' => 1,
                'fundamental_id' => [2, 3, 6],
            ],
            [
                'id' => 7,
                'name' => 'Ataque do Fundo',
                'user_id' => 1,
                'fundamental_id' => [4],
            ],
            [
                'id' => 8,
                'name' => 'Ataque da Frente',
                'user_id' => 1,
                'fundamental_id' => [4],
            ],
            [
                'id' => 9,
                'name' => 'Largada',
                'user_id' => 1,
                'fundamental_id' => [4],
            ],
            [
                'id' => 10,
                'name' => 'Deslizamento',
                'user_id' => 1,
                'fundamental_id' => [6],
            ],
            [
                'id' => 11,
                'name' => 'Defesa do Fundo - 1',
                'user_id' => 1,
                'fundamental_id' => [6],
            ],
            [
                'id' => 12,
                'name' => 'Defesa do Fundo - 5',
                'user_id' => 1,
                'fundamental_id' => [6],
            ],
            [
                'id' => 13,
                'name' => 'Defesa do Fundo - 6',
                'user_id' => 1,
                'fundamental_id' => [6],
            ]
        ];

        foreach ($fundamentals as $fundamental) {
            Fundamentals::updateOrCreate([
                'id' => $fundamental['id'],
            ], [
                'name' => $fundamental['name'],
                'user_id' => $fundamental['user_id'],
            ]);
        }

        foreach ($specificFundamentals as $specificFundamental) {
            $model = SpecificFundamentals::updateOrCreate([
                'id' => $specificFundamental['id'],
            ], [
                'name' => $specificFundamental['name'],
                'user_id' => $specificFundamental['user_id'],
            ]);

            foreach ($specificFundamental['fundamental_id'] as $fundamentalId) {
                $model->fundamentals()->syncWithoutDetaching($fundamentalId);
            }
        }
    }
}
