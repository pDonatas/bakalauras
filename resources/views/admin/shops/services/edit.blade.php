@extends('layouts.admin')
@section('title') {{ __('Shops') }} {{ __('Services') }} @endsection
@section('content')
    <div class="row g-4">
        <!-- Start column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <div class="card-title">
                        {{ __('Services') }}
                    </div>
                </div>
                <div class="card-body">
                    <div class="card card-secondary card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                {{ __('Edit') }}
                            </div>
                        </div>
                        <form method="post" action="{{ route('admin.services.update', [$shop->id, $service->id]) }}">
                            @method('PUT')
                            @csrf
                            <div class="card-body">
                                <x-auth-validation-errors class="tw-mb-4" :errors="$errors" />
                                <div class="mb-3">
                                    <label for="name" class="form-label">{{ __('Name') }}</label>
                                    <input type="text" name="name" class="form-control" id="name" value="{{ $service->name }}">
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">{{ __('Description') }}</label>
                                    <textarea name="description" class="form-control" id="description">{{ $service->description }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="price" class="form-label">{{ __('Price') }}</label>
                                    <input type="number" step="0.1" name="price" class="form-control" id="price" value="{{ $service->price }}">
                                </div>
                                @if (auth()->user()->isAdmin() || $shop->owner_id == auth()->user()->id)
                                    <div class="mb-3">
                                        <label for="user_id" class="form-label">{{ __('Worker') }}</label>
                                        <select name="user_id" class="form-control" id="user_id">
                                            @foreach ($shop->workers as $user)
                                                <option @if ($user->id === $service->user_id) selected @endif value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @else
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                @endif
                                <div class="mb-3">
                                    <label for="duration" class="form-label">{{ __('Duration') }} ({{ __('in minutes') }})</label>
                                    <input type="number" name="length" class="form-control" id="duration" value="{{ $service->length }}">
                                </div>
                                <div class="mb-3">
                                    <label for="category" class="form-label">{{ __('Category') }}</label>
                                    <select name="category_id" class="form-control" id="category">
                                        @foreach ($categories as $category)
                                            <option @if ($category->id === $service->category_id) selected @endif value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">{{ __('Edit') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
