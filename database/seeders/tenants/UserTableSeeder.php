<?php

namespace Database\Seeders\Tenants;

use App\Models\NotificationSetting;
use App\Models\NotificationType;
use App\Models\User;
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
        /*
         * Criando e-mails admin e suporte
         */
        $usersDefault = [
            [
                'id' => 1,
                'name' => 'Administrador',
                'email' => config('mail.from_admin'),
            ],
            [
                'id' => 2,
                'name' => 'Suporte',
                'email' => config('mail.from.address'),
            ],
        ];

        /*
         * Criando e-mails para testes
         */
        if (config('app.debug') && config('app.env') === 'local') {
            $usersDefault[] = [
                'id' => 3,
                'name' => 'Usuário Teste Técnico',
                'email' => config('mail.from_test_technician'),
            ];

            $usersDefault[] = [
                'id' => 4,
                'name' => 'Usuário Teste Jogador',
                'email' => config('mail.from_test_player'),
            ];

            $usersDefault[] = [
                'id' => 5,
                'name' => 'Usuário Sem Permissao',
                'email' => config('mail.from_no_permission'),
            ];
        }

        foreach ($usersDefault as $user) {
            $user = User::updateOrCreate(
                ['id' => $user['id']],
                [
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'password' => Hash::make('password'),
                    'remember_token' => Hash::make(Str::random(10)),
                    'set_password_token' => Str::random(60),
                ]
            );

            $user->information()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'cpf' => '000000000' . $user->id,
                    'phone' => '00000000000' . $user->id,
                    'rg' => '000000000' . $user->id,
                    'birth_date' => '1998-01-06',
                ]
            );

            $types = NotificationType::where('is_active', true)->get();

            foreach ($types as $type) {
                NotificationSetting::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'notification_type_id' => $type->id,
                    ],
                    [
                        'via_email' => false,
                        'via_system' => $type->allow_system,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
