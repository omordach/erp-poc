<?php

namespace Modules\Membership\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Member extends Model
{
    protected $guarded = [];

    public function local(): BelongsTo
    {
        return $this->belongsTo(Local::class);
    }
}
