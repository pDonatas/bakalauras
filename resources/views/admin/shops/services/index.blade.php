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
                        {{ __('Services') }}
                    </div>
                </div>
                <div class="card-body">
                    <div class="card card-secondary card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                {{ __('Create') }}
                            </div>
                        </div>
                        <form method="post" action="{{ route('admin.services.store', $shop->id) }}">
                            @csrf
                            <div class="card-body">
                                <x-auth-validation-errors class="tw-mb-4" :errors="$errors" />
                                <div class="mb-3">
                                    <label for="name" class="form-label">{{ __('Name') }}</label>
                                    <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}">
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">{{ __('Description') }}</label>
                                    <textarea name="description" class="form-control" id="description">{{ old('description') }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="price" class="form-label">{{ __('Price') }}</label>
                                    <input type="number" step="0.1" name="price" class="form-control" value="{{ old('price') }}" id="price">
                                </div>
                                @if (auth()->user()->isAdmin() || $shop->owner_id == auth()->user()->id)
                                <div class="mb-3">
                                    <label for="user_id" class="form-label">{{ __('Worker') }}</label>
                                    <select name="user_id" class="form-control" id="user_id">
                                        @foreach ($shop->workers as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @else
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                @endif
                            <div class="mb-3">
                                <label for="duration" class="form-label">{{ __('Duration') }} ({{ __('in minutes') }})</label>
                                <input type="number" value="{{ old('duration') }}" name="length" class="form-control" id="duration">
                            </div>
                            <div class="mb-3">
                                <label for="category" class="form-label">{{ __('Category') }}</label>
                                <select name="category_id" class="form-control" id="category">
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
                            </div>
                        </form>
                    </div>
                    <div class="card card-secondary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('List of Services') }}</h3>

                            <div class="card-tools">
                                <ul class="pagination pagination-sm float-end">
                                    {{ $services->links() }}
                                </ul>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-0">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th style="width: 10px">{{ __('ID') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Price') }}</th>
                                    <th>{{ __('Worker') }}</th>
                                    <th style="width: 130px">{{ __('Action') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($services as $service)
                                    <tr>
                                        <td>{{ $service->id }}</td>
                                        <td>{{ $service->name }}</td>
                                        <td>{{ $service->price }}</td>
                                        <td>{{ $service->worker->name }}</td>
                                        <td>
                                            <a href="{{ route('admin.photos.index', [$shop->id, $service->id]) }}"><button class="btn btn-default"><i class="fa-solid fa-image"></i></button></a>
                                            <a href="{{ route('admin.services.edit', [$shop->id, $service->id]) }}"><button class="btn btn-default"><i class="fa-solid fa-pen-to-square"></i></button></a>
                                            <a href="#" onclick="deleteItem('{{ route('admin.services.destroy', [$shop->id, $service->id]) }}')"><button class="btn btn-default"><i class="fa-sharp fa-solid fa-trash"></i></button></a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
