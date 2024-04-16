<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TenantRequest;
use App\Jobs\RunTenantMigrations;

class TenantController extends Controller
{
    public function create(TenantRequest $request)
    {
        $this->runTenantMigrations($request->tenantId);

        return response()->json(['message' => trans('TenantCreate.messageSuccess')], 200);
    }

    protected function runTenantMigrations(String $tenantId)
    {
        try {
            RunTenantMigrations::dispatch($tenantId);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
