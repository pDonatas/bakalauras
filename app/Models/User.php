<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    public const ROLE_USER = 0;
    public const ROLE_BARBER = 1;
    public const ROLE_ADMIN = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isGranted(int $role): bool
    {
        return $this->role >= $role;
    }

    public function getRole(): string
    {
        return match ($this->role) {
            self::ROLE_USER => __('User'),
            self::ROLE_BARBER => __('Barber'),
            self::ROLE_ADMIN => __('Admin'),
            default => __('Unknown'),
        };
    }

    public function marks(): HasMany
    {
        return $this->hasMany(Mark::class);
    }

    public function ownedShops(): HasMany
    {
        return $this->hasMany(Shop::class, 'owner_id');
    }

    public function shops(): BelongsToMany
    {
        return $this->belongsToMany(Shop::class, 'shop_workers', 'worker_id', 'shop_id');
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function isAdmin(): bool
    {
        return $this->isGranted(self::ROLE_ADMIN);
    }
}
