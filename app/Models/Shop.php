<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shop extends Model
{
    protected $guarded = [];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function clients(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'shop_clients', 'shop_id', 'client_id');
    }

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class);
    }

    public function marks(): HasMany
    {
        return $this->hasMany(Mark::class);
    }
}
