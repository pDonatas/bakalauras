<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PhotoCreateRequest;
use App\Models\Photo;
use App\Models\Service;
use App\Models\Shop;
use Illuminate\Http\JsonResponse;
use Storage;

class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Shop $shop, Service $service)
    {
        $photos = $service->photos()->paginate();

        return view('admin.photos.index', compact('photos', 'shop', 'service'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Shop $shop, Service $service)
    {
        return view('admin.photos.create', compact('shop', 'service'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Shop $shop, Service $service, PhotoCreateRequest $request)
    {
        $file = $request->file('file');

        $name = $file->getClientOriginalName();

        $newName = time() . '_' . $name;

        $newPath = 'images/services/' . $service->id;

        \Storage::disk('public')->putFileAs($newPath, $file, $newName);

        $service->photos()->create([
            'path' => '/storage/images/services/' . $service->id . '/' . $newName,
        ]);

        return new JsonResponse([
            'success' => true,
            'message' => 'Photo uploaded successfully',
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Photo $photo)
    {
        return view('admin.photos.show', compact('photo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Photo $photo)
    {
        return view('admin.photos.edit', compact('photo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Shop $shop, Service $service, UpdatePhotoRequest $request, Photo $photo)
    {
        $photo->update($request->validated());

        return redirect()->route('admin.shops.services.photos.index', [$shop, $service]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shop $shop, Service $service, Photo $photo)
    {
        $photo->delete();

        return redirect()->route('admin.photos.index', [$shop, $service]);
    }
}
