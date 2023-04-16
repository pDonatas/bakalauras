@extends('layouts.main')

@section('title')
    {{ __('Leave a review') }}
@endsection

@section('content')
    <x-auth-validation-errors />
    <form method="post" action="{{ route('vote', $shop->id) }}">
        <div class="modal-body">
            @csrf
            <div class="mb-3">
                <label for="mark" class="col-form-label">Vertinimas:</label>
                <input type="number" class="form-control" id="mark" name="mark" min="1" max="5"
                       required>
            </div>
            <div class="mb-3">
                <label for="comment" class="col-form-label">Komentaras:</label>
                <textarea class="form-control" id="comment" name="comment"></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Uždaryti</button>
            <button type="submit" class="btn btn-primary">Išsaugoti</button>
        </div>
    </form>
@endsection
