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
