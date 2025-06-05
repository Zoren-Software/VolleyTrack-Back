<?php

namespace App\GraphQL\Queries;

use App\Models\Language;
use Illuminate\Database\Eloquent\Builder;

class LanguageQuery
{
    /**
     * @param  mixed  $_
     * @param  array{}  $args
     * @return Builder<Language>
     */
    public function list($_, array $args): Builder
    {
        $language = new Language;

        return $language->list($args);
    }
}
