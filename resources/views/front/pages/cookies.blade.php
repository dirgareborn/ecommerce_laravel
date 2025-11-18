@extends('front.layouts.app')

@section('content')
@include('front.partials.breadcumb')
<div class="container-xxl py-5">
    <div class="container">
        {{-- Cookie Policy Content --}}
        <div class="row justify-content-center">
                <div class="card rounded-3 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="card-body p-4 p-sm-5">
                        <h2 class="text-primary fw-bold mb-4 text-center">Cookie Policy</h2>
                        
                        <p>Website ini menggunakan <strong>cookies</strong> untuk meningkatkan pengalaman pengguna dan menyediakan layanan yang lebih baik. Dengan melanjutkan penggunaan situs ini, Anda menyetujui penggunaan cookies sesuai dengan kebijakan kami.</p>

                        <h5 class="mt-4">Jenis Cookies yang Kami Gunakan</h5>
                        <ul>
                            <li><strong>Essential Cookies:</strong> Digunakan untuk fungsi dasar situs web seperti keamanan, login, dan preferensi pengguna.</li>
                            <li><strong>Performance Cookies:</strong> Membantu kami menganalisis bagaimana pengunjung menggunakan situs untuk meningkatkan kualitas konten.</li>
                            <li><strong>Functional Cookies:</strong> Menyimpan pilihan pengguna, misalnya bahasa atau lokasi, agar pengalaman lebih personal.</li>
                            <li><strong>Marketing Cookies:</strong> Digunakan untuk menampilkan iklan yang relevan sesuai minat pengguna.</li>
                        </ul>

                        <h5 class="mt-4">Pengelolaan Cookies</h5>
                        <p>Anda dapat memilih untuk menolak cookies melalui pengaturan browser Anda. Namun, beberapa fitur situs mungkin tidak berfungsi dengan optimal jika cookies dinonaktifkan.</p>

                        <h5 class="mt-4">Kontak</h5>
                        <p>Jika Anda memiliki pertanyaan mengenai kebijakan cookies kami, silakan hubungi kami melalui <a href="mailto:info@unm.ac.id">info@unm.ac.id</a>.</p>

                        <div class="text-center mt-4">
                            <a href="{{ url('/') }}" class="btn btn-primary btn-lg">Kembali ke Beranda</a>
                        </div>

                        <p class="text-center text-muted mt-4 small">&copy; {{ date('Y') }} BPB UNM</p>
                    </div>
                </div>
            </div>
    </div>
</div>
@endsection
