<?php

use App\Builder\PageBuilder;
use Illuminate\Support\Facades\Route;

Route::any(config('builder.general.assets_url') . '{any}', function () {
    $builder = new PageBuilder(config('builder'));
    $builder->handlePageBuilderAssetRequest();
})->where('any', '.*');

// handle requests to retrieve uploaded file
Route::any(config('builder.general.uploads_url') . '{any}', function () {
    $builder = new PageBuilder(config('builder'));
    $builder->handleUploadedFileRequest();
})->where('any', '.*');

if (config('builder.website_manager.use_website_manager')) {
    // handle all website manager requests
    Route::any(config('builder.website_manager.url') . '{any}', function () {
        $builder = new PageBuilder(config('builder'));
        $builder->handleRequest();
    })->where('any', '.*');
}

if (config('builder.router.use_router')) {
    // pass all remaining requests to the LaravelPageBuilder router
    Route::any('/{any}', function () {
        $builder = new PageBuilder(config('builder'));
        $hasPageReturned = $builder->handlePublicRequest();

        if ('/' === request()->path() && ! $hasPageReturned) {
            $builder->getWebsiteManager()->renderWelcomePage();
        }
    })->where('any', '.*');
}
