<?php

namespace Tests;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;
use Nuwave\Lighthouse\Testing\RefreshesSchemaCache;
abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    use MakesGraphQLRequests;

    use RefreshesSchemaCache;

    protected $tenancy = false;

    protected $graphql = false;

    protected $login = false;

    protected $token = '';

    protected $user = null;

    public $tenantUrl;

    public function setUp(): void
    {
        parent::setUp();

        if ($this->tenancy) {
            $this->initializeTenancy();
            $this->tenantUrl = 'http://' . env('TENANT_TEST', 'test') . '.' . env('APP_HOST');
        }

        if ($this->graphql) {
            $this->bootRefreshesSchemaCache();
            $this->loginGraphQL();
        }
    }

    public function initializeTenancy(): void
    {
        $domain = env('TENANT_TEST', 'test');

        if (!Tenant::find($domain)) {
            $tenant = Tenant::create(['id' => env('TENANT_TEST', 'test')]);
            $tenant->domains()->create(['domain' => env('TENANT_TEST', 'test') . '.' . env('APP_HOST')]);
            $domain = $tenant;
        }

        tenancy()->initialize($domain);
    }

    public function graphQL(String $nomeQueryGraphQL, array $dadosEntrada, array $dadosSaida, String $type = 'query', bool $input): object
    {
        $objectString = $this->converteDadosEmStringGraphQL($nomeQueryGraphQL, $dadosEntrada, $dadosSaida, $input);

        switch ($type) {
            case 'mutation':
                $post = [
                    'query' => " mutation { $objectString }"
                ];
                break;
            case 'query':
                $post = [
                    'query' => <<<GQL
                    {
                        $objectString
                    }
                    GQL
                ];
                break;
            default:
                break;
        }

        $headers = [
            'x-tenant' => env('TENANT_TEST', 'test'),
            'content-type' => 'application/json',
        ];

        if ($this->token != '' && $this->login) {
            $headers['Authorization'] = 'Bearer ' . $this->token;
        }

        return $this->withHeaders($headers)->postJson(
            $this->tenantUrl . '/graphql',
            $post
        );
    }

    public function loginGraphQL(): void
    {
        $user = User::factory()->make();
        $user->save();
        
        $response = $this->graphQL(
            'login', 
            [
                'email' => $user->email,
                'password' => 'password',
            ], 
            ['token'], 
            'mutation', 
            true
        );

        $this->user = $user;

        $this->token = $response->json()['data']['login']['token'];
    }

    private function converteDadosEmStringGraphQL(String $nomeQueryGraphQL, array $dadosEntrada, array $dadosSaida, $input): String
    {
        $inputOpen = $input ? '( input: {' : '{';
        $inputClose = $input ? '})' : '}';

        $query = "$nomeQueryGraphQL $inputOpen";

        foreach ($dadosEntrada as $key => $value) {

            if (is_array($value)) {
                $query .= $this->converteDadosArray($query, $key, $value, $input);
            } else if($value) {
                $query .=  $this->converteDadosString($query, $key, $value, $input);
            } 
        }

        $closeOpen = $input ? '{' : '';
        $closeExit = $input ? '}' : '';

        $query .= "{$inputClose}{$closeOpen}";

        foreach ($dadosSaida as $value) {
            $query .= " $value ";
        }

        $query .= "{$closeExit}";

        return $query;
    }

    private function converteDadosArray(String $query, String $key, array $value, Bool $input): String
    {
        if($input) {
            $query .= "    {$key}: [";
            $count = 0;
            $total = count($value);
            foreach ($value as $value2) {
                $count++;
                $virgula = $count < $total ? ', ' : '';
                $query .= "{$value2}{$virgula}";
            }
            $query .= "]";
        } else {
            $query .= "    {$key}: [";
            $count = 0;
            $total = count($value);
            foreach ($value as $value2) {
                $count++;
                $virgula = $count < $total ? ', ' : '';
                $query .= "{$value2}{$virgula}";
            }
            $query .= "]";
        }

        return $query;
    }

    private function converteDadosString(String $query, $key, $value, Bool $input): String
    {
        return $input ? $key . ": " . '"' . $value . '" ' : $value . " ";
    }
}
