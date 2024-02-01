<?php

namespace App\GraphQL\Mutations;

use App\Models\Config;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class ConfigMutation
{
    private Config $config;

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

        $args['user_id'] = $context->user()->id;

        $this->config->update($args);

        return $this->config;
    }
}
