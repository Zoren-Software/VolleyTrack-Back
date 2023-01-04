<?php

namespace Database\Seeders\Tenants;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Criar seeder para a tabela languages
        // Com todas as linguagens
        $languages = [
            1 => [
                // português
                'slug' => 'pt-BR',
                'name' => 'Português Brazil',
            ],
            2 => [
                // inglês
                'slug' => 'en',
                'name' => 'English',
            ],
            3 => [
                // espanhol
                'slug' => 'es',
                'name' => 'Español',
            ],
            4 => [
                // francês
                'slug' => 'fr',
                'name' => 'Français',
            ],
            5 => [
                // alemão
                'slug' => 'de',
                'name' => 'Deutsch',
            ],
            6 => [
                // italiano
                'slug' => 'it',
                'name' => 'Italiano',
            ],
            7 => [
                // japonês
                'slug' => 'ja',
                'name' => '日本語',
            ],
            8 => [
                // coreano
                'slug' => 'ko',
                'name' => '한국어',
            ],
            9 => [
                // russo
                'slug' => 'ru',
                'name' => 'Русский',
            ],
            10 => [
                // chinês
                'slug' => 'zh',
                'name' => '中文',
            ],
            11 => [
                // holandês
                'slug' => 'nl',
                'name' => 'Nederlands',
            ],
            12 => [
                // sueco
                'slug' => 'sv',
                'name' => 'Svenska',
            ],
            13 => [
                // dinamarquês
                'slug' => 'da',
                'name' => 'Dansk',
            ],
            14 => [
                // norueguês
                'slug' => 'no',
                'name' => 'Norsk',
            ],
            15 => [
                // finlandês
                'slug' => 'fi',
                'name' => 'Suomi',
            ],
            16 => [
                // polonês
                'slug' => 'pl',
                'name' => 'Polski',
            ],
            17 => [
                // grego
                'slug' => 'el',
                'name' => 'Ελληνικά',
            ],
            18 => [
                // turco
                'slug' => 'tr',
                'name' => 'Türkçe',
            ],
            19 => [
                // húngaro
                'slug' => 'hu',
                'name' => 'Magyar',
            ],
            20 => [
                // checo
                'slug' => 'cs',
                'name' => 'Čeština',
            ],
            21 => [
                // romeno
                'slug' => 'ro',
                'name' => 'Română',
            ],
            22 => [
                // eslovaco
                'slug' => 'sk',
                'name' => 'Slovenčina',
            ],
            23 => [
                // búlgaro
                'slug' => 'bg',
                'name' => 'Български',
            ],
            25 => [
                // árabe
                'slug' => 'ar',
                'name' => 'العربية',
            ],
            26 => [
                // hebraico
                'slug' => 'he',
                'name' => 'עברית',
            ],
            27 => [
                // vietnamita
                'slug' => 'vi',
                'name' => 'Tiếng Việt',
            ],
            28 => [
                // indonésio
                'slug' => 'id',
                'name' => 'Bahasa Indonesia',
            ],
            29 => [
                // malaio
                'slug' => 'ms',
                'name' => 'Bahasa Melayu',
            ],
            30 => [
                // tailandês
                'slug' => 'th',
                'name' => 'ภาษาไทย',
            ],
            31 => [
                // persa
                'slug' => 'fa',
                'name' => 'فارسی',
            ],
            32 => [
                // ucraniano
                'slug' => 'uk',
                'name' => 'Українська',
            ],
            33 => [
                // bielorrusso
                'slug' => 'be',
                'name' => 'Беларуская',
            ],
            34 => [
                // catalão
                'slug' => 'ca',
                'name' => 'Català',
            ],
            35 => [
                // galês
                'slug' => 'cy',
                'name' => 'Cymraeg',
            ],
            36 => [
                // esquimó
                'slug' => 'kl',
                'name' => 'Kalaallisut',
            ],
            37 => [
                // estoniano
                'slug' => 'et',
                'name' => 'Eesti',
            ],
            38 => [
                // letão
                'slug' => 'lv',
                'name' => 'Latviešu',
            ],
            39 => [
                // lituano
                'slug' => 'lt',
                'name' => 'Lietuvių',
            ],
            40 => [
                // macedônio
                'slug' => 'mk',
                'name' => 'Македонски',
            ],
            41 => [
                // sérvio
                'slug' => 'sr',
                'name' => 'Српски',
            ],
            42 => [
                // croata
                'slug' => 'hr',
                'name' => 'Hrvatski',
            ],
            43 => [
                // esloveno
                'slug' => 'sl',
                'name' => 'Slovenščina',
            ],
            44 => [
                // português
                'slug' => 'pt',
                'name' => 'Português Portugal',
            ],
        ];

        foreach ($languages as $id => $language) {
            Language::updateOrCreate(
                [
                    'id' => $id,
                ],
                $language
            );
        }
    }
}
