<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * @property int $id
 * @property int $shopAverageMark
 * @property int $services_count
 * @property int $workers_count
 * @property int $orders_count
 * @property int $owner_id
 */
class Shop extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * @return BelongsTo<User, Shop>
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * @return BelongsToMany<User>
     */
    public function clients(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'shop_clients', 'shop_id', 'client_id');
    }

    /**
     * @return HasMany<Page>
     */
    public function pages(): HasMany
    {
        return $this->hasMany(Page::class);
    }

    /**
     * @return HasMany<Mark>
     */
    public function marks(): HasMany
    {
        return $this->hasMany(Mark::class);
    }

    /**
     * @return BelongsToMany<User>
     */
    public function workers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'shop_workers', 'shop_id', 'worker_id');
    }

    /**
     * @return HasMany<Service>
     */
    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    /**
     * @return Attribute<int, int>
     */
    public function shopAverageMark(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->marks()->avg('mark') ?? 0,
        );
    }

    /**
     * @return HasMany<Bookmark>
     */
    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    /**
     * @return HasManyThrough<Order>
     */
    public function orders(): HasManyThrough
    {
        return $this->hasManyThrough(Order::class, Service::class);
    }
}
