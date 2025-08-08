<?php

namespace Modules\Grievances\Providers;

use Illuminate\Support\ServiceProvider;

class GrievancesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
    }
}
