<?php

namespace Modules\Membership\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Union extends Model
{
    protected $guarded = [];

    public function locals(): HasMany
    {
        return $this->hasMany(Local::class);
    }
}
