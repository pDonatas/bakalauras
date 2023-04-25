<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
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
        'phone_number',
        'avatar'
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

    /**
     * @return HasMany<Mark>
     */
    public function marks(): HasMany
    {
        return $this->hasMany(Mark::class);
    }

    /**
     * @return HasMany<Shop>
     */
    public function ownedShops(): HasMany
    {
        return $this->hasMany(Shop::class, 'owner_id');
    }

    /**
     * @return BelongsToMany<Shop>
     */
    public function shops(): BelongsToMany
    {
        return $this->belongsToMany(Shop::class, 'shop_workers', 'worker_id', 'shop_id');
    }

    /**
     * @return HasMany<Service>
     */
    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function isAdmin(): bool
    {
        return $this->isGranted(self::ROLE_ADMIN);
    }

    /**
     * @return HasMany<Order>
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * @return BelongsToMany<Shop>
     */
    public function relatedShops(): BelongsToMany
    {
        return $this->belongsToMany(Shop::class, 'shop_workers', 'worker_id', 'shop_id')
            ->orWhere('owner_id', $this->id)->groupBy('shop_id');
    }

    /**
     * @return HasManyThrough<Order>
     */
    public function providedOrders(): HasManyThrough
    {
        return $this->hasManyThrough(Order::class, Service::class, 'user_id', 'service_id', 'id', 'id');
    }

    /**
     * @return HasMany<Service>
     */
    public function providedServicesByShop(Shop $shop): HasMany
    {
        return $this->services()->where('shop_id', $shop->id);
    }

    /**
     * @return HasMany<Bookmark>
     */
    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    /**
     * @return HasOne<WorkDay>
     */
    public function workDay(): HasOne
    {
        return $this->hasOne(WorkDay::class);
    }
}
