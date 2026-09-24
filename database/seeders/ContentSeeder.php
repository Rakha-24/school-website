<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\AdmissionInformation;
use App\Models\Extracurricular;
use App\Models\Faq;
use App\Models\GalleryItem;
use App\Models\News;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::role(User::ROLE_ADMIN)->first();
        $teacher = User::role(User::ROLE_TEACHER)->first();

        // ---- Berita ----
        $news = [
            [
                'title' => 'SMK Taruna Sains Kediri Gelar PPDB Tahun Ajaran 2026/2027',
                'category' => 'Informasi', 'image' => 'seeds/graduation.jpg', 'days' => -25,
                'author' => $admin,
                'excerpt' => 'Pendaftaran PPDB 2026/2027 resmi dibuka. Tersedia kuota untuk program keahlian Teknik Alat Berat melalui jalur zonasi, afirmasi, dan prestasi.',
                'content' => "SMK Taruna Sains Kediri secara resmi membuka Penerimaan Peserta Didik Baru (PPDB) untuk Tahun Ajaran 2026/2027. Pendaftaran dilakukan melalui jalur zonasi, afirmasi, perpindahan tugas orang tua, dan prestasi.\n\nKepala sekolah menyampaikan bahwa persaingan minat masuk ke sekolah ini meningkat setiap tahunnya. \"Kami berkomitmen menjaring calon peserta didik terbaik dan memberikan pembinaan hingga mereka lulus dengan kompetensi yang dibutuhkan dunia kerja,\" ujarnya.\n\nCalon peserta didik dapat mendaftar secara daring melalui laman resmi sekolah. Panitia juga menyediakan layanan bantuan pendaftaran bagi orang tua yang membutuhkan pendampingan teknis di ruang PPDB sekolah setiap hari kerja.",
            ],
            [
                'title' => 'Tim Siswa Raih Juara 2 pada Kompetisi Keterampilan Teknik Alat Berat 2026',
                'category' => 'Prestasi', 'image' => 'seeds/workshop.jpg', 'days' => -18,
                'author' => $admin,
                'excerpt' => 'Dua siswa program keahlian Teknik Alat Berat membanggakan sekolah setelah meraih juara 2 kategori troubleshooting pada kompetisi keterampilan tingkat nasional di Surabaya.',
                'content' => "Kabar membanggakan datang dari Ajang Kompetisi Keterampilan Teknik Alat Berat 2026 yang digelar di Surabaya. Dua siswa program keahlian Teknik Alat Berat berhasil meraih Juara 2 pada kategori troubleshooting hidraulik.\n\nPersiapan dilakukan selama kurang lebih tiga bulan, termasuk ketika akhir pekan dan libur semester. Tim dibimbing langsung oleh guru pembimbing program keahlian.\n\n\"Prestasi ini menunjukkan bahwa siswa kami mampu bersaing di tingkat nasional. Ke depan kami akan terus mengembangkan pembinaan agar hasilnya semakin baik,\" ungkap pembimbing. Prestasi ini sekaligus menjadi modal bagi sekolah untuk memperkuat kerja sama dengan industri.",
            ],
            [
                'title' => 'Kunjungan Industri Siswa ke Perusahaan Alat Berat di Surabaya',
                'category' => 'Kegiatan', 'image' => 'seeds/workshop.jpg', 'days' => -12,
                'author' => $teacher,
                'excerpt' => 'Siswa kelas XII melakukan kunjungan industri untuk mengenal langsung praktik perawatan dan pengoperasian alat berat pada perusahaan mitra.',
                'content' => "Sebanyak 24 siswa kelas XII program keahlian Teknik Alat Berat mengikuti kegiatan kunjungan industri ke sebuah perusahaan penyedia alat berat di Surabaya.\n\nSelama kunjungan, siswa diperkenalkan pada alur kerja bengkel rekonstruksi, sistem manajemen perawatan, hingga praktik pengoperasian excavator pada area pelatihan. Siswa juga berkesempatan bertanya langsung kepada teknisi senior dan membayangkan bagaimana kompetensi yang dipelajari diterapkan di lapangan.\n\nKegiatan ini merupakan bagian dari program teaching industry sekolah yang diadakan satu semester sekali untuk setiap program keahlian.",
            ],
            [
                'title' => 'Panen Karya Projek Penguatan Profil Pelajar Pancasila',
                'category' => 'Kegiatan', 'image' => 'seeds/classroom.jpg', 'days' => -9,
                'author' => $teacher,
                'excerpt' => 'Ratusan karya siswa kelas X dan XI ditampilkan dalam panen karya P5 bertema kearifan lokal dan gaya hidup berkelanjutan.',
                'content' => "SMK Taruna Sains Kediri menggelar panen karya Projek Penguatan Profil Pelajar Pancasila (P5). Gelaran ini menampilkan ratusan karya siswa kelas X dan XI dengan dua tema utama: kearifan lokal dan gaya hidup berkelanjutan.\n\nBerbagai karya dipamerkan mulai dari produk olahan pangan lokal, kerajinan dari limbah plastik, hingga prototipe alat penetas telur bertenaga surya. Pameran dibuka untuk orang tua dan diramaikan dengan pertunjukan seni dari ekstrakurikuler sekolah.\n\n\"Melalui P5, siswa belajar memecahkan masalah nyata di sekitar mereka sambil menguatkan karakter,\" tutur koordinator projek.",
            ],
            [
                'title' => 'Workshop Parenting: Mendampingi Anak Menghadapi Dunia Kerja',
                'category' => 'Informasi', 'image' => 'seeds/english.jpg', 'days' => -5,
                'author' => $admin,
                'excerpt' => 'Sekolah mengadakan workshop parenting untuk orang tua guna memperkuat sinergi antara keluarga dan sekolah.',
                'content' => "SMK Taruna Sains Kediri mengadakan workshop parenting bertajuk \"Mendampingi Anak Menghadapi Dunia Kerja\". Kegiatan ini dihadiri oleh lebih dari 200 orang tua siswa kelas X.\n\nNarasumber yang dihadirkan adalah praktisi pendidikan dan penggiat parenting. Materi yang dibahas antara lain komunikasi efektif orang tua dan remaja, mengenali minat dan bakat, serta strategi mendampingi anak masa transisi sekolah ke dunia kerja.\n\nSekolah berharap kegiatan ini dapat membangun sinergi yang lebih kuat antara keluarga dan sekolah dalam mendampingi tumbuh kembang peserta didik.",
            ],
            [
                'title' => 'Purnawiyata Angkatan XXVI: Melepas 240 Siswa ke Dunia Kerja dan Kampus',
                'category' => 'Event', 'image' => 'seeds/graduation.jpg', 'days' => -1,
                'author' => $admin,
                'excerpt' => 'Sebanyak 240 siswa kelas XII dilepas dalam acara purnawiyata yang dirangkaikan dengan acara pelepasan dan wisuda.',
                'content' => "Suasana haru menyelimuti SMK Taruna Sains Kediri saat 240 siswa kelas XII menjalani acara purnawiyata. Mereka resmi dilepas untuk melanjutkan perjalanan menuju dunia kerja, wirausaha, maupun perguruan tinggi.\n\nAcara berlangsung di gedung aula sekolah dan dihadiri orang tua, komite sekolah, serta perwakilan mitra industri. Sejumlah siswa menerima penghargaan atas prestasi akademik, keterampilan, dan kehadiran terbaik.\n\nKepala sekolah berpesan agar para lulusan terus menjaga nama baik almamater dan menjadi teladan di lingkungan masing-masing.",
            ],
        ];

        foreach ($news as $item) {
            News::create([
                'title' => $item['title'],
                'slug' => Str::slug($item['title']),
                'excerpt' => $item['excerpt'],
                'content' => $item['content'],
                'category' => $item['category'],
                'cover_image' => $item['image'],
                'author_id' => $item['author']->id,
                'published_at' => now()->addDays($item['days']),
                'status' => 'published',
            ]);
        }

        // ---- Prestasi ----
        $achievements = [
            ['title' => 'Juara 1 LKS Tingkat Provinsi Jawa Timur — Teknik Alat Berat', 'level' => 'province', 'year' => 2026, 'category' => 'Akademik', 'participant' => 'Arya Pratama', 'image' => 'seeds/workshop.jpg', 'days' => -10, 'desc' => 'Mewakili provinsi Jawa Timur pada bidang Teknik Alat Berat di Family Community (FC) LKS SMK.'],
            ['title' => 'Juara 2 Kompetisi Keterampilan Teknik Alat Berat Nasional — Kategori Hidraulik', 'level' => 'national', 'year' => 2026, 'category' => 'Teknologi', 'participant' => 'Oscar Maulana & Putri Ayunda', 'image' => 'seeds/workshop.jpg', 'days' => -15, 'desc' => 'Tim alat berat meraih juara 2 setelah melalui penyisihan di antara 40 tim dari seluruh Indonesia.'],
            ['title' => 'Juara 1 LKS Kabupaten Kediri — Perawatan Mesin Alat Berat', 'level' => 'city', 'year' => 2026, 'category' => 'Akademik', 'participant' => 'Nadia Salsabila', 'image' => 'seeds/science.jpg', 'days' => -8, 'desc' => 'Meraih skor tertinggi pada kompetisi perawatan mesin diesel alat berat tingkat kabupaten.'],
            ['title' => 'Juara 3 Turnamen Futsal Antarsekolah Se-Kabupaten Kediri', 'level' => 'city', 'year' => 2025, 'category' => 'Olahraga', 'participant' => 'Tim Futsal SMK Taruna Sains Kediri', 'image' => 'seeds/futsal.jpg', 'days' => -140, 'desc' => 'Tim futsal sekolah berhasil menembus babak semifinal dan meraih posisi ketiga.'],
            ['title' => 'Medali Emas OSN Matematika Tingkat Provinsi Jawa Timur', 'level' => 'province', 'year' => 2025, 'category' => 'Akademik', 'participant' => 'Raka Dwi Saputra', 'image' => 'seeds/teacher.jpg', 'days' => -200, 'desc' => 'Meraih medali emas pada Olimpiade Sains Nasional bidang Matematika tingkat provinsi.'],
            ['title' => 'Juara Harapan 1 Parade Paskibraka Tingkat Kota', 'level' => 'city', 'year' => 2025, 'category' => 'Organisasi', 'participant' => 'Regu Paskibra', 'image' => 'seeds/campus.jpg', 'days' => -120, 'desc' => 'Regu paskibra tampil memukau pada parade pengibaran bendera dan meraih juara harapan 1.'],
            ['title' => 'Finalis Kompetisi Operator Alat Berat Nasional 2025', 'level' => 'national', 'year' => 2025, 'category' => 'Teknologi', 'participant' => 'Tim Alat Berat', 'image' => 'seeds/workshop.jpg', 'days' => -260, 'desc' => 'Tim alat berat sekolah lolos hingga babak final kompetisi operator nasional.'],
            ['title' => 'Juara 1 KSN Informatika Tingkat Kabupaten Kediri', 'level' => 'city', 'year' => 2024, 'category' => 'Akademik', 'participant' => 'Kamal Arifin', 'image' => 'seeds/computer-lab.jpg', 'days' => -400, 'desc' => 'Meraih juara pertama Kompetisi Sains Nasional bidang Informatika tingkat kabupaten.'],
        ];

        foreach ($achievements as $item) {
            Achievement::create([
                'title' => $item['title'],
                'slug' => Str::slug($item['title']),
                'description' => $item['desc'],
                'level' => $item['level'],
                'year' => $item['year'],
                'category' => $item['category'],
                'participant' => $item['participant'],
                'image' => $item['image'],
                'published_at' => now()->addDays($item['days']),
                'status' => 'published',
            ]);
        }

        // ---- Ekstrakurikuler ----
        $extras = [
            ['name' => 'Futsal', 'slug' => 'futsal', 'photo' => 'seeds/futsal.jpg', 'schedule' => 'Selasa & Kamis, 15.30–17.30', 'leader' => 'Fitri Handayani, S.Pd.', 'desc' => 'Pembinaan olahraga futsal untuk mengembangkan fisik, kerja sama tim, serta sportivitas. Tim berlatih rutin dan mengikuti turnamen antarsekolah.'],
            ['name' => 'Robotik', 'slug' => 'robotik', 'photo' => 'seeds/robotics.jpg', 'schedule' => 'Sabtu, 08.00–11.00', 'leader' => 'Ahmad Fauzan, S.Kom.', 'desc' => 'Eksplorasi teknik perakitan dan pemrograman robot untuk kompetisi regional dan nasional.'],
            ['name' => 'Pramuka', 'slug' => 'pramuka', 'photo' => 'seeds/campus.jpg', 'schedule' => 'Jumat, 14.00–16.00', 'leader' => 'Agus Salim, S.Sn.', 'desc' => 'Pendidikan kepanduan yang menumbuhkan kemandirian, kedisiplinan, dan jiwa kepemimpinan serta kecintaan pada alam.'],
            ['name' => 'Paskibra', 'slug' => 'paskibraka', 'photo' => 'seeds/theater.jpg', 'schedule' => 'Kamis & Sabtu, 15.00–17.00', 'leader' => 'Nina Marlina, M.Pd.', 'desc' => 'Latihan kedisiplinan baris-berbaris dan pengibaran bendera untuk mendukung agenda upacara sekolah dan daerah.'],
            ['name' => 'Palang Merah Remaja', 'slug' => 'pmr', 'photo' => 'seeds/science.jpg', 'schedule' => 'Rabu, 15.00–17.00', 'leader' => 'Dewi Anggraini, S.Pd.', 'desc' => 'Pembinaan remaja dalam bidang kesehatan, pertolongan pertama, dan kemanusiaan.'],
            ['name' => 'English Club', 'slug' => 'english-club', 'photo' => 'seeds/english.jpg', 'schedule' => 'Selasa, 15.30–17.00', 'leader' => 'Maya Puspita, S.Pd.', 'desc' => 'Kegiatan percakapan, debat, dan public speaking bahasa Inggris untuk meningkatkan kompetensi komunikasi global.'],
            ['name' => 'Seni Tari & Musik', 'slug' => 'seni-tari-musik', 'photo' => 'seeds/theater.jpg', 'schedule' => 'Sabtu, 09.00–12.00', 'leader' => 'Agus Salim, S.Sn.', 'desc' => 'Pengembangan ekspresi seni melalui tari tradisional, musik, dan produksi konten seni digital.'],
            ['name' => 'Rohis', 'slug' => 'rohis', 'photo' => 'seeds/library.jpg', 'schedule' => 'Jumat, 11.30–13.00', 'leader' => 'Zainal Abidin, S.Ag.', 'desc' => 'Forum pembinaan kerohanian Islam melalui kajian, tadarus, dan kegiatan sosial keagamaan.'],
        ];

        foreach ($extras as $item) {
            Extracurricular::create([
                'name' => $item['name'],
                'slug' => $item['slug'],
                'description' => $item['desc'],
                'photo' => $item['photo'],
                'schedule' => $item['schedule'],
                'leader' => $item['leader'],
                'status' => 'active',
            ]);
        }

        // ---- Galeri ----
        $gallery = [
            ['title' => 'Suasana Pembelajaran di Laboratorium Komputer', 'category' => 'Kegiatan Akademik', 'image' => 'seeds/computer-lab.jpg', 'days' => -20, 'desc' => 'Siswa mempraktikkan simulasi pengoperasian alat berat di laboratorium komputer.'],
            ['title' => 'Praktik Bengkel Alat Berat Kelas X', 'category' => 'Kegiatan Akademik', 'image' => 'seeds/workshop.jpg', 'days' => -18, 'desc' => 'Praktik perawatan berkala mesin diesel di bengkel alat berat sekolah.'],
            ['title' => 'Aktivitas Belajar di Ruang Kelas', 'category' => 'Kegiatan Siswa', 'image' => 'seeds/classroom.jpg', 'days' => -14, 'desc' => 'Siswa berdiskusi kelompok dalam pembelajaran berbasis proyek.'],
            ['title' => 'Praktikum Fisika di Laboratorium', 'category' => 'Kegiatan Akademik', 'image' => 'seeds/science.jpg', 'days' => -12, 'desc' => 'Percobaan fisika sederhana oleh siswa kelas X.'],
            ['title' => 'Pertandingan Futsal Antarkelas', 'category' => 'Ekstrakurikuler', 'image' => 'seeds/futsal.jpg', 'days' => -10, 'desc' => 'Semarak pertandingan futsal dalam rangka dies natalis sekolah.'],
            ['title' => 'Latihan Robotik', 'category' => 'Ekstrakurikuler', 'image' => 'seeds/robotics.jpg', 'days' => -9, 'desc' => 'Persiapan tim robotik menjelang kompetisi tingkat nasional.'],
            ['title' => 'Perpustakaan Sekolah', 'category' => 'Fasilitas', 'image' => 'seeds/library.jpg', 'days' => -7, 'desc' => 'Perpustakaan digital dan konvensional untuk menunjang literasi.'],
            ['title' => 'Penampilan Seni Tari Siswa', 'category' => 'Event', 'image' => 'seeds/theater.jpg', 'days' => -6, 'desc' => 'Penampilan tari tradisional pada puncak panen karya P5.'],
            ['title' => 'Kampus SMK Taruna Sains Kediri', 'category' => 'Fasilitas', 'image' => 'seeds/campus.jpg', 'days' => -4, 'desc' => 'Tampak depan kampus SMK Taruna Sains Kediri.'],
            ['title' => 'Purnawiyata Angkatan XXVI', 'category' => 'Event', 'image' => 'seeds/graduation.jpg', 'days' => -2, 'desc' => 'Pelepasan siswa kelas XII angkatan XXVI.'],
        ];

        foreach ($gallery as $item) {
            GalleryItem::create([
                'title' => $item['title'],
                'category' => $item['category'],
                'image' => $item['image'],
                'description' => $item['desc'],
                'published_at' => now()->addDays($item['days']),
                'status' => 'published',
            ]);
        }

        // ---- Info PPDB ----
        $admission = [
            ['type' => 'info', 'title' => 'Selamat Datang di PPDB SMK Taruna Sains Kediri 2026/2027', 'order' => 1, 'body' => "Penerimaan Peserta Didik Baru SMK Taruna Sains Kediri Tahun Ajaran 2026/2027 dibuka melalui jalur zonasi, afirmasi, perpindahan tugas orang tua, dan prestasi.\n\nKuota penerimaan sebanyak 108 kursi untuk satu program keahlian unggulan: Teknik Alat Berat (TAB). Pendaftaran dilakukan secara daring melalui laman resmi sekolah dan tidak dipungut biaya apapun."],
            ['type' => 'requirement', 'title' => 'Persyaratan Peserta', 'order' => 2, 'body' => "1. Lulus atau akan lulus dari SMP/sederajat.\n2. Berusia maksimal 21 tahun per 1 Juli tahun pendaftaran.\n3. Memiliki akta kelahiran dan Nomor Induk Siswa Nasional (NISN).\n4. Berdomisili sesuai jalur pendaftaran (zonasi membawa kartu keluarga).\n5. Sehat jasmani dan rohani, tidak buta warna bagi pendaftar program Teknik Alat Berat.\n6. Mengisi formulir pendaftaran secara daring dengan data yang benar."],
            ['type' => 'stage', 'title' => 'Tahapan Pendaftaran', 'order' => 3, 'body' => "Tahap 1 — Pendaftaran daring: calon peserta mengisi formulir dan mengunggah dokumen.\nTahap 2 — Verifikasi berkas oleh panitia PPDB.\nTahap 3 — Seleksi berdasarkan jalur yang dipilih (zonasi/afirmasi/prestasi).\nTahap 4 — Pengumuman hasil seleksi melalui laman sekolah.\nTahap 5 — Daftar ulang calon peserta yang diterima."],
            ['type' => 'schedule', 'title' => 'Jadwal PPDB', 'order' => 4, 'body' => "Pembukaan pendaftaran : 1–15 Juni 2026\nVerifikasi berkas : 16–20 Juni 2026\nProses seleksi : 21–25 Juni 2026\nPengumuman hasil : 27 Juni 2026\nDaftar ulang : 29 Juni – 3 Juli 2026\nKegiatan MPLS : 13–15 Juli 2026"],
            ['type' => 'document', 'title' => 'Dokumen yang Diperlukan', 'order' => 5, 'body' => "1. Fotokopi akta kelahiran.\n2. Fotokopi kartu keluarga (KK).\n3. Fotokopi rapor SMP/sederajat.\n4. Surat keterangan lulus (SKL) atau ijazah.\n5. Pas foto 3x4 berwarna (2 lembar).\n6. Surat keterangan sehat dari puskesmas/rumah sakit (bagi yang menjalani tes kesehatan).\nSeluruh dokumen diunggah dalam bentuk scan PDF dengan ukuran maksimal 2 MB per berkas."],
        ];

        foreach ($admission as $item) {
            AdmissionInformation::create([
                'type' => $item['type'],
                'title' => $item['title'],
                'body' => $item['body'],
                'sort_order' => $item['order'],
                'status' => 'published',
            ]);
        }

        // ---- FAQ ----
        $faqs = [
            ['question' => 'Apakah PPDB SMK Taruna Sains Kediri dipungut biaya?', 'answer' => 'Tidak. Seluruh proses PPDB dilaksanakan tanpa biaya (gratis). Waspadai pihak yang mengatasnamakan sekolah meminta sejumlah uang untuk menjamin kelulusan.'],
            ['question' => 'Bagaimana jika pendaftar gagal mengunggah berkas?', 'answer' => 'Pendaftar dapat menghubungi panitia melalui nomor WhatsApp yang tertera pada halaman kontak atau datang langsung ke ruang PPDB sekolah pada jam kerja untuk mendapatkan pendampingan.'],
            ['question' => 'Apakah ada tes praktik atau wawancara?', 'answer' => 'Untuk jalur zonasi dan afirmasi tidak ada tes. Khusus jalur prestasi, pendaftar diminta mengunggah bukti prestasi yang kemudian diverifikasi oleh tim seleksi.'],
            ['question' => 'Bolehkah mengubah jalur pendaftaran setelah submit?', 'answer' => 'Perubahan jalur hanya dapat dilakukan sebelum masa pendaftaran ditutup dengan menghubungi panitia PPDB.'],
            ['question' => 'Apa saja program keahlian yang tersedia?', 'answer' => 'Tersedia satu program keahlian unggulan, yaitu Teknik Alat Berat (TAB).'],
            ['question' => 'Apakah tersedia beasiswa bagi siswa kurang mampu?', 'answer' => 'Ya. Siswa dari keluarga kurang mampu dapat mengajukan bantuan melalui program Kartu Indonesia Pintar (KIP), beasiswa unggulan, dan dana bantuan sebagaimana diatur oleh sekolah.'],
        ];

        foreach ($faqs as $index => $item) {
            Faq::create([
                'question' => $item['question'],
                'answer' => $item['answer'],
                'sort_order' => $index + 1,
                'status' => 'published',
            ]);
        }
    }
}