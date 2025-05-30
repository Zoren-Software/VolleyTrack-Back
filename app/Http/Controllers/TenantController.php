<?php

namespace App\Http\Controllers;

use App\Http\Requests\TenantRequest;
use App\Jobs\RunTenantMigrations;

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
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(TenantRequest $request)
    {
        $this->runTenantMigrations($request->tenantId, $request->email, $request->name);

        return response()->json(['message' => trans('TenantCreate.messageSuccess')], 200);
    }

    /**
     * @param string $tenantId
     * @param string $email
     * @param string $name
     * 
     * @return void
     */
    protected function runTenantMigrations(string $tenantId, string $email, string $name): void
    {
        try {
            RunTenantMigrations::dispatch($tenantId, $email, $name);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
