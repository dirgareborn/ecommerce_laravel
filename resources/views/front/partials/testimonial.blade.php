       <!-- Testimonial Start -->
       <div class="container-xxl py-5">
            <div class="container">
                <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                    <h1 class="mb-3 bg-title-navy">Testimoni</h1>
                </div>
                <div class="owl-carousel testimonial-carousel wow fadeInUp" data-wow-delay="0.1s">
                    @foreach($getTestimonial as $testimonial)
                    <div class="testimonial-item bg-light rounded p-3">
                        <div class="bg-white border rounded p-4">
                            <p> {{ $testimonial['description'] }}</p>
                            <div class="d-flex align-items-center">
                                <img class="img-fluid flex-shrink-0 rounded" src="{{ is_user($testimonial['user']['image']) }}" style="width: 45px; height: 45px;">
                                <div class="ps-3">
                                    <h6 class="fw-bold mb-1">{{ $testimonial['user']['name'] }}</h6>
                                    <small>{{ $testimonial['user']['customer_type'] ? :'Umum' }}</small>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <!-- Testimonial End -->
      