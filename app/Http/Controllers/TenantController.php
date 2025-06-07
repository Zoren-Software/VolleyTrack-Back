<?php

namespace App\Http\Controllers;

use App\Http\Requests\TenantRequest;
use App\Jobs\RunTenantMigrations;
use Illuminate\Http\JsonResponse;

class TenantController extends Controller
{
    /**
     * Create Tenant
     *
     * @group Tenant
     *
     * @responseFile 200 scenario="sucesso" scribe/success/tenant/create.json
     * @responseFile 200 scenario="response" scribe/responses/tenant/create.json
     * @responseFile 422 scenario="erro" scribe/errors/tenant/create.json
     */
    public function create(TenantRequest $request): JsonResponse
    {
        /** @var string $tenantId */
        $tenantId = $request->input('tenantId');
        /** @var string $email */
        $email = $request->input('email');
        /** @var string $name */
        $name = $request->input('name');

        $this->runTenantMigrations($tenantId, $email, $name);

        return response()->json(['message' => trans('TenantCreate.messageSuccess')], 200);
    }

    protected function runTenantMigrations(string $tenantId, string $email, string $name): void
    {
        try {
            RunTenantMigrations::dispatch($tenantId, $email, $name);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
