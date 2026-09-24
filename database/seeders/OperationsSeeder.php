<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Attendance;
use App\Models\Schedule;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class OperationsSeeder extends Seeder
{
    public function run(): void
    {
        $this->schedules();
        $this->attendance();
        $this->announcements();
    }

    protected function schedules(): void
    {
        $slots = [
            1 => ['07:00-08:40', '08:45-10:15', '10:30-12:00', '13:00-14:30'],
            2 => ['07:00-08:40', '08:45-10:15', '10:30-12:00', '13:00-14:30'],
            3 => ['07:00-08:40', '08:45-10:15', '10:30-12:00', '13:00-14:30'],
            4 => ['07:00-08:40', '08:45-10:15', '10:30-12:00', '13:00-14:30'],
            5 => ['07:00-08:40', '08:45-10:15', '09:50-11:20'],
        ];

        $roomFor = [
            'INF' => 'Lab Komputer 1', 'DAT' => 'Bengkel Alat Berat', 'PMB' => 'Bengkel Mesin',
            'PCS' => 'Bengkel Chassis', 'PKL' => 'Lab Kelistrikan', 'SHP' => 'Lab Hidraulik',
            'FIS' => 'Lab Fisika', 'MAT' => 'R. 10', 'PJOK' => 'Lapangan', 'SBK' => 'Studio Seni',
        ];

        foreach (SchoolClass::all() as $class) {
            $classSubjects = $class->classSubjects()->with('subject')->get();
            if ($classSubjects->isEmpty()) {
                continue;
            }

            $i = 0;
            foreach ($slots as $day => $times) {
                foreach ($times as $range) {
                    [$start, $end] = explode('-', $range);
                    $pair = $classSubjects[$i % $classSubjects->count()];
                    $i++;

                    $code = $pair->subject->code;

                    Schedule::create([
                        'class_id' => $class->id,
                        'subject_id' => $pair->subject_id,
                        'teacher_id' => $pair->teacher_id,
                        'day' => $day,
                        'start_time' => $start,
                        'end_time' => $end,
                        'room' => $roomFor[$code] ?? 'R-'.$class->grade,
                    ]);
                }
            }
        }
    }

    protected function attendance(): void
    {
        $start = now()->startOfDay()->subDays(20);
        $recorders = [];
        foreach (SchoolClass::all() as $class) {
            $recorders[$class->id] = $class->homeroom_teacher_id
                ? $class->homeroomTeacher->user_id
                : User::where('role', 'teacher')->firstOrFail()->id;
        }

        $dates = [];
        for ($d = $start; $d->lte(now()->startOfDay()); $d->addDay()) {
            if ($d->isWeekend()) {
                continue;
            }
            $dates[] = $d->copy();
        }

        foreach ($dates as $date) {
            foreach (SchoolClass::all() as $class) {
                foreach ($class->students as $student) {
                    Attendance::updateOrCreate(
                        ['student_id' => $student->id, 'class_id' => $class->id, 'date' => $date],
                        [
                            'status' => $this->statusFor($student, $date, $class->name),
                            'note' => null,
                            'recorded_by' => $recorders[$class->id],
                        ],
                    );
                }
            }
        }

        // Kasus khusus agar terlihat bervariasi
        $salsa = Student::whereHas('user', fn ($q) => $q->where('email', 'salsa.amalia@smktarunasainskediri.com'))->first();
        $fira = Student::whereHas('user', fn ($q) => $q->where('email', 'fira.hapsari@smktarunasainskediri.com'))->first();
        $iqbal = Student::whereHas('user', fn ($q) => $q->where('email', 'iqbal.fadhilah@smktarunasainskediri.com'))->first();

        foreach ($dates as $date) {
            if ($salsa && $date->isFriday()) {
                Attendance::updateOrCreate(
                    ['student_id' => $salsa->id, 'class_id' => $salsa->class_id, 'date' => $date],
                    ['status' => 'absent', 'note' => 'Tanpa keterangan', 'recorded_by' => $recorders[$salsa->class_id]],
                );
            }
            if ($fira && $date->equalTo($dates[2] ?? $date)) {
                Attendance::updateOrCreate(
                    ['student_id' => $fira->id, 'class_id' => $fira->class_id, 'date' => $date],
                    ['status' => 'sick', 'note' => 'Demam', 'recorded_by' => $recorders[$fira->class_id]],
                );
            }
            if ($iqbal && $date->isThursday() && $iqbal->class_id && $date->equalTo(end($dates))) {
                Attendance::updateOrCreate(
                    ['student_id' => $iqbal->id, 'class_id' => $iqbal->class_id, 'date' => $date],
                    ['status' => 'permission', 'note' => 'Acara keluarga', 'recorded_by' => $recorders[$iqbal->class_id]],
                );
            }
        }
    }

    protected function statusFor(Student $student, $date, string $className): string
    {
        $r = crc32($student->id.'-'.$date->format('Y-m-d').'-'.$className);

        return match (true) {
            $r % 100 < 3 => 'sick',
            $r % 100 < 5 => 'permission',
            $r % 100 < 7 => 'absent',
            default => 'present',
        };
    }

    protected function announcements(): void
    {
        $admin = User::role(User::ROLE_ADMIN)->first();
        $teacher = User::role(User::ROLE_TEACHER)->first();

        $data = [
            ['title' => 'Jadwal Kegiatan LDKS Kelas X Tahun Ajaran 2026/2027', 'audience' => 'students', 'days' => -12, 'status' => 'published', 'author' => $admin, 'content' => "Diberitahukan kepada seluruh siswa kelas X bahwa Latihan Dasar Kepemimpinan Siswa (LDKS) akan dilaksanakan pada:\n\nHari : Sabtu–Minggu, 19–20 September 2026\nTempat : Bumi Perkemahan Arjasa, Kediri\\nPukul : 06.30 WIB – selesai\n\nPeserta wajib membawa perlengkapan sesuai daftar yang dibagikan walikelas masing-masing. Kehadiran merupakan bagian dari penilaian sikap."],
            ['title' => 'Pengumuman PPDB Gelombang 2 Tahun Ajaran 2026/2027', 'audience' => 'public', 'days' => -6, 'status' => 'published', 'author' => $admin, 'content' => "PPDB SMK Taruna Sains Kediri gelombang 2 akan dibuka pada 5–19 Oktober 2026. Pendaftaran dilakukan secara daring melalui laman resmi sekolah. Pendaftar diharapkan menyiapkan dokumen: akta kelahiran, KK, rapor SMP, dan surat keterangan sehat."],
            ['title' => 'Libur Nasional & Cuti Bersama Peringatan Maulid Nabi Muhammad SAW', 'audience' => 'all', 'days' => -4, 'status' => 'published', 'author' => $admin, 'content' => "Sehubungan dengan peringatan Maulid Nabi Muhammad SAW pada Rabu, 2 September 2026, kegiatan pembelajaran diliburkan. Seluruh siswa diperkenankan mengikuti kegiatan keagamaan di lingkungan masing-masing.\n\nKegiatan pembelajaran kembali aktif pada Kamis, 3 September 2026."],
            ['title' => 'Undangan Rapat Dinas Guru dan Staf', 'audience' => 'teachers', 'days' => -3, 'status' => 'published', 'author' => $admin, 'content' => "Kepada seluruh guru dan tenaga kependidikan, diharapkan hadir dalam rapat dinas pada:\n\nHari/Tanggal : Jumat, 11 September 2026\nPukul : 14.00 WIB\nTempat : Aula sekolah\nAgenda : Pembahasan pelaksanaan Asesmen Sumatif Akhir Semester Ganjil.\n\nMohon datang tepat waktu."],
            ['title' => 'Gerakan Jumat Bersih & Sehat', 'audience' => 'all', 'days' => -2, 'status' => 'published', 'author' => $teacher, 'content' => "Seluruh siswa dan guru diharapkan mengikuti kegiatan Jumat Bersih pada setiap Jumat pukul 07.00–07.30 WIB sebelum kegiatan pembelajaran dimulai. Setiap kelas membersihkan ruang kelas dan area tanggung jawab masing-masing. Siswa yang bertugas memakai sandal/seragam olahraga untuk memudahkan kegiatan bersih-bersih."],
            ['title' => 'Informasi Peminjaman Seragam Praktik Baru', 'audience' => 'students', 'days' => 1, 'status' => 'draft', 'author' => $admin, 'content' => '(Draf) Peminjaman seragam praktik untuk kelas X program keahlian Teknik Alat Berat akan dibuka melalui bagian sarana prasarana. Persyaratan dan jadwal menyusul.'],        ];

        foreach ($data as $item) {
            Announcement::create([
                'title' => $item['title'],
                'content' => $item['content'],
                'audience' => $item['audience'],
                'published_at' => now()->addDays($item['days']),
                'status' => $item['status'],
                'author_id' => $item['author']->id,
            ]);
        }
    }
}