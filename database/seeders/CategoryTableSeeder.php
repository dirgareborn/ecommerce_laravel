<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CategoryTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('categories')->truncate();

        // Parent categories
        $parents = [
            1 => 'Sarana Olahraga',
            2 => 'Hunian & Akomodasi',
            3 => 'Event Space & Venue',
            4 => 'Area Komersial',
            5 => 'Peralatan & Mesin',
            6 => 'Pelatihan',
            7 => 'Media & Publikasi',
        ];

        $children = [
            1 => ['Lapangan Sepakbola', 'Lapangan Bulutangkis', 'Lapangan Basket'],
            2 => ['Rusunawa', 'Ramsis', 'Hotel / Guest House'],
            3 => ['Ballroom', 'Ruang Rapat', 'Ruang Kelas', 'Ruang Seminar', 'Gedung Pernikahan'],
            4 => ['Kantin', 'Foodcourt', 'Ruko', 'Lahan Terbuka'],
            5 => ['Bus Pariwisata', 'Mobil Dinas', 'Proyektor', 'Sound System', 'Kursi Lipat', 'Meja Kotak', 'Tenda'],
            6 => ['UPA Bahasa', 'Pelatihan Bimbingan Konseling', 'Tes Psikologi Pegawai', 'Pelatihan / Sertifikasi Lainnya'],
            7 => ['Videotron'],
        ];

        // Insert Parent Categories
        foreach ($parents as $id => $name) {
            DB::table('categories')->insert([
                'id'            => $id,
                'parent_id'     => null,
                'category_name' => $name,
                'category_image'=> null,
                'url'           => Str::slug($name),
                'status'        => 1,
            ]);
        }

        // Insert Child Categories
        foreach ($children as $parentId => $items) {
            foreach ($items as $childName) {
                DB::table('categories')->insert([
                    'parent_id'     => $parentId,
                    'category_name' => $childName,
                    'category_image'=> null,
                    'url'           => Str::slug($childName),
                    'status'        => 1,
                ]);
            }
        }
    }
}