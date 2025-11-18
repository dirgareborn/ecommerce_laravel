@extends('front.layouts.app')
@push('style')
<style>
    #centerToast {
        transition: transform 0.3s ease, opacity 0.3s ease;
    }

    #centerToast.showing {
        opacity: 1;
        transform: translateY(0);
    }

    #centerToast.hide {
        opacity: 0;
        transform: translateY(-20px);
    }
</style>
@endpush
@section('content')
@include('front.partials.breadcumb')
<!-- Contact Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="row g-4">
            <!-- Info Kontak -->
            <div class="col-12">
                <div class="row gy-4">
                    <div class="col-md-6 col-lg-4">
                        <div class="bg-light rounded p-3">
                            <div class="d-flex align-items-center bg-white rounded p-3 border border-dashed border-success-subtle">
                                <i class="fa fa-map-marker-alt text-primary me-3" style="font-size: 20px;"></i>
                                <span>{{ strip_tags($kontak->alamat ?? '-') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="bg-light rounded p-3">
                            <div class="d-flex align-items-center bg-white rounded p-3 border border-dashed border-success-subtle">
                                <i class="fa fa-envelope-open text-primary me-3" style="font-size: 20px;"></i>
                                <span>{{ strip_tags($kontak->email ?? '-') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="bg-light rounded p-3">
                            <div class="d-flex align-items-center bg-white rounded p-3 border border-dashed border-success-subtle">
                                <i class="fa fa-phone-alt text-primary me-3" style="font-size: 20px;"></i>
                                <span>{{ strip_tags($kontak->telepon ?? '-') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Maps -->
            <div class="col-md-6">
                <iframe class="position-relative rounded w-100 h-100"
                src="{{ $kontak->maps ?? '' }}"
                frameborder="0"
                style="min-height: 350px; border:0;"
                allowfullscreen=""
                aria-hidden="false"
                tabindex="0"></iframe>
            </div>

            <!-- Formulir Kontak -->
            <div class="col-md-6">
                <form id="contactForm">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" name="name" class="form-control" id="name" placeholder="Nama Anda" required>
                                <label for="name">Nama Anda</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="email" name="email" class="form-control" id="email" placeholder="Email Anda" required>
                                <label for="email">Email Anda</label>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-floating">
                                <input type="text" name="subject" class="form-control" id="subject" placeholder="Subjek">
                                <label for="subject">Subjek</label>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-floating">
                                <textarea name="message" class="form-control" id="message" style="height: 150px" placeholder="Tulis pesan Anda di sini" required></textarea>
                                <label for="message">Pesan</label>
                            </div>
                        </div>

                        <div class="col-12">
                            <button class="btn btn-primary w-100 py-3" type="submit">
                                <i class="fa fa-paper-plane me-2"></i>Kirim Pesan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Contact End -->
@endsection
@push('scripts')
<script>
    $("#contactForm").on("submit", function(e) {
    e.preventDefault();
    const form = $(this);
    const button = form.find("button[type=submit]");

    button.prop("disabled", true).html('<i class="fa fa-spinner fa-spin me-2"></i>Mengirim...');

    $.ajax({
        url: "{{ route('kontak.kirim') }}",
        type: "POST",
        data: form.serialize(),
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(res) {
            if(res.success) {
                showCenterToast(res.message, 'success');
                form[0].reset();
            } else {
                showCenterToast(res.message, 'danger');
            }
            button.prop("disabled", false).html('Kirim Pesan');
        },
        error: function() {
            showCenterToast('Terjadi kesalahan. Coba lagi nanti.', 'danger');
            button.prop("disabled", false).html('Kirim Pesan');
        }
    });
});


  function showCenterToast(message, type = 'success', duration = 3000) {
    const toastEl = document.getElementById('centerToast');
    const toastBody = document.getElementById('centerToastBody');

    // Hapus kelas sebelumnya
    toastEl.classList.remove('bg-success', 'bg-danger');
    toastEl.classList.add(type === 'success' ? 'bg-success' : 'bg-danger');

    toastBody.innerHTML = message;

    // Tampilkan toast
    toastEl.style.display = 'block';
    toastEl.classList.add('showing');

    // Bootstrap toast instance
    const bsToast = new bootstrap.Toast(toastEl, { delay: duration });
    bsToast.show();

    // Setelah toast hilang, sembunyikan elemen
    toastEl.addEventListener('hidden.bs.toast', () => {
        toastEl.style.display = 'none';
        toastEl.classList.remove('showing');
    }, { once: true });
}
</script>
@endpush



