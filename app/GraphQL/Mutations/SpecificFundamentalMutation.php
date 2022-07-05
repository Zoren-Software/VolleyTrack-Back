<?php

namespace App\GraphQL\Mutations;

use App\Models\SpecificFundamental;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class SpecificFundamentalMutation
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function create($rootValue, array $args, GraphQLContext $context)
    {
        $specificFundamental = new SpecificFundamental();
        $specificFundamental->name = $args['name'];
        $specificFundamental->user_id = $args['user_id'];
        $specificFundamental->save();

        //TODO - Adicionar o relacionamento com o Fundamento
        // Testar esse codigo feito pelo Copilot

        $fundamental = Fundamental::find($args['fundamental_id']);
        $fundamental->specificFundamentals()->save($specificFundamental);

        return $specificFundamental;
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function edit($rootValue, array $args, GraphQLContext $context)
    {
        $specificFundamental = SpecificFundamental::find($args['id']);
        $specificFundamental->name = $args['name'];
        $specificFundamental->user_id = $args['user_id'];
        $specificFundamental->save();

        return $specificFundamental;
    }
}
