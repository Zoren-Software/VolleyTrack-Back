<?php

namespace App\GraphQL\Mutations;

use App\Models\Config;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class ConfigMutation
{
    private Config $config;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param  mixed  $rootValue
     * @param  array<string, mixed>  $args
     * @param GraphQLContext $context
     * 
     * @return Config
     */
    public function make($rootValue, array $args, GraphQLContext $context): Config
    {
        $this->config = $this->config->find(1);

        $args['user_id'] = $context->user()->getAuthIdentifier();

        $this->config->update($args);

        return $this->config;
    }
}
