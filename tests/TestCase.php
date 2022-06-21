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

    protected $email = null;

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

    public function graphQL(String $nomeQueryGraphQL, array $dadosEntrada, array $dadosSaida, String $type = 'query', Bool $input, Bool $parametrosEntrada = false): object
    {
        $objectString = $this->converteDadosEmStringGraphQL($nomeQueryGraphQL, $dadosEntrada, $dadosSaida, $input, $type, $parametrosEntrada);

        switch ($type) {
            case 'mutation':
                $post = [
                    'query' => "mutation { $objectString }"
                ];
                break;
            case 'query':
                $post = [
                    'query' => " { $objectString }"

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
        if (env('EMAIL_FROM_TEST_TECHNICIAN') != '') {
            $user = User::where('email', env('EMAIL_FROM_TEST_TECHNICIAN'))->first();
        } else {
            $user = User::factory()->make();
            $user->save();
        }

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

    private function converteDadosEmStringGraphQL(String $nomeQueryGraphQL, array $dadosEntrada, array $dadosSaida, $input, $type, Bool $parametrosEntrada): String
    {
        if ($input) {
            $inputOpen = '( input: {';
            $inputClose = '} )';
        } else {
            if ($type == 'mutation') {
                $inputOpen = '{';
                $inputClose = '';

                if ($parametrosEntrada) {
                    $inputOpen = '(';
                    $inputClose = ') {';
                }
            } else {
                $inputOpen = '(';
                $inputClose = ') {';
            }
        }

        $query = "$nomeQueryGraphQL $inputOpen";

        foreach ($dadosEntrada as $key => $value) {
            if (is_array($value)) {
                $query .= $this->converteDadosArrayEntrada($query, $key, $value, $input, $type);
            } elseif ($value) {
                $query .= $this->converteDadosString($query, $key, $value, $input, $type, $parametrosEntrada);
            }
        }

        $closeOpen = $input ? '{' : '';
        $closeExit = $input ? '}' : '}';

        $query .= "{$inputClose}{$closeOpen}";

        foreach ($dadosSaida as $key => $value) {
            if (is_array($value)) {
                $total = count($value);
                $count = 0;

                foreach ($value as $newValue) {
                    if ($count == 0) {
                        $query .= " $key {";
                    }
                    $query .= " $newValue";
                    $count++;
                    if ($count == $total) {
                        $query .= '}';
                    }
                }
            } else {
                $query .= " $value ";
            }
        }

        $query .= "{$closeExit}";

        return $query;
    }

    private function converteDadosArrayEntrada(String $query, String $key, array $value, Bool $input): String
    {
        if ($input) {
            $query .= "    {$key}: [";
            $count = 0;
            $total = count($value);
            foreach ($value as $value2) {
                $count++;
                $virgula = $count < $total ? ', ' : '';
                $query .= "{$value2}{$virgula}";
            }
            $query .= ']';
        } else {
            $query .= "    {$key}: [";
            $count = 0;
            $total = count($value);
            foreach ($value as $value2) {
                $count++;
                $virgula = $count < $total ? ', ' : '';
                $query .= "{$value2}{$virgula}";
            }
            $query .= ']';
        }

        return $query;
    }

    private function converteDadosString(String $query, $key, $value, Bool $input, String $type, Bool $receberComoParametro): String
    {
        if ($input || $type == 'query') {
            if (is_int($value)) {
                return $key . ': ' . $value . ' ';
            }
            return $key . ': ' . '"' . $value . '" ';
        } elseif ($receberComoParametro) {
            if (is_int($value)) {
                return $key . ': ' . $value . ' ';
            }
            return $key . ': ' . '"' . $value . '" ';
        }
        return $value . ' ';
    }
}
