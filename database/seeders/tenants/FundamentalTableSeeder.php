<?php

namespace Database\Seeders\Tenants;

use App\Models\Fundamental;
use App\Models\SpecificFundamental;
use Illuminate\Database\Seeder;

class FundamentalTableSeeder extends Seeder
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
            1 => 'Saque',
            2 => 'Recepção',
            3 => 'Levantamento',
            4 => 'Ataque',
            5 => 'Bloqueio',
            6 => 'Defesa',
        ];

        /**
         * Criando fundamentos mais específicos do voleibol
         */
        $specificFundamentals = [
            1 => [
                'name' => 'Saque Viagem',
                'fundamental_id' => [1],
            ],
            2 => [
                'name' => 'Saque por Baixo',
                'fundamental_id' => [1],
            ],
            3 => [
                'name' => 'Saque por Cima',
                'fundamental_id' => [1],
            ],
            4 => [
                'name' => 'Saque Flutuante',
                'fundamental_id' => [1],
            ],
            5 => [
                'name' => 'Toque',
                'fundamental_id' => [2, 3, 6],
            ],
            6 => [
                'name' => 'Manchete',
                'fundamental_id' => [2, 3, 6],
            ],
            7 => [
                'name' => 'Ataque do Fundo',
                'fundamental_id' => [4],
            ],
            8 => [
                'name' => 'Ataque da Frente',
                'fundamental_id' => [4],
            ],
            9 => [
                'name' => 'Largada',
                'fundamental_id' => [4],
            ],
            10 => [
                'name' => 'Deslizamento',
                'fundamental_id' => [6],
            ],
            11 => [
                'name' => 'Defesa do Fundo - 1',
                'fundamental_id' => [6],
            ],
            12 => [
                'name' => 'Defesa do Fundo - 5',
                'fundamental_id' => [6],
            ],
            13 => [
                'name' => 'Defesa do Fundo - 6',
                'fundamental_id' => [6],
            ]
        ];

        foreach ($fundamentals as $id => $fundamental) {
            Fundamental::updateOrCreate([
                'id' => $id,
            ], [
                'name' => $fundamental,
                'user_id' => 1,
            ]);
        }

        foreach ($specificFundamentals as $id => $specificFundamental) {
            $model = SpecificFundamental::updateOrCreate([
                'id' => $id,
            ], [
                'name' => $specificFundamental['name'],
                'user_id' => 1,
            ]);

            foreach ($specificFundamental['fundamental_id'] as $fundamentalId) {
                $model->fundamentals()->syncWithoutDetaching($fundamentalId);
            }
        }
    }
}
