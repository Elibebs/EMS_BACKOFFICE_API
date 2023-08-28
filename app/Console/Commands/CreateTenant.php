<?php
namespace App\Console\Commands;
use App\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
class CreateTenant extends Command
{
    protected $signature = 'tenant:create {name} {email}';
    protected $description = 'Creates a tenant with the provided name and email of super admin e.g. php artisan tenant:create ghana xyz@ems.com';
    public function handle()
    {
        $name = $this->argument('name');
        $email = $this->argument('email');
        if (Tenant::tenantExists($name)) {
            $this->error("A tenant with name '{$name}' already exists.");
            return;
        }
        $tenant = Tenant::registerTenant($name);
        $this->info("Tenant '{$name}' is created and is now accessible at {$tenant->hostname->fqdn}");
    }

}
