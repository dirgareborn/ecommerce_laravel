@extends('front.layouts.app')

@section('content')

@include('front.partials.breadcumb')

<div class="container-xxl py-5">
    <div class="container">

        <div class="text-center mx-auto mb-5 wow fadeInUp" style="max-width: 900px;">
            <h2 class="mb-3">Kerjasama dan Pemanfaatan Aset BPB UNM</h2>
            <p class="mb-0">
                Badan Pengembangan Bisnis (BPB) BLU Universitas Negeri Makassar membuka kesempatan bagi berbagai pihak, baik dari sektor industri, pemerintah, lembaga, maupun masyarakat dan UMKM, untuk menjalin kerjasama dan memanfaatkan aset universitas secara produktif. BPB UNM berkomitmen mengoptimalkan aset institusi serta menciptakan peluang bisnis yang berkelanjutan dan bermanfaat bagi semua pihak.
            </p>
        </div>

        <div class="row g-4">

            <!-- Kolom Kiri: teks tambahan + tombol -->
            <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                <p>
                    Hubungan kerja sama diharapkan mendorong inovasi, memperkuat daya saing, dan memberikan manfaat nyata bagi civitas academica, masyarakat, dan dunia usaha. Dengan prinsip profesionalisme, transparansi, dan keberlanjutan, BPB UNM siap menjadi jembatan yang menghubungkan potensi universitas dengan kebutuhan mitra.
                </p>

                <div class="mt-4 d-flex flex-wrap gap-3">
                    <!-- Tombol WhatsApp -->
                    <a href="https://wa.me/6281234567890?text=Halo%20BPB%20UNM,%20saya%20ingin%20mengajukan%20kerjasama" 
                       target="_blank" 
                       class="btn btn-success btn-lg rounded-pill px-4 shadow btn-hover">
                        <i class="fab fa-whatsapp me-2"></i> Hubungi via WhatsApp
                    </a>

                    <!-- Tombol Formulir -->
                    <a href="https://pep.pps.uny.ac.id/sites/pep.pps.uny.ac.id/files/KUIK%20UNY-%20POB%20Kerjasaama%20Dalam%20Negeri%20dan%20Luar%20Negeri.pdf" 
                       target="_blank" 
                       class="btn btn-primary btn-lg rounded-pill px-4 shadow btn-hover">
                        <i class="fas fa-file-signature me-2"></i> Panduan
                    </a>
                </div>
            </div>

            <!-- Kolom Kanan: card jenis kerjasama -->
            <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.2s">
                <div class="row g-3">

                    <!-- KSO -->
                    <div class="col-12">
                        <div class="card card-hover p-4 h-100 text-white" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-file-contract fa-2x me-3"></i>
                                <h5 class="mb-0">KSO (Kerjasama Operasional)</h5>
                            </div>
                            <p class="mb-0">
                                Kerjasama untuk pengelolaan operasional aset atau layanan tertentu secara bersama.
                            </p>
                        </div>
                    </div>

                    <!-- KSM -->
                    <div class="col-12">
                        <div class="card card-hover p-4 h-100 text-white" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-hand-holding fa-2x me-3"></i>
                                <h5 class="mb-0">KSM (Kerjasama Manfaat)</h5>
                            </div>
                            <p class="mb-0">
                                Kerjasama untuk pemanfaatan aset, fasilitas, atau layanan universitas.
                            </p>
                        </div>
                    </div>

                    <!-- KSO/KSM -->
                    <div class="col-12">
                        <div class="card card-hover p-4 h-100 text-white" style="background: linear-gradient(135deg, #f7971e 0%, #ffd200 100%);">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-handshake fa-2x me-3"></i>
                                <h5 class="mb-0">KSO/KSM</h5>
                            </div>
                            <p class="mb-0">
                                Kombinasi antara pengelolaan operasional dan pemanfaatan aset universitas.
                            </p>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>
</div>

<!-- Custom CSS untuk hover -->
@push('style')
<style>
    /* Card hover scale */
    .card-hover {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: pointer;
    }
    .card-hover:hover {
        transform: scale(1.05);
        box-shadow: 0 20px 30px rgba(0,0,0,0.2);
    }

    /* Tombol hover animasi + shadow */
    .btn-hover {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .btn-hover:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.25);
    }
</style>
@endpush

@endsection 
