<?php

namespace Database\Seeders;

use App\Models\Central\ExternalAccessToken;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Domain;

class DomainsDevTableSeeder extends Seeder
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
        
        if (env('APP_ENV') !== 'production') {
            $domains = Domain::all();
            foreach ($domains as $domain) {
                if (strpos($domain->domain, '.volleytrack.com') !== false) {
                    $domain->domain = str_replace('.volleytrack.com', '.volleytrack.local', $domain->domain);
                    $domain->save();
                }
            }
        }

    }
}
