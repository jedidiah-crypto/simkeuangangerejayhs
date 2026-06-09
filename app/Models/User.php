<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = [];

    protected $hidden = ['password', 'remember_token'];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Cek apakah user memiliki role tertentu.
     * Dipanggil oleh RoleMiddleware.
     */
    public function hasRole(string $role): bool
    {
        return $this->roles()->where('name', $role)->exists();
    }

    /**
     * Cek apakah user adalah pendeta (admin).
     */
    public function isPendeta(): bool
    {
        return $this->hasRole('pendeta');
    }

    /**
     * Cek apakah user adalah bendahara.
     */
    public function isBendahara(): bool
    {
        return $this->hasRole('bendahara');
    }
}
