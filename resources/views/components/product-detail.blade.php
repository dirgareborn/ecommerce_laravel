@props(['product', 'priceInfo', 'averageRating' => 0, 'totalReviews' => 0])
<!-- Header Start -->
<div class="container-fluid header bg-white p-0">
  <div class="row g-0 align-items-center flex-column-reverse flex-md-row">
    <div class="col-md-6 p-5 mt-lg-5">
     <br>
     <br>
     <br>
     <nav aria-label="breadcrumb animated fadeIn">
      <ol class="breadcrumb text-uppercase">
        @for($i = 1; $i <= count(Request::segments()); $i++) <li class="breadcrumb-item"><a href="{{ URL::to( implode( '/', array_slice(Request::segments(), 0 ,$i, true)))}}">{{strtoupper(Request::segment($i))}}</a></li>
          @endfor
        </ol>
      </nav>
    </div>
  </div>
</div>
<div class="container mb-5">
    <div class="row g-4">
        <!-- Product Images -->
        <div class="col-md-6">
            <div class="position-relative">
                <img src="{{ asset('front/images/products/'.$product['product_image']) }}"
                     class="img-fluid rounded cursor-pointer"
                     style="min-height: 350px; object-fit: cover;"
                     data-bs-toggle="modal" data-bs-target="#lightboxModal" data-index="0">

                <div class="d-flex mt-2 gap-2">
                    @foreach($product['images'] as $key => $image)
                        <img src="{{ asset('front/images/products/'.($key==0 ? $product['product_image'] : 'galery/'.$image['image'])) }}"
                             class="img-thumbnail cursor-pointer"
                             style="height:60px; width:80px; object-fit: cover;"
                             data-bs-toggle="modal" data-bs-target="#lightboxModal" data-index="{{ $key }}">
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Product Info -->
        <div class="col-md-6">
            <h2 class="fw-bold">{{ $product['product_name'] }}</h2>

            <div class="mb-2">
                @for($i = 1; $i <= 5; $i++)
                    <i class="fa fa-star @if($i <= round($averageRating)) text-warning @else text-secondary @endif"></i>
                @endfor
                <span class="ms-2">{{ $totalReviews }} review(s)</span>
            </div>

            <div class="mb-3">
                @if($priceInfo['discount'] > 0)
                    <span class="text-danger me-2">
                        @if($priceInfo['discount_type'] === 'nominal')
                            @currency($priceInfo['discount'])
                        @else
                            {{ $priceInfo['discount_percent'] }}%
                        @endif
                    </span>
                    <del class="text-muted">@currency($priceInfo['product_price'])</del>
                @endif
                <h4 class="mt-2">@currency($priceInfo['final_price'])</h4>
            </div>

            <p>{!! $product['product_facility'] !!}</p>

            <form id="addCart" action="javascript:;" method="POST">
                <input type="hidden" name="product_id" value="{{ $product['id'] }}">
                <div class="row g-2">
                    <div class="col-md-4">
                        <input type="text" class="form-control date" name="start" placeholder="Mulai Tanggal">
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control date" name="end" placeholder="Sampai Tanggal">
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" name="customer_type">
                            <option value="umum">Umum</option>
                            <option value="civitas">Civitas</option>
                            <option value="mahasiswa">Mahasiswa</option>
                        </select>
                    </div>
                </div>
                <div class="mt-3">
                    <button class="btn btn-primary me-2" type="submit">Masukkan Keranjang</button>
                    <button class="btn btn-outline-dark" type="button"><i class="fa fa-heart"></i></button>
                </div>
            </form>

            <div class="mt-4">
                <h5>Deskripsi Produk</h5>
                <p>{{ $product['product_description'] }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Lightbox Modal -->
<div class="modal fade" id="lightboxModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-transparent border-0">
      <div class="modal-body p-0 position-relative">
        <button type="button" class="btn-close position-absolute top-0 end-0 m-3 text-white" data-bs-dismiss="modal" aria-label="Close"></button>
        <img id="lightboxImage" src="" class="w-100 rounded" style="object-fit: contain; max-height: 80vh;">
        <button class="btn btn-dark position-absolute top-50 start-0 translate-middle-y" id="prevImage">
            <i class="fa fa-chevron-left"></i>
        </button>
        <button class="btn btn-dark position-absolute top-50 end-0 translate-middle-y" id="nextImage">
            <i class="fa fa-chevron-right"></i>
        </button>
      </div>
    </div>
  </div>
</div>