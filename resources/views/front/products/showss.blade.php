@extends('front.layouts.app')
@push('style')

<script type="application/ld+json">
{
  "@context": "https://schema.org/",
  "@type": "Product",
  "name": "{{ $product['product_name'] }}",
  "image": "{{ asset('front/images/products/'.$product['product_image']) }}",
  "description": "{{ $product['product_description'] ?? '' }}",
  "sku": "{{ $product['id'] }}",
  "offers": {
    "@type": "Offer",
    "url": "{{ url('kategori/'.$product['category']['url'].'/'. $product['url']) }}",
    "priceCurrency": "IDR",
    "price": "{{ $product['product_price'] }}",
    "availability": "https://schema.org/InStock"
  }
}
</script>
<style>
  img {
    max-width: 100%; }

    .preview {
      @media (min-width:320px)  {  
        .card {
         padding: 1em;
         margin:10px 10px;
       }
     }
     @media (min-width:480px)  { 
      .card {
       padding: 1em;
       margin:10px 10px;}
     }
   }
   display: -webkit-box;
   display: -webkit-flex;
   display: -ms-flexbox;
   display: flex;
   -webkit-box-orient: vertical;
   -webkit-box-direction: normal;
   -webkit-flex-direction: column;
   -ms-flex-direction: column;
   flex-direction: column; }
   @media screen and (max-width: 996px) {
    .preview {
      margin-bottom: 20px; } }

      .preview-pic {
        -webkit-box-flex: 1;
        -webkit-flex-grow: 1;
        -ms-flex-positive: 1;
        flex-grow: 1; }

        .preview-thumbnail.nav-tabs {
          border: none;
          margin-top: 15px; }
          .preview-thumbnail.nav-tabs li {
            width: 18%;
            margin-right: 2.5%; }
            .preview-thumbnail.nav-tabs li img {
              max-width: 100%;
              display: block; }
              .preview-thumbnail.nav-tabs li a {
                padding: 0;
                margin: 0; }
                .preview-thumbnail.nav-tabs li:last-of-type {
                  margin-right: 0; }

                  .tab-content {
                    overflow: hidden; }
                    .tab-content img {
                      width: 100%;
                      -webkit-animation-name: opacity;
                      animation-name: opacity;
                      -webkit-animation-duration: .3s;
                      animation-duration: .3s; }

                      .card {
                        margin:10px 50px;
                        background: #22222;
                        padding: 0em;
                        line-height: 1.5em; }

                        @media screen and (min-width: 997px) {

                          .wrapper {
                            display: -webkit-box;
                            display: -webkit-flex;
                            display: -ms-flexbox;
                            display: flex; } }

                            .details {
                              display: -webkit-box;
                              display: -webkit-flex;
                              display: -ms-flexbox;
                              display: flex;
                              -webkit-box-orient: vertical;
                              -webkit-box-direction: normal;
                              -webkit-flex-direction: column;
                              -ms-flex-direction: column;
                              flex-direction: column; }

                              .colors {
                                -webkit-box-flex: 1;
                                -webkit-flex-grow: 1;
                                -ms-flex-positive: 1;
                                flex-grow: 1; }

                                .product-title, .price, .sizes, .colors {
                                  text-transform: UPPERCASE;
                                  font-weight: bold; }

                                  .checked, .price span {
                                    color: #ff9f1a; }

                                    .product-title, .rating, .product-description, .price, .vote, .sizes {
                                      margin-bottom: 15px; }

                                      .product-title {
                                        margin-top: 0; }

                                        .size {
                                          margin-right: 10px; }
                                          .size:first-of-type {
                                            margin-left: 40px; }

                                            .color {
                                              display: inline-block;
                                              vertical-align: middle;
                                              margin-right: 10px;
                                              height: 2em;
                                              width: 2em;
                                              border-radius: 2px; }
                                              .color:first-of-type {
                                                margin-left: 20px; }

                                                .add-to-cart, .like {
                                                  background: #ff9f1a;
                                                  padding: 1.2em 1.5em;
                                                  border: none;
                                                  text-transform: UPPERCASE;
                                                  font-weight: bold;
                                                  color: #fff;
                                                  -webkit-transition: background .3s ease;
                                                  transition: background .3s ease; }
                                                  .add-to-cart:hover, .like:hover {
                                                    background: #b36800;
                                                    color: #fff; }



                                                    @-webkit-keyframes opacity {
                                                      0% {
                                                        opacity: 0;
                                                        -webkit-transform: scale(3);
                                                        transform: scale(3); }
                                                        100% {
                                                          opacity: 1;
                                                          -webkit-transform: scale(1);
                                                          transform: scale(1); } }

                                                          @keyframes opacity {
                                                            0% {
                                                              opacity: 0;
                                                              -webkit-transform: scale(3);
                                                              transform: scale(3); }
                                                              100% {
                                                                opacity: 1;
                                                                -webkit-transform: scale(1);
                                                                transform: scale(1); } 
                                                              }

/*# sourceMappingURL=style.css.map */
</style>
@endpush
@section('content')
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

<div >
	<div class="card rounded-0 border-0 animated fadeIn">
		<div class="row g-5 wow fadeIn" data-wow-delay="0.1s">
			<div class="col-md-6 preview">
				<div class="preview-pic tab-content" id="myTabContent">
					@foreach($product['images'] as $key => $image) 
					<div @if($key == 0) class="tab-pane fade show active" @else class="tab-pane" @endif id="pic-{{ $key }}">
						<img @if($key == 0) src="{{ asset('front/images/products/'. $product['product_image']) }}" @else src="{{ asset('front/images/products/galery/'. $image['image']) }}" @endif/></div>
           @endforeach
         </div>
         <ul class="preview-thumbnail nav nav-tabs" role="tablist">
           @foreach($product['images'] as $key => $image)
           <li @if($key == 0) class="active" @else class="" @endif>
            <a data-bs-target="#pic-{{$key}}" data-bs-toggle="tab">
              <img @if($key == 0) src="{{ asset('front/images/products/'. $product['product_image']) }}" @else src="{{ asset('front/images/products/galery/'. $image['image']) }}" @endif/>
            </a>
          </li>
          @endforeach
        </ul>
      </div>
      <div class="details col-md-6">
       <h3 class="product-title">#1 {{ $product['product_name'] }}</h3>
<div class="rating" data-product-id="{{ $product->id }}">
    <div class="stars">
        @for($i = 1; $i <= 5; $i++)
            <span class="fa fa-star {{ $i <= round($average_rating ?? 0) ? 'checked' : '' }}" data-value="{{ $i }}"></span>
        @endfor
    </div>
    <span class="review-no">{{ $total_reviews ?? 0 }} reviews</span>
</div>

<p class="vote"><strong>{{ round(($average_rating ?? 0)/5*100) }}%</strong> of buyers enjoyed this product! 
<strong>({{ $total_reviews ?? 0 }} votes)</strong></p>

@if(auth()->check() && !$userHasReviewed)
<div class="d-flex align-items-center gap-2 my-2 review-form">
    <textarea id="reviewInput" class="form-control flex-grow-1" placeholder="Tulis review (opsional)"></textarea>
    <button id="submitRatingBtn" class="btn btn-primary" type="button" title="Kirim">
        <i class="fa fa-paper-plane"></i>
    </button>
</div>
@endif

<!-- Toast -->
<div id="guestToast" class="toast position-fixed top-50 start-50 translate-middle" role="alert" data-bs-delay="3000">
    <div class="toast-body bg-danger text-white">
        Silahkan login untuk memberikan review!
    </div>
</div>


        <p class="product-description">{{ $product['product_description'] }}</p>
        @if($priceInfo['discount'] > 0)
        <div class="text-danger">
          Diskon 
          @if($priceInfo['discount_type'] === 'nominal')
          @currency($priceInfo['discount'])
          @else
          {{ $priceInfo['discount_percent'] }}%
          @endif
        </div>
        <del class="text-muted">@currency($priceInfo['product_price'])</del>
        @endif
        <h5 class="price">Harga: <span> @currency($priceInfo['final_price'])</span></h5>
        <p class="sizes">Fasilitas : {!! $product['product_facility'] !!}</p>
        <form id="addCart" name="addCart" action="javascript:;" method="POST">
          <!-- @csrf -->
          <input type="hidden" name="product_id" value="{{$product['id']}}">
          <div class="row g-2">
            <div class="col-md-10">
              <div class="row g-2">
                <div class="col-md-4">
                  <input  id="start" name="start" class="date form-control py-3" placeholder="Mulai Tanggal ">
                </div>
                <div class="col-md-4">
                  <input id="end" name="end" class="date form-control  py-3" placeholder="Sampai Tanggal">
                </div>
                <div class="col-md-4">
                  <select name="customer_type" class="form-select py-3">
                    <option selected value="umum">Penyewa</option>
                    <option value="umum">Umum</option>
                    <option value="civitas">Civitas</option>
                    <option value="mahasiswa">Mahasiswa</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <br>
          <div class="action">
            <button class="btn btn-primary" type="submit">Masukkan Keranjang</button>
            <button class="btn btn-dark" type="button"><span class="fa fa-heart"></span></button>
          </div>
        </form>
        <div class="print-error-msg"></div>
        <div class="print-success-msg"></div>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script type="text/javascript">
  var array = ["05-08-2024", "08-08-2024", "17-08-2024", "21-07-2024"];
  $(".date").datepicker({
    dateFormat: "yy-mm-dd",
    beforeShowDay: function(date) {
      var string = jQuery.datepicker.formatDate('dd-mm-yy', date);
        return [array.indexOf(string) == -1]; // disables dates in array
      }
    });   
  document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.stars span');
    const reviewInput = document.getElementById('reviewInput');
    const submitBtn = document.getElementById('submitRatingBtn');
    const ratingDiv = document.querySelector('.rating');
    const productId = ratingDiv?.dataset.productId;
    const rateUrl = `/product/${productId}/rate`; // Sesuaikan route
    let selectedRating = 0;

    // Hover & click stars
    stars.forEach(star => {
        star.addEventListener('mouseover', () => {
            const value = star.dataset.value;
            stars.forEach(s => s.classList.toggle('checked', s.dataset.value <= value));
        });

        star.addEventListener('click', () => {
            selectedRating = star.dataset.value;
        });
    });

    submitBtn?.addEventListener('click', async () => {
        @if(!auth()->check())
            const toast = new bootstrap.Toast(document.getElementById('guestToast'));
            toast.show();
            return;
        @endif

        if(selectedRating === 0) selectedRating = 5; // default rating
        await submitRating(selectedRating, reviewInput?.value);
    });

    async function submitRating(rating, review) {
        try {
            const res = await fetch(rateUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({ rating, review })
            });

            const data = await res.json();
            if(data.success){
                // Update UI
                document.querySelector('.review-no').textContent = `${data.total_reviews} reviews`;
                document.querySelector('.vote strong').textContent = `${Math.round(data.average_rating/5*100)}%`;

                // Hilangkan form review otomatis
                const reviewForm = document.querySelector('.review-form');
                reviewForm?.remove();
            } else {
                alert(data.message);
            }
        } catch(err){
            console.error(err);
            alert('Error submitting rating');
        }
    }
});
</script>
@endpush