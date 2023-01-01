<?php

namespace App\GraphQL\Mutations;

use App\Models\Config;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class ConfigMutation
{
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function make($rootValue, array $args, GraphQLContext $context)
    {
        $this->config = $this->config->find(1);
        $this->config->update($args);

        return $this->config;
    }
}
