@extends('layouts.admin')

@section('title', 'Edit Photo')

@section('content')
    <div class="card">
        <div class="card-body">
            {{ __('Current photo') }} <img src="{{ $photo->path }}" />
        </div>
    </div>
@endsection
