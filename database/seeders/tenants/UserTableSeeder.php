<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Criando e-mail admin do suporte

        $user = \App\Models\User::updateOrCreate([
            'name' => 'Suporte',
            'email' => 'suporte@voleiclub.com.br', 
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
    }
}
