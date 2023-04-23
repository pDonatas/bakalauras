@extends('layouts.admin')

@section('title', 'Edit Photo')

@section('content')
    <div class="card">
        <div class="card-body">
            {{ __('Current photo') }} <img src="{{ $photo->path }}" />
            <form method="post" action="{{ route('admin.photos.update', [$shop->id, $service->id, $photo->id]) }}" enctype="multipart/form-data">
                <input type="file" name="path" class="form-control" accept="image/*"/>
                <input type="hidden" name="_method" value="PUT">
                @csrf
                <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
            </form>
        </div>
    </div>
@endsection
