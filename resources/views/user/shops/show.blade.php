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
                                        <img src="https://dummyimage.com/600x400/000/fff" class="d-block" alt="...">
                                        <div class="carousel-caption d-none d-md-block">
                                            <h5>First slide label</h5>
                                            <p>Some representative placeholder content for the first slide.</p>
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <img src="https://dummyimage.com/600x400/000/fff" class="d-block" alt="...">
                                        <div class="carousel-caption d-none d-md-block">
                                            <h5>Second slide label</h5>
                                            <p>Some representative placeholder content for the second slide.</p>
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <img src="https://dummyimage.com/600x400/000/fff" class="d-block " alt="...">
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
                                    <a
                                        class="flex-sm-fill text-sm-center nav-link" aria-current="page"
                                        id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-tab-pane" type="button"
                                        role="tab" aria-controls="contact-tab-pane" aria-selected="false">
                                        Kontaktai
                                    </a>
                                </nav>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="buttons float-end">
                                <a href="#" class="btn btn-primary">Vertinti</a>
                                <a href="#" class="btn btn-primary">Palyginti</a>
                                <a href="#" class="btn btn-primary">Prid??ti ?? m??gstamus</a>
                                <a href="#" class="btn btn-primary">Registruotis</a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="about-tab-pane" role="tabpanel" aria-labelledby="about-tab" tabindex="0">Apie</div>
                            <div class="tab-pane fade" id="rating-tab-pane" role="tabpanel" aria-labelledby="rating-tab" tabindex="0">Vertinimai</div>
                            <div class="tab-pane fade" id="contact-tab-pane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">Kontaktai</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
