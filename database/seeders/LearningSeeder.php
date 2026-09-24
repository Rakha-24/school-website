<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\ClassSubject;
use App\Models\Material;
use App\Models\Student;
use App\Models\Submission;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class LearningSeeder extends Seeder
{
    public function run(): void
    {
        Storage::disk('public')->makeDirectory('seeds/files');
        Storage::disk('public')->makeDirectory('submissions');
        Storage::disk('public')->makeDirectory('materials');

        $cs = function (string $class, string $subjectCode): ClassSubject {
            return ClassSubject::whereHas('schoolClass', fn ($q) => $q->where('name', $class))
                ->whereHas('subject', fn ($q) => $q->where('code', $subjectCode))
                ->firstOrFail();
        };

        $this->materials($cs);

        // ---- Tugas ----
        $assign = function (ClassSubject $classSubject, string $title, string $desc, int $dueDaysAgo, bool $attachment = true): Assignment {
            $assignment = Assignment::create([
                'class_subject_id' => $classSubject->id,
                'teacher_id' => $classSubject->teacher_id,
                'title' => $title,
                'description' => $desc,
                'attachment' => $attachment ? $this->makePdf('Lampiran - '.$title) : null,
                'due_at' => now()->addDays($dueDaysAgo),
                'status' => 'published',
            ]);

            return $assignment;
        };

        $a1 = $assign($cs('X-TAB 1', 'DAT'), 'Tugas 1: Laporan Pengenalan Alat Berat', "Buat laporan pengenalan komponen utama alat berat berdasarkan praktik di bengkel.\n\nKetentuan:\n1. Jelaskan fungsi mesin, undercarriage, bucket, dan hydraulic system\n2. Sertakan sketsa atau foto alat yang diamati\n3. Simpan file dengan format Nama_Kelas_Laporan.pdf\n4. Kumpulkan sebelum deadline", -3);
        $a2 = $assign($cs('X-TAB 1', 'MAT'), 'Latihan Soal Trigonometri', 'Kerjakan 15 soal trigonometri dari modul halaman 42–48. Tuliskan caranya secara lengkap dan kumpulkan dalam PDF.', -5);
        $a3 = $assign($cs('X-TAB 1', 'FIS'), 'Laporan Praktik K3 Bengkel', "Buat laporan praktik keselamatan kerja (K3) yang telah dipraktikkan di bengkel alat berat. Laporan mencakup:\n\n- Tujuan praktikum\n- Alat dan bahan\n- Prosedur keselamatan\n- Hasil pengamatan\n- Kesimpulan", 7);
        $a4 = $assign($cs('XII-TAB 1', 'PCS'), 'Proyek: Perawatan Chassis Alat Berat', "Buat laporan praktik perawatan chassis dan sistem pemindah tenaga alat berat:\n\n1. Prosedur pemeriksaan axle dan differential\n2. Pengecekan kondisi bearing dan seal\n3. Prosedur penggantian oli transmisi\n4. Hasil dan rekomendasi perawatan\n\nSertakan dokumentasi praktik.", -2);
        $a5 = $assign($cs('XI-TAB 1', 'SHP'), 'Analisis Sistem Hidraulik Alat Berat', "Analisis hasil kerja sistem hidraulik pada excavator yang mencakup sirkuit pompa, control valve, silinder, dan motor hidraulik.\n\nSertakan skema alur kerja minyak hidraulik beserta penjelasan fungsi setiap komponen.", -10);
        $a6 = $assign($cs('X-TAB 1', 'BIG'), 'Job Interview Dialogue with a Partner', 'Buatlah percakapan wawancara kerja (job interview) dalam bahasa Inggris dengan pasangan. Rekam dalam bentuk teks dialog berdurasi minimal 10 pertanyaan. Kumpulkan dalam format dokumen atau PDF.', 12);
        $a7 = $assign($cs('XI-TAB 1', 'PMB'), 'Simulasi Trouble-Shooting Mesin Diesel', "Buat diagram alir (flowchart) prosedur troubleshooting mesin diesel yang tidak bisa start. Sertakan kemungkinan penyebab dan langkah pemeriksaan.\n\nUnggah file laporan dan diagram.", -1);
        $a8 = $assign($cs('XII-TAB 1', 'PKL'), 'Laporan Praktik Kelistrikan Alat Berat', 'Buat laporan praktik pengukuran kelistrikan pada sistem starter dan alternator alat berat menggunakan multimeter. Sertakan tabel hasil pengukuran dan analisis singkat.', -6);

        $this->submissions($a1, 'X-TAB 1', ['arya.pratama@smktarunasainskediri.com' => 85, 'bella.safitri@smktarunasainskediri.com' => 90, 'dina.rahmawati@smktarunasainskediri.com' => 78], 'chandra.wijaya@smktarunasainskediri.com');
        $this->submissions($a2, 'X-TAB 1', ['erik.santoso@smktarunasainskediri.com' => 80, 'fira.hapsari@smktarunasainskediri.com' => null, 'galih.ramadhan@smktarunasainskediri.com' => 72], 'hanum.sekar@smktarunasainskediri.com');
        $this->submissions($a4, 'XII-TAB 1', ['raka.dwi@smktarunasainskediri.com' => null], 'salsa.amalia@smktarunasainskediri.com', ['salsa.amalia@smktarunasainskediri.com']);
        $this->submissions($a5, 'XI-TAB 1', ['oscar.maulana@smktarunasainskediri.com' => 92, 'putri.ayunda@smktarunasainskediri.com' => 88]);
        $this->submissions($a7, 'XI-TAB 1', ['miftahul.huda@smktarunasainskediri.com' => 76], 'nadia.salsabila@smktarunasainskediri.com');
        $this->submissions($a8, 'XII-TAB 1', ['taufik.hidayat@smktarunasainskediri.com' => 84, 'umar.khadafi@smktarunasainskediri.com' => 91]);
    }

    protected function materials($cs): void
    {
        $materialData = [
            ['class' => 'X-TAB 1', 'code' => 'DAT', 'title' => 'Modul 1: Mengenal Komponen Alat Berat', 'desc' => 'Pengenalan jenis-jenis alat berat (excavator, bulldozer, wheel loader, dump truck) dan fungsi komponen utamanya.', 'file' => true, 'days' => -20],
            ['class' => 'X-TAB 1', 'code' => 'DAT', 'title' => 'Modul 2: Prinsip Kerja Hydraulic System', 'desc' => 'Konsep dasar sistem hidraulik: pompa, silinder, control valve, dan rangkaian kerja sederhana.', 'file' => true, 'days' => -12],
            ['class' => 'X-TAB 1', 'code' => 'DAT', 'title' => 'Modul 3: Keselamatan dan Kesehatan Kerja Bengkel', 'desc' => 'Prosedur K3 di bengkel alat berat, APD, dan penanganan bahaya kerja.', 'file' => false, 'days' => -2],
            ['class' => 'X-TAB 1', 'code' => 'INF', 'title' => 'Presentasi: Teknologi Fleet Management', 'desc' => 'Slide pengenalan sistem monitoring armada alat berat berbasis telematika.', 'file' => false, 'days' => -6],
            ['class' => 'X-TAB 1', 'code' => 'FIS', 'title' => 'Handout: Besaran dan Satuan dalam Teknik', 'desc' => 'Besaran pokok/turunan, konversi satuan, dan penerapannya pada perhitungan mesin dan tenaga.', 'file' => true, 'days' => -15],
            ['class' => 'X-TAB 1', 'code' => 'MAT', 'title' => 'Struktur Bangun dan Geometri Terapan', 'desc' => 'Perhitungan volume galian, kemiringan, dan penerapan geometri pada pekerjaan lapangan.', 'file' => false, 'days' => -4],
            ['class' => 'XI-TAB 1', 'code' => 'PMB', 'title' => 'Modul 1: Perawatan Berkala Mesin Diesel', 'desc' => 'Jadwal perawatan berkala mesin diesel, penggantian filter, dan pemeriksaan sistem bahan bakar.', 'file' => true, 'days' => -14],
            ['class' => 'XI-TAB 1', 'code' => 'SHP', 'title' => 'Modul 2: Sirkuit Hidraulik Lanjutan', 'desc' => 'Membaca skema sirkuit hidraulik, pressure relief valve, dan sequence valve.', 'file' => true, 'days' => -7],
            ['class' => 'XI-TAB 1', 'code' => 'PMB', 'title' => 'Troubleshooting Mesin Tidak Bisa Start', 'desc' => 'Langkah sistematis identifikasi kerusakan mesin diesel yang tidak dapat dihidupkan.', 'file' => false, 'days' => -3],
            ['class' => 'XII-TAB 1', 'code' => 'PCS', 'title' => 'Modul 1: Sistem Pemindah Tenaga Alat Berat', 'desc' => 'Komponen final drive, transmission, dan differential pada alat berat.', 'file' => true, 'days' => -18],
            ['class' => 'XII-TAB 1', 'code' => 'PKL', 'title' => 'Modul 2: Kelistrikan Mesin dan Body', 'desc' => 'Sistem starter, alternator, dan kelistrikan bodi pada alat berat.', 'file' => true, 'days' => -9],
            ['class' => 'XII-TAB 1', 'code' => 'MAT', 'title' => 'Statistika dan Analisis Data Teknik', 'desc' => 'Penyajian data hasil pengukuran bengkel dan analisis statistik sederhana.', 'file' => false, 'days' => -1],
        ];

        foreach ($materialData as $data) {
            $classSubject = ClassSubject::whereHas('schoolClass', fn ($q) => $q->where('name', $data['class']))
                ->whereHas('subject', fn ($q) => $q->where('code', $data['code']))
                ->firstOrFail();

            Material::create([
                'class_subject_id' => $classSubject->id,
                'teacher_id' => $classSubject->teacher_id,
                'title' => $data['title'],
                'description' => $data['desc'],
                'content' => "Materi ini membahas ".strtolower($data['title']).".\n\nSilakan pelajari dokumen yang dilampirkan lalu kerjakan latihan yang diberikan oleh guru di kelas. Diskusikan dengan teman sekelas apabila ada bagian yang belum dipahami, dan sampaikan pertanyaan kepada guru pengampu saat pelajaran berlangsung.",
                'file_path' => $data['file'] ? $this->makePdf($data['title']) : null,
                'published_at' => now()->addDays($data['days']),
                'status' => 'published',
            ]);
        }
    }

    /**
     * @param  array<string, int|null>  $submitted  email => score|null
     * @param  string  $lateEmail
     * @param  array<string>  $absentEmails
     */
    protected function submissions(Assignment $assignment, string $className, array $submitted, ?string $lateEmail = null, array $absentEmails = []): void
    {
        $students = Student::whereHas('schoolClass', fn ($q) => $q->where('name', $className))->get()
            ->keyBy(fn ($s) => $s->user->email);

        foreach ($submitted as $email => $score) {
            $student = $students->get($email);
            if (! $student) {
                continue;
            }

            $isLate = $email === $lateEmail;
            $earlier = now()->subHours(rand(2, 60));
            $beforeDue = $assignment->due_at->copy()->subHours(rand(1, 40));
            $submittedAt = $isLate
                ? $assignment->due_at->copy()->addHours(rand(3, 20))
                : ($earlier->lt($beforeDue) ? $earlier : $beforeDue);

            Submission::create([
                'assignment_id' => $assignment->id,
                'student_id' => $student->id,
                'answer_text' => "Berikut jawaban saya untuk tugas \"{$assignment->title}\".\n\nFile hasil pekerjaan telah saya lampirkan. Mohon dikoreksi, terima kasih.",
                'file_path' => $this->makePdf('Jawaban - '.$student->user->name),
                'submitted_at' => $submittedAt,
                'score' => $score,
                'feedback' => $score !== null ? $this->feedback($score) : null,
                'graded_at' => $score !== null ? now()->subDays(rand(0, 4)) : null,
            ]);
        }

        foreach ($absentEmails as $email) {
            $student = $students->get($email);
            if (! $student) {
                continue;
            }

            Submission::create([
                'assignment_id' => $assignment->id,
                'student_id' => $student->id,
                'submitted_at' => now()->subHours(rand(1, 10)),
                'file_path' => $this->makePdf('Jawaban - '.$student->user->name),
            ]);
        }
    }

    protected function feedback(int $score): string
    {
        return match (true) {
            $score >= 90 => 'Sangat baik! Pemahaman konsep dan kelengkapan jawaban sangat memuaskan. Pertahankan!',
            $score >= 80 => 'Bagus. Jawaban sudah cukup lengkap, perhatikan detail penulisan dan format laporan.',
            $score >= 70 => 'Cukup baik, namun masih ada bagian yang kurang lengkap. Silakan pelajari kembali modul dan diskusikan di kelas.',
            default => 'Perlu ditingkatkan. Lengkapi kembali bagian yang kurang dan konsultasikan dengan guru pengampu.',
        };
    }

    protected function makePdf(string $title): string
    {
        $safe = preg_replace('/[^A-Za-z0-9]+/', '-', strtolower(substr($title, 0, 40))).'-'.rand(1000, 9999);
        $path = "seeds/files/{$safe}.pdf";
        if (! Storage::disk('public')->exists($path)) {
            $content = $this->pdfBytes($title);
            Storage::disk('public')->put($path, $content);
        }

        return $path;
    }

    protected function pdfBytes(string $title): string
    {
        $text = preg_replace('/[^\x20-\x7E]/', ' ', $title);

        return "%PDF-1.4\n1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj\n2 0 obj<</Type/Pages/Kids[3 0 R]/Count 1>>endobj\n3 0 obj<</Type/Page/Parent 2 0 R/MediaBox[0 0 595 842]/Resources<</Font<</F1 4 0 R>>>>/Contents 5 0 R>>endobj\n4 0 obj<</Type/Font/Subtype/Type1/BaseFont/Helvetica>>endobj\n5 0 obj<</Length 120>>stream\nBT /F1 16 Tf 50 780 Td ({$text}) Tj ET\nendstream\nendobj\nxref\n0 6\ntrailer<</Size 6/Root 1 0 R>>\nstartxref\n0\n%%EOF\n";
    }
}