@extends('layouts.admin')
@section('title') {{ __('Shops') }} @endsection
@section('content')
    <div class="row g-4">
        <!-- Start column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <div class="card-title">
                        {{ __('Shops') }}
                    </div>
                </div>
                <div class="card-body">
                    <div class="card card-secondary card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                {{ __('Edit') }}
                            </div>
                        </div>
                        <form method="post" enctype="multipart/form-data" action="{{ route('admin.shops.update', $shop->id) }}">
                            @method('PUT')
                            @csrf
                            <div class="card-body">
                                <x-auth-validation-errors class="tw-mb-4" :errors="$errors" />
                                <div class="mb-3">
                                    <label for="name" class="form-label">{{ __('Company Name') }}</label>
                                    <input type="text" name="company_name" class="form-control" id="name" value="{{ $shop->company_name }}">
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">{{ __('Description') }}</label>
                                    <textarea name="description" class="form-control" id="description">{{ $shop->description }}</textarea>
                                </div>
                                @if (auth()->user()->isAdmin())
                                <div class="mb-3">
                                    <label for="owner_id" class="form-label">{{ __('Owner') }}</label>
                                    <select name="owner_id" class="form-control" id="owner_id">
                                        @foreach ($users as $user)
                                            <option @if($user->id == $shop->owner_id) selected @endif value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @else
                                    <input type="hidden" name="owner_id" value="{{ auth()->user()->id }}">
                                @endif
                                <div class="mb-3">
                                    <label for="workers" class="form-label">{{ __('Workers') }}</label>
                                    <select multiple name="workers[]" class="form-control" id="workers">
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="photo" class="form-label">{{ __('Main photo') }}</label>
                                    <input type="file" name="photo" class="form-control" id="photo" accept="image/*" />
                                </div>
                                <div class="mb-3">
                                    <label for="company_address" class="form-label">{{ __('Company address') }}</label>
                                    <input type="text" name="company_address" class="form-control" id="company_address" value="{{ $shop->company_address }}">
                                </div>
                                <div class="mb-3">
                                    <label for="company_code" class="form-label">{{ __('Company code') }}</label>
                                    <input type="text" name="company_code" class="form-control" id="company_code" value="{{ $shop->company_code }}">
                                </div>
                                <div class="mb-3">
                                    <label for="company_phone" class="form-label">{{ __('Company phone') }}</label>
                                    <input type="text" name="company_phone" class="form-control" id="company_phone" value="{{ $shop->company_phone }}">
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
@section('scripts')
    <script type="module">
        $(document).ready(function() {
            const workers = $('#workers');

            workers.select2({
                placeholder: "{{ __('Select workers') }}",
                allowClear: true,
            });

            workers.val(@json($shop->workers->pluck('id')->toArray()));
            workers.trigger('change');
        });
    </script>
@endsection
