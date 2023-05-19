@props(['errors'])

@if ($errors->any())
    <div {{ $attributes }}>
        <div class="alert alert-danger">
            {{ __('Whoops! Something went wrong.') }}

            <ul class="list-group">
                @foreach ($errors->all() as $error)
                    <li class="list-group-item">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
