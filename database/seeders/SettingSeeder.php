<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'school_name' => 'SMK Taruna Sains Kediri',
            'school_short_name' => 'SMK Taruna Sains',
            'school_npsn' => '70064410',
            'school_tagline' => 'Membangun generasi yang siap belajar, berkarya, dan berkembang.',
            'school_address' => 'Dusun Pule, Desa Karangpakis, Kec. Purwoasri, Kab. Kediri, Jawa Timur 64154',
            'school_city' => 'Kediri, Jawa Timur',
            'school_phone' => '(0354) 512345',
            'school_whatsapp' => '0812-3456-7890',
            'school_email' => 'smk.tarunasains@gmail.com',
            'school_map_embed' => 'https://www.openstreetmap.org/export/embed.html?bbox=112.1195%2C-7.6205%2C112.1395%2C-7.6005&layer=mapnik&marker=-7.6105%2C112.1295',
            'school_map_label' => 'Kampus SMK Taruna Sains Kediri, Purwoasri',
            'school_opening_hours' => "Senin–Jumat : 06.30 – 15.30 WIB\nSabtu : 07.00 – 12.00 WIB",

            'principal_name' => 'H. Bambang Susilo, M.Pd.',
            'principal_photo' => null,
            'principal_message' => "Assalamualaikum warahmatullahi wabarakatuh.\n\nSelamat datang di SMK Taruna Sains Kediri. Keluarga besar kami percaya bahwa setiap anak memiliki potensi untuk tumbuh menjadi pribadi yang kompeten, berkarakter, dan siap menghadapi dunia kerja maupun melanjutkan studi.\n\nMelalui pembelajaran berbasis proyek, kerja sama dengan industri, serta pembinaan karakter yang konsisten, kami berkomitmen menghadirkan pendidikan sekolah menengah kejuruan yang relevan dengan kebutuhan zaman. Saya mengundang para calon peserta didik dan orang tua untuk bergabung bersama keluarga besar SMK Taruna Sains Kediri.",

            'school_vision' => "Terwujudnya peserta didik yang beriman, berkarakter Pancasila, unggul dalam keterampilan teknologi dan alat berat, berwawasan lingkungan, dan siap bersaing di tingkat nasional maupun global.",
            'school_mission' => "Menyelenggarakan pembelajaran berbasis proyek dan industri yang relevan dengan kebutuhan dunia kerja\nMembina karakter peserta didik yang beriman, disiplin, jujur, dan tanggung jawab\nMengembangkan kompetensi guru dan tenaga kependidikan yang profesional\nMembangun kemitraan strategis dengan dunia usaha dan dunia industri\nMewujudkan lingkungan sekolah yang hijau, sehat, dan aman\nMelaksanakan tata kelola sekolah yang transparan, akuntabel, dan partisipatif",

            'school_history' => "SMK Taruna Sains Kediri berdiri sebagai Sekolah Menengah Kejuruan yang bertujuan menjawab kebutuhan tenaga kerja menengah sektor alat berat di wilayah Kabupaten Kediri dan sekitarnya. Berbekal semangat pantang menyerah, sekolah ini tumbuh bersama masyarakat dan industri.\n\nSebagai sekolah kejuruan, kami berkomitmen menghadirkan pembelajaran yang terhubung langsung dengan dunia kerja. Dengan satu kompetensi keahlian unggulan, Teknik Alat Berat, sekolah terus melengkapi kampus dengan fasilitas praktik: bengkel alat berat, kelas pengoperasian, dan laboratorium pendukung. Hingga saat ini SMK Taruna Sains Kediri telah meluluskan ratusan alumni yang tersebar di dunia kerja, dunia industri, dan perguruan tinggi.",

            'school_values' => json_encode([
                ['title' => 'Beriman & Bertakwa', 'text' => 'Ibadah dan akhlak mulia menjadi fondasi setiap kegiatan di sekolah.'],
                ['title' => 'Profesional', 'text' => 'Keterampilan diasah agar siap kerja, bukan hanya siap ujian.'],
                ['title' => 'Kreatif & Inovatif', 'text' => 'Berpikir terbuka, berani mencoba, dan menghasilkan karya.'],
                ['title' => 'Gotong Royong', 'text' => 'Kolaborasi siswa, guru, orang tua, dan industri.'],
                ['title' => 'Peduli Lingkungan', 'text' => 'Kebiasaan hidup hijau ditanamkan dalam keseharian.'],
            ]),

            'school_facilities' => json_encode([
                ['name' => 'Bengkel Alat Berat', 'description' => 'Bengkel praktik teknik alat berat dengan peralatan standar industri dan area servis luas.', 'image' => 'seeds/workshop.jpg'],
                ['name' => 'Laboratorium Komputer', 'description' => 'Lab komputer untuk simulasi pengoperasian, pemrograman dasar, dan administrasi industri.', 'image' => 'seeds/computer-lab.jpg'],
                ['name' => 'Perpustakaan', 'description' => 'Perpustakaan digital dan konvensional dengan koleksi lebih dari 8.000 eksemplar.', 'image' => 'seeds/library.jpg'],
                ['name' => 'Lapangan Olahraga', 'description' => 'Lapangan futsal, basket, dan voli untuk kegiatan pembelajaran dan ekstrakurikuler.', 'image' => 'seeds/sport.jpg'],
                ['name' => 'Studio Seni & Audio', 'description' => 'Ruang praktik seni, musik, dan produksi konten digital.', 'image' => 'seeds/art.jpg'],
                ['name' => 'Mushola', 'description' => 'Sarana ibadah dengan kapasitas 300 jamaah dan ruang pembinaan kerohanian.', 'image' => 'seeds/campus.jpg'],
            ]),

            'school_programs' => json_encode([
                ['name' => 'Teknik Alat Berat', 'code' => 'TAB', 'description' => 'Program keahlian pengoperasian, perawatan, dan perbaikan alat berat yang bekerja sama dengan industri konstruksi, pertambangan, dan perkebunan.', 'image' => 'seeds/workshop.jpg'],
            ]),

            'school_academic_intro' => "Pembelajaran di SMK Taruna Sains Kediri mengikuti Kurikulum Merdeka dengan pendekatan teaching factory dan project based learning. Peserta didik tidak hanya belajar teori, tetapi mempraktikkan keterampilan pada proyek nyata yang relevan dengan bidang keahliannya.",
            'school_learning_methods' => json_encode([
                'Pembelajaran berbasis proyek (Project Based Learning)',
                'Teaching factory dengan praktik bengkel nyata',
                'Teaching industry bersama praktisi dari dunia usaha/industri',
                'Praktikum laboratorium dan bengkel terjadwal',
                'Kunjungan industri dan magang (praktik kerja lapangan)',
            ]),

            'admission_intro' => "Penerimaan Peserta Didik Baru (PPDB) SMK Taruna Sains Kediri Tahun Ajaran 2026/2027 dibuka melalui jalur zonasi, afirmasi, perpindahan tugas orang tua, dan prestasi. Pendaftaran dilakukan secara daring melalui situs resmi sekolah.",
            'admission_open' => '1',
            'admission_year' => '2026/2027',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}