<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Stancl\JobPipeline\JobPipeline;
use Stancl\Tenancy\Events;
use Stancl\Tenancy\Jobs;
use Stancl\Tenancy\Listeners;
use Stancl\Tenancy\Middleware;

class TenancyServiceProvider extends ServiceProvider
{
    /**
     * By default, no namespace is used to support the callable array syntax.
     */
    public static string $controllerNamespace = '';

    /**
     * @return array<string, list<callable|class-string>>
     */
    public function events(): array
    {
        // TenantCreated pipeline
        /** @var JobPipeline $pipelineCreated */
        $pipelineCreated = JobPipeline::make([
            Jobs\CreateDatabase::class,
            Jobs\MigrateDatabase::class,
        ]);

        /** @var JobPipeline $pipelineCreatedSent */
        $pipelineCreatedSent = $pipelineCreated->send(
            fn (Events\TenantCreated $event) => $event->tenant
        );

        /** @var JobPipeline $pipelineCreatedQueued */
        $pipelineCreatedQueued = $pipelineCreatedSent->shouldBeQueued(false);

        /** @var callable $tenantCreatedListener */
        $tenantCreatedListener = $pipelineCreatedQueued->toListener();

        // TenantDeleted pipeline
        /** @var JobPipeline $pipelineDeleted */
        $pipelineDeleted = JobPipeline::make([
            Jobs\DeleteDatabase::class,
        ]);

        /** @var JobPipeline $pipelineDeletedSent */
        $pipelineDeletedSent = $pipelineDeleted->send(
            fn (Events\TenantDeleted $event) => $event->tenant
        );

        /** @var JobPipeline $pipelineDeletedQueued */
        $pipelineDeletedQueued = $pipelineDeletedSent->shouldBeQueued(false);

        /** @var callable $tenantDeletedListener */
        $tenantDeletedListener = $pipelineDeletedQueued->toListener();

        return [
            Events\CreatingTenant::class => [],
            Events\TenantCreated::class => [$tenantCreatedListener],
            Events\TenantDeleted::class => [$tenantDeletedListener],

            Events\SavingTenant::class => [],
            Events\TenantSaved::class => [],
            Events\UpdatingTenant::class => [],
            Events\TenantUpdated::class => [],
            Events\DeletingTenant::class => [],
            Events\CreatingDomain::class => [],
            Events\DomainCreated::class => [],
            Events\SavingDomain::class => [],
            Events\DomainSaved::class => [],
            Events\UpdatingDomain::class => [],
            Events\DomainUpdated::class => [],
            Events\DeletingDomain::class => [],
            Events\DomainDeleted::class => [],
            Events\DatabaseCreated::class => [],
            Events\DatabaseMigrated::class => [],
            Events\DatabaseSeeded::class => [],
            Events\DatabaseRolledBack::class => [],
            Events\DatabaseDeleted::class => [],
            Events\InitializingTenancy::class => [],
            Events\TenancyInitialized::class => [
                Listeners\BootstrapTenancy::class,
            ],
            Events\EndingTenancy::class => [],
            Events\TenancyEnded::class => [
                Listeners\RevertToCentralContext::class,
            ],
            Events\BootstrappingTenancy::class => [],
            Events\TenancyBootstrapped::class => [],
            Events\RevertingToCentralContext::class => [],
            Events\RevertedToCentralContext::class => [],
            Events\SyncedResourceSaved::class => [
                Listeners\UpdateSyncedResource::class,
            ],
            Events\SyncedResourceChangedInForeignDatabase::class => [],
        ];
    }

    public function register()
    {
        //
    }

    /**
     * @return void
     */
    public function boot()
    {
        $this->bootEvents();
        // $this->mapRoutes();

        $this->makeTenancyMiddlewareHighestPriority();
    }

    protected function bootEvents(): void
    {
        foreach ($this->events() as $event => $listeners) {
            foreach ($listeners as $listener) {
                // if ($listener instanceof JobPipeline) {
                //     $listener = $listener->toListener();
                // }

                Event::listen($event, $listener);
            }
        }
    }

    /**
     * @codeCoverageIgnore
     */
    protected function mapRoutes(): void
    {
        if (file_exists(base_path('routes/tenant.php'))) {
            Route::namespace(static::$controllerNamespace)
                ->group(base_path('routes/tenant.php'));
        }
    }

    protected function makeTenancyMiddlewareHighestPriority(): void
    {
        $tenancyMiddleware = [
            // Even higher priority than the initialization middleware
            Middleware\PreventAccessFromCentralDomains::class,

            Middleware\InitializeTenancyByDomain::class,
            Middleware\InitializeTenancyBySubdomain::class,
            Middleware\InitializeTenancyByDomainOrSubdomain::class,
            Middleware\InitializeTenancyByPath::class,
            Middleware\InitializeTenancyByRequestData::class,
        ];

        foreach (array_reverse($tenancyMiddleware) as $middleware) {
            app(\Illuminate\Contracts\Http\Kernel::class)->prependToMiddlewarePriority($middleware);
        }
    }
}
