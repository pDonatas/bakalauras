<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Order extends Model
{
    use HasFactory;

    const ORDER_TYPE_PAYMENT = 1;

    const ORDER_TYPE_CASH = 2;

    const STATUS_NOT_PAID = 0;

    const STATUS_PAID = 1;

    const STATUS_CANCELED = 2;

    const STATUS_FULFILLED = 3;

    protected $guarded = [];

    /**
     * @return BelongsTo<User, Order>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Service, Order>
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * @return HasOneThrough<User>
     */
    public function provider(): HasOneThrough
    {
        return $this->hasOneThrough(User::class, Service::class, 'id', 'id', 'service_id', 'user_id');
    }

    /**
     * @return Attribute<string, string>
     */
    protected function statusText(): Attribute
    {
        return Attribute::make(
            get: fn () => match ($this->status) {
                self::STATUS_NOT_PAID => __('Not Paid'),
                self::STATUS_PAID => __('Paid'),
                self::STATUS_CANCELED => __('Canceled'),
                self::STATUS_FULFILLED => __('Fulfilled'),
                default => __('Unknown'),
            });
    }

    /**
     * @return Attribute<string, string>
     */
    protected function orderTypeText(): Attribute
    {
        return Attribute::make(
            get: fn () => match ($this->order_type) {
                "1" => __('Paysera'),
                "0" => __('Cash'),
                default => __('Unknown'),
            },
        );
    }

    /**
     * @return array<string, string>
     */
    public function toCallendarArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->user->name . ' - ' . $this->service->name,
            'start' => Carbon::createFromFormat('Y-m-d H:i', $this->date . ' ' . $this->time)->toIso8601String(),
            'end'   => Carbon::createFromFormat('Y-m-d H:i', $this->date . ' ' . $this->time)->addMinutes($this->length)->toIso8601String(),
        ];
    }

    /**
     * @return HasOneThrough<Shop>
     */
    public function shop(): HasOneThrough
    {
        return $this->hasOneThrough(Shop::class, Service::class, 'id', 'id', 'service_id', 'shop_id');
    }
}
