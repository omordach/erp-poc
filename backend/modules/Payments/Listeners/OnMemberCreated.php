<?php

namespace Modules\Payments\Listeners;

use Modules\Core\Events\MemberCreated;
use Modules\Payments\Models\Account;

class OnMemberCreated
{
    public function handle(MemberCreated $event): void
    {
        // Create a simple account record for the member in Payments module
        Account::firstOrCreate(
            ['member_id' => $event->memberId],
            ['status' => 'active', 'opened_at' => now()]
        );
    }
}
