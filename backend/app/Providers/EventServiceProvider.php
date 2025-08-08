<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Core\Events\MemberCreated;
use Modules\Payments\Listeners\OnMemberCreated;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        MemberCreated::class => [
            OnMemberCreated::class,
        ],
    ];
}
