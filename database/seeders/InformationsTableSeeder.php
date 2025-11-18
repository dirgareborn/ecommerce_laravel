<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Information;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use Carbon\Carbon;

class InformationsTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $data = [
            [
                'title' => 'Strategi Sukses Belajar di Era Digital untuk Mahasiswa',
                'image' => '',
                'content' => '<p>Dalam era digital, mahasiswa dituntut untuk mampu belajar secara mandiri dengan memanfaatkan berbagai platform online. Dengan perkembangan teknologi, banyak materi kuliah kini tersedia secara daring melalui LMS kampus atau platform pembelajaran online seperti Coursera, Udemy, dan edX. Mahasiswa harus belajar mengelola waktu dan membuat jadwal rutin, karena belajar online membutuhkan disiplin lebih tinggi dibanding belajar tatap muka. Selain itu, kemampuan literasi digital sangat penting, termasuk mengidentifikasi sumber informasi yang valid, mengelola dokumen digital, dan menggunakan perangkat lunak kolaborasi. </p>

                <p>Mahasiswa juga dianjurkan untuk ikut forum diskusi online, grup studi, atau komunitas akademik untuk saling bertukar pengetahuan. Dengan memanfaatkan teknologi dan strategi belajar yang tepat, mahasiswa dapat meningkatkan pemahaman materi, memperluas jaringan profesional, dan menyiapkan diri untuk tantangan dunia kerja. Penting juga untuk menjaga keseimbangan antara aktivitas belajar daring dengan kegiatan sosial dan fisik agar tetap sehat dan produktif.</p>

                <p>Selain itu, mahasiswa dapat memanfaatkan aplikasi manajemen tugas dan catatan digital untuk mencatat dan mengorganisir materi kuliah. Kemampuan multitasking dengan tetap fokus pada prioritas menjadi kunci sukses belajar di era digital. Dengan kombinasi strategi, disiplin, dan teknologi, mahasiswa dapat mengoptimalkan pengalaman belajar dan meningkatkan prestasi akademik.</p>'
            ],
            [
                'title' => 'Peluang Usaha Kreatif bagi Mahasiswa di Tengah Pandemi',
                'image' => '',
                'content' => '<p>Mahasiswa memiliki peluang besar untuk memulai usaha kreatif, terutama di tengah pandemi yang mengubah pola konsumsi masyarakat. Salah satu peluang adalah menjual produk digital, seperti desain grafis, template, ebook, atau kursus online. Dengan biaya awal rendah, mahasiswa bisa memanfaatkan keterampilan yang dimiliki dan menjualnya melalui platform online seperti Instagram, Tokopedia, atau marketplace global.</p>

                <p>Selain produk digital, usaha makanan dan minuman rumahan juga populer di kalangan mahasiswa. Membuat menu unik dengan bahan lokal dan menjualnya melalui media sosial dapat menjadi sumber penghasilan tambahan. Penting untuk membuat brand yang menarik, melakukan promosi digital, dan menjaga kualitas produk agar pelanggan puas dan kembali membeli.</p>

                <p>Mahasiswa juga bisa memulai usaha jasa, seperti jasa penulisan, konsultasi akademik, atau kursus privat. Dengan menggabungkan kreativitas, teknologi, dan pemasaran digital, mahasiswa dapat mengembangkan usaha yang menguntungkan sambil tetap fokus pada studi mereka. Mengikuti pelatihan bisnis dan mentoring juga membantu mengurangi risiko dan meningkatkan peluang keberhasilan.</p>'
            ],
            [
                'title' => 'Workshop Kewirausahaan untuk Mahasiswa: Dari Ide hingga Implementasi',
                'image' => '',
                'content' => '<p>Workshop kewirausahaan untuk mahasiswa bertujuan untuk mengajarkan proses dari ide hingga implementasi bisnis. Mahasiswa belajar menganalisis peluang pasar, membuat rencana bisnis, dan mengembangkan prototype produk. Kegiatan ini menggabungkan teori dan praktik sehingga mahasiswa dapat merasakan tantangan nyata dalam memulai usaha.</p>

                <p>Peserta workshop juga diajarkan tentang manajemen keuangan, strategi pemasaran digital, dan cara membangun tim yang efektif. Sesi mentoring dari pengusaha sukses memberikan insight berharga tentang strategi yang terbukti berhasil. Mahasiswa didorong untuk membuat pitch produk dan mempresentasikan ide mereka di depan panel juri, yang sekaligus melatih kemampuan public speaking dan negosiasi.</p>

                <p>Dengan mengikuti workshop, mahasiswa tidak hanya mendapatkan pengetahuan bisnis, tetapi juga membangun jaringan dengan rekan peserta dan mentor, yang dapat mendukung pengembangan usaha di masa depan. Workshop ini menjadi salah satu jalan bagi mahasiswa untuk mulai berwirausaha sejak dini dan meningkatkan keterampilan praktis yang dibutuhkan di dunia kerja.</p>'
            ],
            [
                'title' => 'Pemanfaatan Teknologi AI dalam Pembelajaran Mahasiswa',
                'image' => '',
                'content' => '<p>Teknologi kecerdasan buatan (AI) mulai banyak dimanfaatkan dalam dunia pendidikan. Mahasiswa dapat menggunakan AI untuk membantu belajar, seperti chatbot untuk tanya jawab materi, aplikasi penerjemah otomatis, atau sistem rekomendasi materi belajar berdasarkan kemampuan masing-masing siswa. Dengan bantuan AI, proses belajar menjadi lebih personal dan efisien.</p>

                <p>Selain itu, dosen dapat memanfaatkan AI untuk menganalisis performa mahasiswa, memberikan feedback otomatis, dan memprediksi area yang membutuhkan perbaikan. Mahasiswa pun didorong untuk memahami dasar-dasar AI agar dapat memanfaatkan teknologi ini secara optimal, sekaligus menambah skill yang relevan di dunia kerja masa depan.</p>'
            ],
            [
                'title' => 'Membangun Usaha Kecil Menengah di Kampus',
                'image' => '',
                'content' => '<p>Usaha Kecil Menengah (UKM) di lingkungan kampus bisa menjadi peluang emas bagi mahasiswa. Contohnya, membuka kedai kopi, jasa fotokopi, atau jualan makanan ringan dengan konsep unik. Dengan modal terbatas dan kreativitas, mahasiswa bisa menghasilkan keuntungan sambil belajar mengelola bisnis.</p>

                <p>Mahasiswa diajarkan untuk membuat rencana bisnis sederhana, menghitung modal, biaya operasional, hingga strategi pemasaran. Hal ini membantu membangun pemahaman tentang manajemen usaha nyata, sekaligus meningkatkan soft skill seperti komunikasi, negosiasi, dan teamwork. Dengan perencanaan matang, UKM kampus dapat berkembang dan memberikan pengalaman praktis berharga.</p>'
            ],
            [
                'title' => 'Pentingnya Literasi Digital bagi Generasi Muda',
                'image' => '',
                'content' => '<p>Di era informasi, literasi digital menjadi keterampilan penting bagi mahasiswa dan generasi muda. Literasi digital mencakup kemampuan menilai kredibilitas informasi, keamanan online, penggunaan software produktivitas, dan pemanfaatan media sosial secara sehat.</p>

                <p>Dengan literasi digital yang baik, mahasiswa mampu mengambil keputusan lebih tepat, menghindari hoaks, dan meningkatkan efisiensi belajar. Literasi digital juga membuka peluang usaha baru, misalnya di bidang marketing digital, content creation, atau e-commerce. Pendidikan formal dan pelatihan non-formal dapat menjadi sarana penting untuk meningkatkan literasi digital generasi muda.</p>'
            ],
            [
                'title' => 'Manajemen Waktu untuk Mahasiswa dan Entrepreneur Muda',
                'image' => '',
                'content' => '<p>Manajemen waktu adalah kunci sukses bagi mahasiswa yang ingin tetap produktif sambil mengelola usaha. Mengatur prioritas, membuat jadwal harian, dan menetapkan target mingguan membantu menjaga fokus dan menghindari stres. Mahasiswa yang menjalankan bisnis harus bisa menyeimbangkan aktivitas akademik dan kewirausahaan.</p>

                <p>Tools digital seperti Google Calendar, Trello, atau Notion dapat membantu merencanakan kegiatan secara lebih efisien. Dengan manajemen waktu yang tepat, mahasiswa tidak hanya mencapai target akademik tetapi juga mengembangkan usaha dengan lebih profesional, membangun reputasi, dan meningkatkan peluang sukses di dunia nyata.</p>'
            ],
            [
                'title' => 'Inovasi Produk Kreatif dari Mahasiswa',
                'image' => '',
                'content' => '<p>Mahasiswa sering menjadi pionir dalam menciptakan produk kreatif. Contohnya, pembuatan aplikasi mobile, kerajinan tangan inovatif, atau solusi digital untuk kebutuhan masyarakat. Kreativitas ini mendorong mahasiswa untuk berpikir kritis, menyelesaikan masalah, dan menghasilkan produk bernilai ekonomi.</p>

                <p>Selain itu, pengembangan produk kreatif mengajarkan mahasiswa tentang proses prototyping, branding, hingga pemasaran digital. Mahasiswa dapat mengikuti kompetisi inovasi atau inkubator bisnis kampus untuk memperluas jaringan dan mendapatkan bimbingan mentor. Produk kreatif mahasiswa berpotensi menjadi usaha yang sustainable di masa depan.</p>'
            ],
            [
                'title' => 'Digital Marketing untuk Pemula: Panduan Mahasiswa',
                'image' => '',
                'content' => '<p>Digital marketing menjadi keterampilan wajib di era bisnis online. Mahasiswa dapat belajar tentang SEO, social media marketing, email marketing, dan iklan berbayar. Dengan memahami dasar-dasar digital marketing, mahasiswa bisa mempromosikan produk kampus, usaha kecil, atau proyek pribadi secara efektif.</p>

                <p>Selain teori, praktik langsung melalui media sosial, website, dan kampanye digital sangat penting untuk memahami target audiens, engagement, dan konversi. Penguasaan digital marketing tidak hanya meningkatkan peluang usaha, tetapi juga menambah skill yang diminati industri kreatif dan startup.</p>'
            ],
            [
                'title' => 'Etika dan Tanggung Jawab dalam Berbisnis untuk Mahasiswa',
                'image' => '',
                'content' => '<p>Etika bisnis sangat penting bagi mahasiswa yang mulai merintis usaha. Etika mencakup kejujuran, transparansi, tanggung jawab terhadap pelanggan, dan kepatuhan terhadap hukum. Dengan menerapkan etika yang baik, mahasiswa membangun reputasi positif yang akan menguntungkan usaha mereka di jangka panjang.</p>

                <p>Mahasiswa perlu memahami bahwa kesuksesan usaha bukan hanya diukur dari keuntungan, tetapi juga dari dampak positif terhadap komunitas dan pelanggan. Pelatihan kewirausahaan, seminar, dan mentoring dapat membantu mahasiswa memahami praktik bisnis yang etis sekaligus meningkatkan kepercayaan pasar terhadap produk atau jasa mereka.</p>'
            ],
        ];

        foreach($data as $info){
            Information::create([
                'title' => $info['title'],
                'content' => $info['content'],
                'admin_id' => 1,
                'slug' => Str::slug($info['title']),
                'status' => 'published',
                'published_at' => $faker->dateTimeBetween('-6 months', 'now'),
            ]);
        }
    }
}
