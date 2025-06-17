<?php

namespace Tests\Feature\Horizon;

use Tests\TestCase;

class HorizonRoutesTest extends TestCase
{
    protected bool $tenancy = true;

    protected string $tenant = 'horizon';

    /**
     * A basic test route horizon for login.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('routesProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function route_login_horizon(string $route)
    {
        $response = $this->get($this->tenantUrl . $route);

        $response->assertStatus(200);
    }

    /**
     * @return array<string, array<int, string>>
     */
    public static function routesProvider(): array
    {
        return [
            'home dashboard horizon redirect' => [
                '/horizon',
            ],
            'home dashboard horizon' => [
                '/horizon/dashboard',
            ],
            'monitoring' => [
                '/horizon/monitoring',
            ],
            'metrics jobs' => [
                '/horizon/metrics/jobs',
            ],
            'batches' => [
                '/horizon/batches',
            ],
            'jobs pending' => [
                '/horizon/jobs/pending',
            ],
            'jobs completed' => [
                '/horizon/jobs/completed',
            ],
            'jobs silenced' => [
                '/horizon/jobs/silenced',
            ],
            'failed' => [
                '/horizon/failed',
            ],
        ];
    }
}
