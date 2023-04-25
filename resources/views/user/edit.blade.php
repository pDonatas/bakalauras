@extends('layouts.main')

@section('title') {{ $user->name }} @endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-default">
                <div class="card-body">
                    <form enctype="multipart/form-data" method="post" action="{{ route('profile.update') }}">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label for="name">{{ __('Name') }}</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}">
                        </div>
                        <div class="form-group">
                            <label for="email">{{ __('Email') }}</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}">
                        </div>
                        <div class="form-group">
                            <label for="avatar">{{ __('Avatar') }}</label>
                            <input type="file" class="form-control" id="avatar" name="avatar">
                        </div>
                        <div class="form-group">
                            <label for="number">{{ __('Phone number') }}</label>
                            <input type="text" class="form-control" id="number" name="phone_number" value="{{ $user->phone_number }}">
                        </div>
                        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
