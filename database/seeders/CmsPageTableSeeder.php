<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CmsPage;
class CmsPageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cmsPagesRecords = [
        [   
            'id' => 1,
            'title' => 'Kontak Kami',
            'description' => 'Content is coming soon',
            'url' => 'kontak-kami',
            'meta_title' => 'Kontak Kami',
            'meta_description' => 'Kontak Kami Content',
            'meta_keywords' => 'Kontak Kami, Kontak',
            'status' => 1,
        ],
        [   
            'id' => 3,
            'title' => 'Kebijakan Privasi',
            'description' => 'Content is coming soon',
            'url' => 'kebijakan-privasi',
            'meta_title' => 'Kebijakan Privasi',
            'meta_description' => 'Kebijakan Privasi Content',
            'meta_keywords' => 'Kebijakan Privasi, Privasi',
            'status' => 1,
        ],
    ];

    CmsPage::insert($cmsPagesRecords);
    }
}
