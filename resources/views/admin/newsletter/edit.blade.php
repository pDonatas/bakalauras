@extends('layouts.admin')
@section('title')
    {{ __('Categories') }}
@endsection
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
                        <form method="post" action="{{ route('admin.newsletters.update', $newsletter->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <x-auth-validation-errors class="tw-mb-4" :errors="$errors"/>
                                <div class="mb-3">
                                    <label for="title" class="form-label">{{ __('Title') }}</label>
                                    <input name="title" class="form-control" id="title" type="text"
                                           value="{{ $newsletter->title }}"/>
                                </div>
                                <div class="mb-3">
                                    <label for="text" class="form-label">{{ __('Text') }}</label>
                                    <textarea name="text" class="form-control"
                                              id="text">{{ $newsletter->text }}</textarea>
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
