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
                                {{ __('Create') }}
                            </div>
                        </div>
                        <form method="post" enctype="multipart/form-data" action="{{ route('admin.shops.store') }}">
                            @csrf
                            <div class="card-body">
                                <x-auth-validation-errors class="tw-mb-4" :errors="$errors" />
                                <div class="mb-3">
                                    <label for="name" class="form-label">{{ __('Name') }}</label>
                                    <input type="text" name="company_name" class="form-control" id="name">
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">{{ __('Description') }}</label>
                                    <textarea name="description" class="form-control" id="description"></textarea>
                                </div>
                                @if (auth()->user()->isAdmin())
                                <div class="mb-3">
                                    <label for="owner_id" class="form-label">{{ __('Owner') }}</label>
                                    <select name="owner_id" class="form-control" id="owner_id">
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @else
                                    <input type="hidden" name="owner_id" value="{{ auth()->user()->id }}">
                                @endif
                                <div class="mb-3">
                                    <label for="photo" class="form-label">{{ __('Main photo') }}</label>
                                    <input type="file" name="photo" class="form-control" id="photo" accept="image/*" />
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
                            </div>
                        </form>
                    </div>
                    <div class="card card-secondary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('List of Shops') }}</h3>

                            <div class="card-tools">
                                <ul class="pagination pagination-sm float-end">
                                    {{ $shops->links() }}
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
                                    <th style="width: 130px">{{ __('Action') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($shops as $shop)
                                    <tr>
                                        <td>{{ $shop->id }}</td>
                                        <td>{{ $shop->company_name }}</td>
                                        <td>
                                            @if (auth()->user()->isAdmin() || auth()->user()->id == $shop->owner_id)
                                            <a href="{{ route('admin.shops.edit', $shop->id) }}"><button title="{{ __('Edit') }}" class="btn btn-default"><i class="fa-solid fa-pen-to-square"></i></button></a>
                                            <a href="{{ route('admin.pages.index', $shop->id) }}"><button title="{{ __('Pages') }}" class="btn btn-default"><i class="fa-solid fa-file"></i></button></a>
                                            <a href="#" onclick="deleteItem('{{ route('admin.shops.destroy', $shop->id) }}')"><button title="{{ __('Delete') }}" class="btn btn-default"><i class="fa-sharp fa-solid fa-trash"></i></button></a>
                                            @endif
                                            <a href="{{ route('admin.services.index', $shop->id) }}"><button title="{{ __('Services') }}" class="btn btn-default"><i class="fa-solid fa-scissors"></i></button></a>
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
