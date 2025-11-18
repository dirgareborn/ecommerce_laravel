<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProfilWebsitesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        DB::table('profil_websites')->insert([
            'maps' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3973.5976339265867!2d119.43229025214113!3d-5.168243061407906!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dbee346c3c3d419%3A0x6614a72fbc921a6e!2sBadan%20Pengembang%20Bisnis%20BLU%20UNM!5e0!3m2!1sid!2sid!4v1763050344394!5m2!1sid!2sid',
            'alamat' => 'Jl. Pendidikan No.1, Makassar, Sulawesi Selatan',
            'kode_pos' => '90222',
            'telepon' => '+62 411 1234567',
            'email' => 'info@unm.ac.id',
            'file_struktur_organisasi' => '/storage/struktur_organisasi.pdf',
            'file_logo' => '/storage/logo.png',
            'sambutan' => 'Selamat datang di Badan Pengembangan Bisnis Universitas Negeri Makassar. Kami berkomitmen untuk memberikan layanan terbaik dalam pengelolaan bisnis dan kemitraan.',
            'socialmedia' => json_encode([
                [
                    'socialmedia_name' => 'Facebook',
                    'socialmedia_icon' => 'fab fa-facebook-f',
                    'url' => 'https://www.facebook.com/bpb.unm',
                ],
                [
                    'socialmedia_name' => 'Instagram',
                    'socialmedia_icon' => 'fab fa-instagram',
                    'url' => 'https://www.instagram.com/bpb.unm',
                ],
                [
                    'socialmedia_name' => 'Twitter',
                    'socialmedia_icon' => 'fab fa-twitter',
                    'url' => 'https://twitter.com/bpb.unm',
                ],
                [
                    'socialmedia_name' => 'LinkedIn',
                    'socialmedia_icon' => 'fab fa-linkedin-in',
                    'url' => 'https://www.linkedin.com/school/universitas-negeri-makassar/',
                ],
            ]),
            'visi' => 'Menjadi lembaga pengembangan bisnis yang inovatif, profesional, dan berdampak positif bagi masyarakat.',
            'misi' => '1. Mengembangkan usaha yang berkelanjutan. 
2. Meningkatkan kompetensi sumber daya manusia. 
3. Menjalin kemitraan strategis baik lokal maupun internasional.',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
