@extends('layouts.admin')
@section('title') {{ __('Categories') }} @endsection
@section('content')
    <div class="row g-4">
        <!-- Start column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <div class="card-title">
                        {{ __('Categories') }}
                    </div>
                </div>
                <div class="card-body">
                    <div class="card card-secondary card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                {{ __('Edit') }}
                            </div>
                        </div>
                        <form method="post" action="{{ route('admin.categories.update', $category->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <x-auth-validation-errors class="tw-mb-4" :errors="$errors" />
                                <div class="mb-3">
                                    <label for="name" class="form-label">{{ __('Name') }}</label>
                                    <input type="text" name="name" class="form-control" id="name" value="{{ $category->name }}">
                                </div>
                                <div class="mb-3">
                                    <label for="slug" class="form-label">{{ __('Slug') }}</label>
                                    <input type="text" name="slug" class="form-control" id="slug" value="{{ $category->slug }}">
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">{{ __('Description') }}</label>
                                    <textarea name="description" class="form-control" id="description">{{ $category->description }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="parent_id" class="form-label">{{ __('Parent') }}</label>
                                    <select name="parent_id" class="form-control" id="parent_id">
                                        <option value="0">{{ __('None') }}</option>
                                        @foreach($categories as $cat)
                                            <option @if($category->parent_id == $cat->id) selected @endif value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
