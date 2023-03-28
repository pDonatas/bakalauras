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
                                {{ __('Create') }}
                            </div>
                        </div>
                        <form method="post" action="{{ route('admin.categories.store') }}">
                            @csrf
                            <div class="card-body">
                                <x-auth-validation-errors class="tw-mb-4" :errors="$errors" />
                                <div class="mb-3">
                                    <label for="name" class="form-label">{{ __('Name') }}</label>
                                    <input type="text" name="name" class="form-control" id="name">
                                </div>
                                <div class="mb-3">
                                    <label for="slug" class="form-label">{{ __('Slug') }}</label>
                                    <input type="text" name="slug" class="form-control" id="slug">
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">{{ __('Description') }}</label>
                                    <textarea name="description" class="form-control" id="description"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="parent_id" class="form-label">{{ __('Parent') }}</label>
                                    <select name="parent_id" class="form-control" id="parent_id">
                                        <option value="0">{{ __('None') }}</option>
                                        @foreach($categories as $category)
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
                            <h3 class="card-title">{{ __('List of Categories') }}</h3>

                            <div class="card-tools">
                                <ul class="pagination pagination-sm float-end">
                                    {{ $categories->links() }}
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
                                @foreach($categories as $category)
                                    <tr>
                                        <td>{{ $category->id }}</td>
                                        <td>{{ $category->name }}</td>
                                        <td>
                                            <a href="{{ route('admin.categories.edit', $category->id) }}"><button title="{{ __('Edit') }}" class="btn btn-default"><i class="fa-solid fa-pen-to-square"></i></button></a>
                                            <a href="#" onclick="deleteItem('{{ route('admin.categories.destroy', $category->id) }}')"><button title="{{ __('Delete') }}" class="btn btn-default"><i class="fa-sharp fa-solid fa-trash"></i></button></a>
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

@section('scripts')
    <script>
        function slugify(val) {
            return val.toString().toLowerCase()
                .replace(/\s+/g, '-')           // Replace spaces with -
                .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
                .replace(/\-\-+/g, '-')         // Replace multiple - with single -
                .replace(/^-+/, '')             // Trim - from start of text
                .replace(/-+$/, '');            // Trim - from end of text
        }

        document.addEventListener("DOMContentLoaded", function() {
            $('#name').on('keyup', function() {
                $('#slug').val(slugify($(this).val()));
            });
        });
    </script>
@endsection
