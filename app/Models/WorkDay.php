<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkDay extends Model
{
    use HasFactory;

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
