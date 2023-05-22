@extends('layouts.admin')
@section('title') {{ __('Newsletters') }} @endsection
@section('content')
    <div class="row g-4">
        <!-- Start column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <div class="card-title">
                        {{ __('Newsletters') }}
                    </div>
                </div>
                <div class="card-body">
                    <div class="card card-secondary card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                {{ __('Create') }}
                            </div>
                        </div>
                        <form method="post" action="{{ route('admin.newsletters.store') }}">
                            @csrf
                            <div class="card-body">
                                <x-auth-validation-errors class="tw-mb-4" :errors="$errors" />
                                <div class="mb-3">
                                    <label for="title" class="form-label">{{ __('Title') }}</label>
                                    <input name="title" class="form-control" id="title" type="text" />
                                </div>
                                <div class="mb-3">
                                    <label for="text" class="form-label">{{ __('Text') }}</label>
                                    <textarea name="text" class="form-control" id="text"></textarea>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
                            </div>
                        </form>
                    </div>
                    <div class="card card-secondary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('List of Newsletters') }}</h3>

                            <div class="card-tools">
                                <ul class="pagination pagination-sm float-end">
                                    {{ $newsletters->links() }}
                                </ul>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-0">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th style="width: 10px">{{ __('ID') }}</th>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Created At') }}</th>
                                    <th style="width: 130px">{{ __('Action') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($newsletters as $newsletter)
                                    <tr>
                                        <td>{{ $newsletter->id }}</td>
                                        <td>{{ $newsletter->title }}</td>
                                        <td>{{ $newsletter->created_at->format("Y-m-d H:i") }}</td>
                                        <td>
                                            <a href="{{ route('admin.newsletters.edit', $newsletter->id) }}"><button title="{{ __('Edit') }}" class="btn btn-default"><i class="fa-solid fa-pen-to-square"></i></button></a>
                                            <a href="#" onclick="deleteItem('{{ route('admin.newsletters.destroy', $newsletter->id) }}')"><button title="{{ __('Delete') }}" class="btn btn-default"><i class="fa-sharp fa-solid fa-trash"></i></button></a>
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
