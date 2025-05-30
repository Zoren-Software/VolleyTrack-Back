<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\CheckTenantForMaintenanceMode;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->mapWebRoutes();
        $this->mapApiRoutes();

        parent::boot();
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @codeCoverageIgnore
     *
     * @return void
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }

    /**
     * @return void
     */
    protected function mapWebRoutes(): void
    {
        Route::middleware([
            'web',
        ])
            ->namespace($this->namespace)
            ->domain('horizon.' . appHost())
            ->group(base_path('routes/horizon.php'));

        Route::middleware([
            'web',
            // NOTE - Deixar sempre comentado, descomentar apenas para testar rotas de e-mail
            // InitializeTenancyByDomain::class,
            // PreventAccessFromCentralDomains::class,
            // CheckTenantForMaintenanceMode::class,
        ])
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));

        foreach ($this->rotasWeb() as $arquivoDeRota) {
            Route::middleware([
                'api',
                // InitializeTenancyByDomain::class,
                // PreventAccessFromCentralDomains::class,
                // CheckTenantForMaintenanceMode::class,
            ])
                ->prefix('/v1')->name('app.')
                ->namespace("{$this->namespace}\App")
                ->group(base_path("routes/v1/web/$arquivoDeRota"));
        }
    }

    /**
     * @return void
     */
    protected function mapApiRoutes(): void
    {
        foreach ($this->centralDomains() as $domain) {
            Route::prefix('api')
                ->domain($domain)
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));
        }
    }

    /**
     * @return array<string>
     */
    protected function centralDomains(): array
    {
        return config('tenancy.central_domains');
    }

    /**
     * @return array<string>
     */
    private function rotasWeb(): array
    {
        return array_diff(scandir(base_path('routes/v1/web')), ['.', '..']);
    }
}
