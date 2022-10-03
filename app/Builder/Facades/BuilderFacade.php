<?php

declare(strict_types=1);

namespace App\Builder\Facades;

use App\Builder\PageBuilder;
use Illuminate\Support\Facades\Facade;

/**
 * @see PageBuilder
 */
class BuilderFacade extends Facade
{
    /**
     * @inheritDoc
     */
    protected static function getFacadeAccessor(): string
    {
        return PageBuilder::class;
    }
}
