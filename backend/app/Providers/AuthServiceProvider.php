<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // Add model => policy if needed; PoC uses middleware checks.
    ];

    public function boot(): void
    {
        //
    }
}
