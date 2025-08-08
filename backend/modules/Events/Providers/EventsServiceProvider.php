<?php

namespace Modules\Events\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Events\Providers\EventsServiceProvider;
// ...


class EventsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        $this->app->register(EventsServiceProvider::class);
    }
}
