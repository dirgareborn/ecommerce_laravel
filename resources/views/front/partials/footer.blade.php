        <!-- Footer Start -->
        <div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
            <div class="container py-5">
                <div class="row g-5">
                    <div class="col-lg-3 col-md-6">
                        <h5 class="text-white mb-4">Alamat Kantor</h5>
                        <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>{{ strip_tags($profil->alamat ?? '') }}</p>
                        <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>{{ $profil->telepon ?? ''}}</p>
                        <p class="mb-2"><i class="fa fa-envelope me-3"></i>{{ $profil->email ?? ''}}</p>
                        <div class="d-flex pt-2">
                            @if (isset($profil->socialmedia))
                                @foreach($profil['socialmedia'] as $sosmed)
                                <a class="btn btn-outline-light btn-social" href="{{ $sosmed['socialmedia_name'] ?? ''}}"><i class="fab {{ $sosmed['socialmedia_icon'] ?? ''}}"></i></a>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h5 class="text-white mb-4">Link</h5>
                        @foreach($QuickLinks as $page)
                        <a class="btn btn-link text-white-50" href="{{ url($page['url']) }}">{{ $page['title']}}</a>
                        @endforeach
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h5 class="text-white mb-4">Galeri</h5>
                        <div class="row g-2 pt-2">
                            @foreach($galery as $g)
                            <div class="col-4">
                                <img class="img-fluid rounded bg-light p-1" src="{{ is_product($g['product_image']) }}" alt="{{ $g['url'] }}">
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
    <h5 class="text-white mb-4">Newsletter</h5>
    <p>Ikuti informasi terbaru kami.</p>
    <div class="position-relative mx-auto" style="max-width: 400px;">
        <form id="newsletterForm">
            @csrf
            <input name="email" id="newsletterEmail" class="form-control bg-transparent w-100 py-3 ps-4 pe-5" type="email" placeholder="email..." required>
            <button type="submit" class="btn btn-primary py-2 position-absolute top-0 end-0 mt-2 me-2">Ikuti</button>
        </form>
    </div>
</div>
                </div>
            </div>
            <div class="container">
                <div class="copyright">
                    <div class="row">
                        <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                            &copy; <span>BPB</span> <a class="" href="https://unm.ac.id">UNM</a>, All Right Reserved. v.{{ app_version() }}
                        </div>
                        <div class="col-md-6 text-center text-md-end">
                            <div class="footer-menu">
                                <a href="{{ route('beranda') }}">Beranda</a>
                                <a href="{{ route('cookies') }}">Cookies</a>
                                <a href="{{ route('faq') }}">FQAs</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
		
        </div>
        <!-- Footer End -->