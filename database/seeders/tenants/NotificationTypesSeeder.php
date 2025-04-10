<?php

namespace Database\Seeders\Tenants;

use App\Models\NotificationType;
use Illuminate\Database\Seeder;

class NotificationTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = [
            [
                'key' => 'account_confirmation',
                'description' => 'Confirmação de e-mail da conta',
            ],
            [
                'key' => 'training_created',
                'description' => 'Novo treino criado',
            ],
            [
                'key' => 'training_cancelled',
                'description' => 'Treino cancelado',
            ],
        ];

        foreach ($tipos as $tipo) {
            NotificationType::updateOrCreate(
                ['key' => $tipo['key']],
                [
                    'description' => $tipo['description'],
                    'allow_email' => true,
                    'allow_system' => in_array($tipo['key'], ['training_created', 'training_cancelled']),
                    'is_active' => true,
                ]
            );
        }
    }
}
