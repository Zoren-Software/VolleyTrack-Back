<?php

namespace App\GraphQL\Mutations;

use App\Models\SpecificFundamental;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class SpecificFundamentalMutation
{
    public function __construct(SpecificFundamental $specificFundamental)
    {
        $this->specificFundamental = $specificFundamental;
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function make($rootValue, array $args, GraphQLContext $context)
    {
        // dump($this->specificFundamental->fundamentals());
        // if (isset($args['id'])) {
        //     $this->specificFundamental = $this->specificFundamental->find($args['id']);
        //     $this->specificFundamental->update($args);
        // } else {
        //     $this->specificFundamental->create($args);

        // TODO - Tentar continuar testes com essa função
        $this->specificFundamental->updateOrCreate(
            ['id' => $args['id']],
            $args
        );
        //dd($args['id']);

        dd($this->specificFundamental->fundamentals());

        if (isset($args['fundamental_id'])) {
            $this->specificFundamental->fundamentals()->syncWithoutDetaching($args['fundamental_id']);
        }

        return $this->specificFundamental;
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function delete($rootValue, array $args, GraphQLContext $context)
    {
        $specificFundamentals = [];
        foreach ($args['id'] as $id) {
            $this->specificFundamental = $this->specificFundamental->deleteSpecificFundamental($id);
            $specificFundamentals[] = $this->specificFundamental;
        }

        return $specificFundamentals;
    }
}
