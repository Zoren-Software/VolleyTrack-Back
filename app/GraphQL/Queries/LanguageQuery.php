<?php

namespace App\GraphQL\Queries;

use App\Models\Language;

class LanguageQuery
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function list($_, array $args)
    {
        $language = new Language;

        return $language->list($args);
    }
}
