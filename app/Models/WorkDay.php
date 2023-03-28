<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkDay extends Model
{
    protected $guarded = [];

    protected $casts = [
        'days' => 'array',
    ];

    /**
     * @return BelongsTo<User, WorkDay>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
