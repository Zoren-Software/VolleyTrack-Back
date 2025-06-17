<?php

namespace Database\Seeders;

use App\Models\Central\ExternalAccessToken;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ExternalAccessTokenTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * NOTE - Criando token se já não existir
         */
        $token = ExternalAccessToken::find(1);

        // Se o registro não existir, então criamos um novo
        if (is_null($token)) {
            ExternalAccessToken::create([
                'id' => 1,
                'token' => Hash::make('123'),
            ]);
        }
    }
}
