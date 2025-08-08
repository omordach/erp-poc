<?php

namespace Modules\Payments\Providers;

use Illuminate\Support\ServiceProvider;

class PaymentsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
    }
}
