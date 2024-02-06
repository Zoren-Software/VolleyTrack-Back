<?php

namespace Tests;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;
use Nuwave\Lighthouse\Testing\RefreshesSchemaCache;
use Spatie\Permission\Models\Role;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use MakesGraphQLRequests;
    use RefreshesSchemaCache;

    protected $tenancy = false;

    protected $tenant = 'test';

    protected $graphql = false;

    protected $login = false;

    protected $email = null;

    protected $token = '';

    protected $user = null;

    protected $otherUser = false;

    public static $paginatorInfo = [
        'count',
        'currentPage',
        'firstItem',
        'hasMorePages',
        'lastItem',
        'lastPage',
        'perPage',
        'total',
    ];

    public static $errors = [
        '*' => [
            'message',
            'locations',
            'extensions',
            'path',
            'trace',
        ],
    ];

    public static $formatDate = 'Y-m-d H:i:s';

    public static $unauthorized = 'This action is unauthorized.';

    public $tenantUrl;

    public function setUp(): void
    {
        parent::setUp();

        if ($this->tenancy) {
            $this->tenant = $this->tenant ?? env('TENANT_TEST', 'test');

            $this->initializeTenancy();
            $protocol = env('APP_ENV') === 'local' ? 'http' : 'https';
            $this->tenantUrl = $protocol . '://' . $this->tenant . '.' . env('APP_HOST');
        }

        if ($this->graphql) {
            $this->bootRefreshesSchemaCache();
            $this->loginGraphQL();
        }
    }

    public function initializeTenancy(): void
    {
        $tenantId = env('TENANT_TEST', 'test');
        $tenantIdLogs = $tenantId . '_logs';

        Artisan::call('migrate --seed');

        if (!Tenant::find($tenantId)) {
            $tenant = Tenant::create(['id' => $tenantId]);
            Tenant::create(['id' => $tenantIdLogs]);
            $tenant->domains()->create(['domain' => $tenantId . '.' . env('APP_HOST')]);

            try {
                Artisan::call("multi_tenants:migrate --tenants {$tenantId} --path base");
                Artisan::call("multi_tenants:migrate --tenants {$tenantId}");
                Artisan::call("multi_tenants_logs:migrate --tenants {$tenantIdLogs}");
                Artisan::call("multi_tenants:seed --tenants {$tenantId}");
            } catch (\Exception $e) {
                throw new \Exception($e->getMessage());
            }
        } else {
            tenancy()->initialize($tenantId);

            try {
                // NOTE - Se o ambiente não tiver sido inicializado, o comando abaixo irá falhar
                //        e o catch irá inicializar o ambiente, apenas fazendo este processo para o primeiro teste
                DB::table('migrations')->get();
            } catch (\Exception $e) {
                try {
                    Artisan::call("multi_tenants:migrate --tenants {$tenantId} --path base");
                    Artisan::call("multi_tenants:migrate --tenants {$tenantId}");
                    Artisan::call("multi_tenants_logs:migrate --tenants {$tenantIdLogs}");
                    Artisan::call("multi_tenants:seed --tenants {$tenantId}");
                } catch (\Exception $e) {
                    throw new \Exception($e->getMessage());
                }
            }
        }
        tenancy()->initialize($tenantId);
    }

    public function graphQL(
        string $nomeQueryGraphQL,
        array $dadosEntrada,
        array $dadosSaida,
        string $type,
        bool $input,
        bool $parametrosEntrada = false
    ): object {
        $objectString = $this->converteDadosEmStringGraphQL($nomeQueryGraphQL, $dadosEntrada, $dadosSaida, $input, $type, $parametrosEntrada);

        switch ($type) {
            case 'mutation':
                $post = [
                    'query' => "mutation { $objectString }",
                ];
                break;
            case 'query':
                $post = [
                    'query' => " { $objectString }",

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
        if ($this->otherUser) {
            $user = User::factory()->make();
            $user->save();
        } else {
            $user = User::where('email', env('MAIL_FROM_TEST_TECHNICIAN'))->first();
        }

        $user->password = Hash::make('password');
        $user->save();

        $this->email = $user->email;

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

    private function converteDadosEmStringGraphQL(
        string $nomeQueryGraphQL,
        array $dadosEntrada,
        array $dadosSaida,
        $input,
        $type,
        bool $parametrosEntrada
    ): string {
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
                if (empty($dadosEntrada)) {
                    $inputOpen = '';
                    $inputClose = '{';
                } else {
                    $inputOpen = '(';
                    $inputClose = ') {';
                }
            }
        }

        $query = "$nomeQueryGraphQL $inputOpen";

        foreach ($dadosEntrada as $key => $value) {
            if (is_array($value) && isset($value['type']) && $value['type'] == 'ENUM') {
                $query .= $this->converteDadosString($query, $key, $value, $input, $value['type'], $parametrosEntrada);
            } elseif (is_array($value)) {
                $query .= $this->converteDadosArrayEntrada($key, $value);
            } elseif ($value) {
                $query .= $this->converteDadosString($query, $key, $value, $input, $type, $parametrosEntrada);
            }
        }

        $closeOpen = $input ? '{' : '';
        $closeExit = '}';

        $query .= "{$inputClose}{$closeOpen}";

        foreach ($dadosSaida as $key => $value) {
            $query .= $this->converteDadosSaidaGraphQL($key, $value);
        }

        $query .= "{$closeExit}";

        // NOTE - Para Debug das queries
        //dump($query);

        return $query;
    }

    private function converteDadosSaidaGraphQL($key, $value): string
    {
        if (is_array($value)) {
            $queryPart = " $key {";
            foreach ($value as $subKey => $subValue) {
                $queryPart .= $this->converteDadosSaidaGraphQL($subKey, $subValue);
            }
            $queryPart .= ' }';

            return $queryPart;
        }

        return " $value ";
    }

    private function converteDadosArrayEntrada(string $key, array $value): string
    {
        $stringValue = '';

        $stringValue .= " {$key}: [";
        $count = 0;
        $total = count($value);

        foreach ($value as $value2) {
            $count++;
            $virgula = $count < $total ? ', ' : '';

            // Checa se o valor é uma string e adiciona aspas duplas
            if (is_string($value2)) {
                $stringValue .= '"' . $value2 . '"' . $virgula;
            } else {
                $stringValue .= $value2 . $virgula;
            }
        }

        $stringValue .= '] ';

        return $stringValue;
    }

    private function converteDadosString(
        string $query,
        $key,
        $value,
        bool $input,
        string $type,
        bool $receberComoParametro
    ): string {
        if ($type == 'ENUM') {
            return $key . ': ' . $value['value'] . ' ';
        } elseif ($input || $type == 'query') {
            if (is_int($value) || is_bool($value)) {
                if ($value === true) {
                    $value = 'true';
                } elseif ($value === false) {
                    $value = 'false';
                }

                return $key . ': ' . $value . ' ';
            }

            return $key . ': ' . '"' . $value . '" ';
        } elseif ($receberComoParametro) {
            if (is_int($value) || is_bool($value)) {
                if ($value === true) {
                    $value = 'true';
                } elseif ($value === false) {
                    $value = 'false';
                }

                return $key . ': ' . $value . ' ';
            }

            return $key . ': ' . '"' . $value . '" ';
        }

        return $value . ' ';
    }

    private function addPermissionToUser(string $permission, string $role): void
    {
        $role = Role::where('name', $role)->first();
        $role->givePermissionTo($permission);
    }

    private function removePermissionToUser(string $permission, string $role): void
    {
        $role = Role::where('name', $role)->first();
        $role->revokePermissionTo($permission);
    }

    /**
     * @param  bool  $permission - true para adicionar, false para remover
     * @param  string  $role - nome do role
     * @param  string  $namePermission - nome do permission
     */
    public function checkPermission(bool $permission, string $role, string $namePermission): void
    {
        if ($permission) {
            $this->addPermissionToUser($namePermission, $role);
        } else {
            $this->removePermissionToUser($namePermission, $role);
        }
    }

    public function assertMessageError($type_message_error, $response, bool $permission, $expected_message)
    {
        if ($type_message_error) {
            if (!$permission) {
                $this->assertSame($response->json()['errors'][0][$type_message_error], $expected_message);
            } else {
                if (isset($response->json()['errors'][0]['extensions'])) {
                    $response = $response->json()['errors'][0]['extensions'];
                }
                if (isset($response['validation'])) {
                    $this->assertSame($response['validation'][$type_message_error][0], trans($expected_message));
                } else {
                    if (isset($response['category'])) {
                        $this->assertSame($response['category'], trans($expected_message));
                    }
                }
            }
        }
    }

    public static function permissionProvider(): array
    {
        return [
            'when permission allows' => [
                true,
            ],
            'when permission does not allow' => [
                false,
            ],
        ];
    }
}
