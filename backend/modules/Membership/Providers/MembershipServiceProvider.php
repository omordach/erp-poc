<?php

namespace Modules\Membership\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class MembershipServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
    }
}
