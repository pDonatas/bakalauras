@extends('layouts.main')
@section('title') {{ $shop->name }} @endsection
@section('content')
    <div class="row g-4">
        <!-- Start column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-default">
                <div class="card-header">
                    <div class="card-title">
                        <div class="row">
                            <div class="col-md-6">
                                {{ $shop->name }}
                            </div>
                            <div class="col-md-6">
                                <div class="rating float-end">
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>

                                    <span>7821 vertinimai</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body shop">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="carouselExampleCaptions" class="carousel slide shop-top-slide" data-bs-ride="false">
                                <div class="carousel-indicators">
                                    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                                    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
                                    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
                                </div>
                                <div class="carousel-inner">
                                    <div class="carousel-item active">
                                        <img src="https://dummyimage.com/600x400/000/fff" class="d-block" alt="">
                                        <div class="carousel-caption d-none d-md-block">
                                            <h5>First slide label</h5>
                                            <p>Some representative placeholder content for the first slide.</p>
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <img src="https://dummyimage.com/600x400/000/fff" class="d-block" alt="">
                                        <div class="carousel-caption d-none d-md-block">
                                            <h5>Second slide label</h5>
                                            <p>Some representative placeholder content for the second slide.</p>
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <img src="https://dummyimage.com/600x400/000/fff" class="d-block " alt="">
                                        <div class="carousel-caption d-none d-md-block">
                                            <h5>Third slide label</h5>
                                            <p>Some representative placeholder content for the third slide.</p>
                                        </div>
                                    </div>
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="navigation">
                                <nav class="nav nav-pills flex-column flex-sm-row">
                                    <a
                                        class="flex-sm-fill text-sm-center nav-link active" aria-current="page"
                                        id="about-tab" data-bs-toggle="tab" data-bs-target="#about-tab-pane" type="button"
                                        role="tab" aria-controls="about-tab-pane" aria-selected="true">
                                        Apie
                                    </a>
                                    <a
                                        class="flex-sm-fill text-sm-center nav-link" aria-current="page"
                                        id="rating-tab" data-bs-toggle="tab" data-bs-target="#rating-tab-pane" type="button"
                                        role="tab" aria-controls="rating-tab-pane" aria-selected="false">
                                        Vertinimai
                                    </a>
                                    @foreach($shop->pages as $page)
                                        <a
                                            class="flex-sm-fill text-sm-center nav-link" aria-current="page"
                                            id="{{ $page->name }}{{ $page->id }}-tab" data-bs-toggle="tab" data-bs-target="#{{ $page->name }}{{ $page->id }}-tab-pane" type="button"
                                            role="tab" aria-controls="{{ $page->name }}{{ $page->id }}-tab-pane" aria-selected="false">
                                            {{ $page->name }}
                                        </a>
                                    @endforeach
                                </nav>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="buttons float-end">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal" class="btn btn-primary">Vertinti</a>
                                <a href="#" class="btn btn-primary">Palyginti</a>
                                <a href="#" class="btn btn-primary">Pridėti į mėgstamus</a>
                                <a href="#" class="btn btn-primary">Registruotis</a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="about-tab-pane" role="tabpanel" aria-labelledby="about-tab" tabindex="0">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="about">
                                            <h3>Apie</h3>
                                            {!! $shop->description !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="rating-tab-pane" role="tabpanel" aria-labelledby="rating-tab" tabindex="0">
                                Vertinimai
                            </div>
                            @foreach($shop->pages as $page)
                                <div class="tab-pane fade" id="{{ $page->name }}{{ $page->id }}-tab-pane" role="tabpanel" aria-labelledby="{{ $page->name }}{{ $page->id }}--tab" tabindex="0">{!! $page->description !!}</div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">{{ $shop->name }} vertinimas</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('vote', $shop->id) }}">
                        @csrf
                        <div class="mb-3">
                            <label for="rating" class="col-form-label">Vertinimas:</label>
                            <input type="number" class="form-control" id="rating" name="rating" min="1" max="5" required>
                        </div>
                        <div class="mb-3">
                            <label for="comment" class="col-form-label">Komentaras:</label>
                            <textarea class="form-control" id="comment" name="comment" required></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Uždaryti</button>
                            <button type="submit" class="btn btn-primary">Išsaugoti</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
@endsection
