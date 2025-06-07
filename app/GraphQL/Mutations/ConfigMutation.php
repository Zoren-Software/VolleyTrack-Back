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
     * @param  mixed  $rootValue
     * @param  array<string, mixed>  $args
     */
    public function make($rootValue, array $args, GraphQLContext $context): Config
    {
        $this->config = $this->config->findOrFail(1);

        $user = $context->user();
        if (!$user) {
            throw new \Exception('User not authenticated.');
        }

        $args['user_id'] = $user->getAuthIdentifier();

        $this->config->update($args);

        return $this->config;
    }
}
