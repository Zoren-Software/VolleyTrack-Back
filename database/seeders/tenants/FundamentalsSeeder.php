<?php

namespace Database\Seeders;

use App\Models\Fundamentals;
use App\Models\SpecificFundamentals;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            ],
            [
                'id' => 2,
                'name' => 'Recepção',
            ],
            [
                'id' => 3,
                'name' => 'Levantamento',
            ],
            [
                'id' => 4,
                'name' => 'Ataque',
            ],
            [
                'id' => 5,
                'name' => 'Bloqueio',
            ],
            [
                'id' => 6,
                'name' => 'Defesa',
            ]
        ];

        /**
         * Criando fundamentos mais específicos do voleibol
         */
        $specificFundamentals = [
            [
                'id' => 1,
                'name' => 'Saque Viagem',
                'fundamental_id' => [1],
            ],
            [
                'id' => 2,
                'name' => 'Saque por Baixo',
                'fundamental_id' => [1],
            ],
            [
                'id' => 3,
                'name' => 'Saque por Cima',
                'fundamental_id' => [1],
            ],
            [
                'id' => 4,
                'name' => 'Saque Flutuante',
                'fundamental_id' => [1],
            ],
            [
                'id' => 5,
                'name' => 'Toque',
                'fundamental_id' => [2, 3, 6],
            ],
            [
                'id' => 6,
                'name' => 'Manchete',
                'fundamental_id' => [2, 3, 6],
            ],
            [
                'id' => 7,
                'name' => 'Ataque do Fundo',
                'fundamental_id' => [4],
            ],
            [
                'id' => 8,
                'name' => 'Ataque da Frente',
                'fundamental_id' => [4],
            ],
            [
                'id' => 9,
                'name' => 'Largada',
                'fundamental_id' => [4],
            ],
            [
                'id' => 10,
                'name' => 'Deslizamento',
                'fundamental_id' => [6],
            ],
            [
                'id' => 11,
                'name' => 'Defesa do Fundo - 1',
                'fundamental_id' => [6],
            ],
            [
                'id' => 12,
                'name' => 'Defesa do Fundo - 5',
                'fundamental_id' => [6],
            ],
            [
                'id' => 13,
                'name' => 'Defesa do Fundo - 6',
                'fundamental_id' => [6],
            ]
        ];

        foreach($fundamentals as $fundamental) {
            Fundamentals::updateOrCreate([
                'id' => $fundamental['id'],
            ], [
                'name' => $fundamental['name'],
                'user_id' => 1,
            ]);
        }

        foreach($specificFundamentals as $specificFundamental) {
            $model = SpecificFundamentals::updateOrCreate([
                'id' => $specificFundamental['id'],
            ], [
                'name' => $specificFundamental['name'],
                'user_id' => 1,
            ]);

            foreach($specificFundamental['fundamental_id'] as $fundamentalId) {
                $model->fundamentals()->syncWithoutDetaching($fundamentalId);
            }
        }
    }
}
