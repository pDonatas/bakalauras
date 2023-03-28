<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Photo extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * @return BelongsTo<Service, Photo>
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
