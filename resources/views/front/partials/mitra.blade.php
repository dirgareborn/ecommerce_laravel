<div class="container my-5">
    <h5 class="text-center fw-bold mb-4 bg-title-orange">Mitra Kerjasama</h5>
    <div class="overflow-hidden">
        <div class="d-flex align-items-center gap-5 slide-track">
            @foreach($mitra as $m)
                <img src="{{ asset('uploads/mitra/'.$m->logo) }}" 
                     alt="{{ $m->nama }}" 
                     style="height: 60px; object-fit: contain;">
            @endforeach
        </div>
    </div>
</div>
