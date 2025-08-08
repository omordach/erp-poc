<?php

namespace Modules\Membership\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Local extends Model
{
    protected $guarded = [];

    public function union(): BelongsTo
    {
        return $this->belongsTo(Union::class);
    }

    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }
}
