@extends('layouts.web')

@section('content')
@include('front.partials.breadcumb')
<!-- Team Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
            <h1 class="mb-3">{{ $page_title }}</h1>
            <p>{{ $page_description }}</p>
        </div>

        <div class="row g-4">
            @foreach($teams as $team)
                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="team-item rounded overflow-hidden">
                        <div class="position-relative">
                            <img class="img-fluid" src="/images/employees/{{ $team->image }}" alt="">
                            <div class="position-absolute start-50 top-100 translate-middle d-flex align-items-center">
                                <a class="btn btn-square mx-1" href="#"><i class="fab fa-facebook-f"></i></a>
                                <a class="btn btn-square mx-1" href="#"><i class="fab fa-twitter"></i></a>
                                <a class="btn btn-square mx-1" href="#"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                        <div class="text-center p-4 mt-3">
                            <h5 class="fw-bold mb-0">{{ $team->name }}</h5>
                            <small>{{ $team->jobs->job_name }}</small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
<!-- Team End -->

@endsection
