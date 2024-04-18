<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TenantRequest;
use App\Jobs\RunTenantMigrations;

class TenantController extends Controller
{
    /**
     * 
     * Create Tenant
     * 
     * @group Tenant
     * 
     * @responseFile 200 scenario="sucesso" scribe/success/tenant/create.json
     * @responseFile 200 scenario="response" scribe/responses/tenant/create.json
     * @responseFile 422 scenario="erro" scribe/errors/tenant/create.json
     * 
     * @param TenantRequest $request
     * 
     * @return [type]
     */
    public function create(TenantRequest $request)
    {
        $this->runTenantMigrations($request->tenantId, $request->email, $request->name);

        return response()->json(['message' => trans('TenantCreate.messageSuccess')], 200);
    }

    protected function runTenantMigrations(String $tenantId, String $email, String $name)
    {
        try {
            RunTenantMigrations::dispatch($tenantId, $email, $name);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
