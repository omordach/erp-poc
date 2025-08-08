<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Core\Providers\CoreServiceProvider;
use Modules\Membership\Providers\MembershipServiceProvider;
use Modules\Grievances\Providers\GrievancesServiceProvider;
use Modules\Payments\Providers\PaymentsServiceProvider;

class ModulesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(CoreServiceProvider::class);
        $this->app->register(MembershipServiceProvider::class);
        $this->app->register(GrievancesServiceProvider::class);
        $this->app->register(PaymentsServiceProvider::class);
    }

    public function boot(): void
    {
        //
    }
}
