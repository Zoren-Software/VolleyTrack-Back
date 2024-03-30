<?php

namespace Tests\Feature\Tenant;

use Tests\TestCase;

class HorizonRoutesTest extends TestCase
{
    protected $tenancy = true;

    protected $tenant = 'horizon';

    /**
     * A basic test route horizon for login.
     *
     * @test
     *
     * @dataProvider routesProvider
     *
     * @return void
     */
    public function routeLoginHorizon(string $route)
    {
        $response = $this->get($this->tenantUrl . $route);

        $response->assertStatus(200);
    }

    /**
     * @return [type]
     */
    public static function routesProvider()
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
