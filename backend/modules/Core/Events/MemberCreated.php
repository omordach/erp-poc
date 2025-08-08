<?php

namespace Modules\Core\Events;

use Illuminate\Foundation\Events\Dispatchable;

class MemberCreated
{
    use Dispatchable;

    public function __construct(
        public int $memberId,
        public array $payload = []
    ) {}
}
