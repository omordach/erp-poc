<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * Use the tenant database connection.
     */
    protected $connection = 'tenant';

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function roles(): HasMany
    {
        return $this->hasMany(UserRole::class);
    }

    // Minimal PoC helper: checks if user has role for module (any scope)
    public function hasRoleForModule(string $moduleKey, string $required = 'viewer'): bool
    {
        $priority = ['viewer' => 1, 'editor' => 2, 'admin' => 3];
        $needed = $priority[$required] ?? 1;

        foreach ($this->roles as $role) {
            if ($role->module_key === $moduleKey) {
                $have = $priority[$role->role] ?? 0;
                if ($have >= $needed) {
                    return true;
                }
            }
        }

        return false;
    }
}
