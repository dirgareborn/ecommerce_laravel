@extends('front.layouts.app')

@section('content')
@include('front.partials.breadcumb')
<!-- Content Start -->
<div class="container-xl py-5">
    <div class="container">
        <h1 class="display-6 mb-5 text-center text-primary animated fadeIn">{{ $page_title }}</h1>

<div class="row justify-content-center">
    <div class="col-lg-12 wow fadeIn" data-wow-delay="0.3s">
        <div class="accordion" id="accordionFAQ">

            <!-- FAQ 1 -->
            <div class="accordion-item mb-3 shadow-sm rounded">
                <h2 class="accordion-header" id="faqHeading1">
                    <button class="accordion-button bg-primary text-white fw-bold" type="button" data-bs-toggle="collapse"
                        data-bs-target="#faqCollapse1" aria-expanded="true" aria-controls="faqCollapse1">
                        <i class="bi bi-question-circle me-2"></i> Apa saja jenis aset yang dapat disewa di Kampus BLU?
                        <span class="badge bg-warning text-dark ms-auto">Populer</span>
                    </button>
                </h2>
                <div id="faqCollapse1" class="accordion-collapse collapse show" aria-labelledby="faqHeading1" data-bs-parent="#accordionFAQ">
                    <div class="accordion-body">
                        Kampus BLU menyediakan berbagai aset yang bisa disewa, termasuk:
                        <ul>
                            <li>Ruang pertemuan</li>
                            <li>Auditorium</li>
                            <li>Laboratorium</li>
                            <li>Peralatan multimedia</li>
                            <li>Lahan parkir</li>
                        </ul>
                        Setiap aset memiliki ketentuan dan tarif sewa yang berbeda.
                    </div>
                </div>
            </div>

            <!-- FAQ 2 -->
            <div class="accordion-item mb-3 shadow-sm rounded">
                <h2 class="accordion-header" id="faqHeading2">
                    <button class="accordion-button collapsed bg-light text-dark fw-bold" type="button" data-bs-toggle="collapse"
                        data-bs-target="#faqCollapse2" aria-expanded="false" aria-controls="faqCollapse2">
                        <i class="bi bi-question-circle me-2"></i> Bagaimana prosedur pemesanan aset?
                    </button>
                </h2>
                <div id="faqCollapse2" class="accordion-collapse collapse" aria-labelledby="faqHeading2" data-bs-parent="#accordionFAQ">
                    <div class="accordion-body">
                        Prosedur pemesanan:
                        <ol>
                            <li>Mengisi formulir pemesanan di website atau kantor administrasi.</li>
                            <li>Menunggu konfirmasi ketersediaan aset.</li>
                            <li>Membayar biaya sewa sesuai ketentuan.</li>
                            <li>Menerima surat konfirmasi dan instruksi penggunaan.</li>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- FAQ 3 -->
            <div class="accordion-item mb-3 shadow-sm rounded">
                <h2 class="accordion-header" id="faqHeading3">
                    <button class="accordion-button collapsed bg-light text-dark fw-bold" type="button" data-bs-toggle="collapse"
                        data-bs-target="#faqCollapse3" aria-expanded="false" aria-controls="faqCollapse3">
                        <i class="bi bi-question-circle me-2"></i> Berapa biaya sewa aset?
                    </button>
                </h2>
                <div id="faqCollapse3" class="accordion-collapse collapse" aria-labelledby="faqHeading3" data-bs-parent="#accordionFAQ">
                    <div class="accordion-body">
                        Biaya sewa bervariasi tergantung jenis aset, durasi, dan fasilitas tambahan. Tarif resmi dapat dilihat di website Kampus BLU atau di bagian administrasi.
                    </div>
                </div>
            </div>

            <!-- FAQ 4 -->
            <div class="accordion-item mb-3 shadow-sm rounded">
                <h2 class="accordion-header" id="faqHeading4">
                    <button class="accordion-button collapsed bg-light text-dark fw-bold" type="button" data-bs-toggle="collapse"
                        data-bs-target="#faqCollapse4" aria-expanded="false" aria-controls="faqCollapse4">
                        <i class="bi bi-question-circle me-2"></i> Apakah ada persyaratan khusus untuk menyewa aset?
                    </button>
                </h2>
                <div id="faqCollapse4" class="accordion-collapse collapse" aria-labelledby="faqHeading4" data-bs-parent="#accordionFAQ">
                    <div class="accordion-body">
                        Persyaratan umum:
                        <ul>
                            <li>Identitas resmi (KTP/NIK atau identitas organisasi).</li>
                            <li>Formulir pemesanan lengkap dan tanda tangan persetujuan aturan penggunaan.</li>
                            <li>Deposit atau pembayaran awal sesuai ketentuan.</li>
                            <li>Menandatangani surat perjanjian sewa jika diperlukan.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- FAQ 5 -->
            <div class="accordion-item mb-3 shadow-sm rounded">
                <h2 class="accordion-header" id="faqHeading5">
                    <button class="accordion-button collapsed bg-light text-dark fw-bold" type="button" data-bs-toggle="collapse"
                        data-bs-target="#faqCollapse5" aria-expanded="false" aria-controls="faqCollapse5">
                        <i class="bi bi-question-circle me-2"></i> Bagaimana cara membatalkan atau mengubah pemesanan?
                    </button>
                </h2>
                <div id="faqCollapse5" class="accordion-collapse collapse" aria-labelledby="faqHeading5" data-bs-parent="#accordionFAQ">
                    <div class="accordion-body">
                        Untuk membatalkan atau mengubah pemesanan:
                        <ul>
                            <li>Hubungi administrasi melalui email atau telepon.</li>
                            <li>Lakukan minimal 3 hari sebelum jadwal sewa.</li>
                            <li>Ikuti ketentuan pengembalian deposit sesuai peraturan Kampus BLU.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- FAQ 6 -->
            <div class="accordion-item mb-3 shadow-sm rounded">
                <h2 class="accordion-header" id="faqHeading6">
                    <button class="accordion-button collapsed bg-light text-dark fw-bold" type="button" data-bs-toggle="collapse"
                        data-bs-target="#faqCollapse6" aria-expanded="false" aria-controls="faqCollapse6">
                        <i class="bi bi-question-circle me-2"></i> Apa yang harus dilakukan jika aset rusak atau bermasalah?
                    </button>
                </h2>
                <div id="faqCollapse6" class="accordion-collapse collapse" aria-labelledby="faqHeading6" data-bs-parent="#accordionFAQ">
                    <div class="accordion-body">
                        Segera laporkan kerusakan atau masalah ke pihak Kampus BLU. Biaya perbaikan akan ditentukan sesuai pemeriksaan dan perjanjian sewa.
                    </div>
                </div>
            </div>

            Terhubung dengan Pusat Layanan? Klik <a href="{{ route('help-desk') }}" class=" fw-bold"> <i class="fas fa-headset "></i> Help-desk </a>


        </div>
    </div>
</div>


    </div>
</div>
<!-- Content End -->
@endsection
