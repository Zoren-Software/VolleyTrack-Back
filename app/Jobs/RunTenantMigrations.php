<?php

namespace App\Jobs;

use App\Mail\User\ConfirmEmailAndCreatePasswordMail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Tenant;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class RunTenantMigrations implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $tenantId;

    protected $email;

    protected $name;

    public function __construct(String $tenantId, String $email, String $name)
    {
        $this->tenantId = $tenantId;
        $this->email = $email;
        $this->name = $name;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $tenant = Tenant::create([
                'id' => $this->tenantId,
            ]);

            $tenant->domains()->create(['domain' => $this->tenantId . '.' . env('APP_HOST')]);

            tenancy()->initialize($this->tenantId);

            Artisan::call('tenants:seed', ['--tenants' => $this->tenantId]);

            $user = new User();
            $user->name = $this->name;
            $user->email = $this->email;
            $user->password = Hash::make(Str::random(8) . '@volleyball');
            $user->save();

            $user->assignRole('admin');

            $user->sendConfirmEmailAndCreatePasswordNotification($this->tenantId, true);

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
