@extends('layouts.admin')

@section('title', 'Create Photo')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="post" action="{{ route('admin.photos.store', [$shop->id, $service->id]) }}" enctype="multipart/form-data">
                <input type="file" name="path" class="form-control" accept="image/*"/>
                <input type="hidden" name="_method" value="PUT">
                @csrf
                <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
            </form>
        </div>
    </div>
@endsection
