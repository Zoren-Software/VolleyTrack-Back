<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Criando e-mail admin e suporte

        \App\Models\User::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'Suporte',
                'email' => env('MAIL_FROM_ADDRESS'),
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]
        );

        \App\Models\User::updateOrCreate(
            ['id' => 2],
            [
                'name' => 'Administrador',
                'email' => env('MAIL_FROM_ADMIN'),
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]
        );

        //Criando e-mail de teste
        if (env('APP_DEBUG')) {
            \App\Models\User::updateOrCreate(
                ['id' => 3],
                [
                    'name' => 'Usuário Teste Técnico',
                    'email' => env('MAIL_FROM_TEST_TECHNICIAN'),
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'remember_token' => Str::random(10),
                ]
            );

            \App\Models\User::updateOrCreate(
                ['id' => 4],
                [
                    'name' => 'Usuário Teste Jogador',
                    'email' => env('MAIL_FROM_TEST_PLAYER'),
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'remember_token' => Str::random(10),
                ]
            );

            \App\Models\User::updateOrCreate(
                ['id' => 5],
                [
                    'name' => 'Usuário Sem Permissao',
                    'email' => env('MAIL_FROM_NO_PERMISSION'),
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'remember_token' => Str::random(10),
                ]
            );
        }
    }
}
