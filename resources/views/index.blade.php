@extends('layouts.main')

@section('content')
    @foreach($shops as $shop)
        <div class="shop">
            <h2>{{ $shop->name }}</h2>
            <p>{{ $shop->description }}</p>
        </div>
    @endforeach
@endsection
