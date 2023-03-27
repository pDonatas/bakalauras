@extends('layouts.admin')
@section('title') {{ __('Photos') }} @endsection
@section('content')
    <div class="row g-4">
        <!-- Start column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <div class="card-title">
                        {{ __('Photos') }}
                    </div>
                </div>
                <div class="card-body">
                    <div class="card card-secondary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('Add photos') }}</h3>
                        </div>
                        <div class="card-body">
                            <div id="dropzone">
                                <form action="{{ route('admin.photos.store', [$shop->id, $service->id]) }}" class="dropzone" id="upload">
                                    @csrf
                                    <div class="fallback">
                                        <input name="file" type="file" multiple />
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card card-secondary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('List of Photos') }}</h3>

                            <div class="card-tools">
                                <ul class="pagination pagination-sm float-end">
                                    {{ $photos->links() }}
                                </ul>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-0">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th style="width: 10px">{{ __('ID') }}</th>
                                    <th>{{ __('Service') }}</th>
                                    <th>{{ __('Image') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($photos as $photo)
                                    <tr>
                                        <td>{{ $photo->id }}</td>
                                        <td>{{ $photo->service->name }}</td>
                                        <td><img src="{{ url($photo->path) }}" alt="{{ $photo->service->name}}" class="img-thumbnail"/></td>
                                        <td>
                                            <a href="#" onclick="deleteItem('{{ route('admin.photos.destroy', [$shop->id, $service->id, $photo->id]) }}')"><button title="{{ __('Delete') }}" class="btn btn-default"><i class="fa-sharp fa-solid fa-trash"></i></button></a>
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
@section('js')
    <script type="module">
        $(document).ready(function() {
            new Dropzone('#upload', {
                queuecomplete: function() {
                    location.reload();
                }
            });
        });
    </script>
@endsection
@section('styles')
    <style>
        img {
            max-width: 100px !important;
        }
    </style>
@endsection
