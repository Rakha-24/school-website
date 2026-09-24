<?php

namespace Database\Seeders;

use App\Models\ClassSubject;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;

class AcademicSeeder extends Seeder
{
    public function run(): void
    {
        $teacher = fn (string $email) => Teacher::whereHas('user', fn ($q) => $q->where('email', $email))->firstOrFail();

        // ---- Kelas ----
        $classData = [
            ['name' => 'X-TAB 1', 'grade' => 10, 'academic_year' => '2026/2027', 'homeroom_email' => 'fitri.handayani@smktarunasainskediri.com'],
            ['name' => 'XI-TAB 1', 'grade' => 11, 'academic_year' => '2026/2027', 'homeroom_email' => 'rudi.hartono@smktarunasainskediri.com'],
            ['name' => 'XII-TAB 1', 'grade' => 12, 'academic_year' => '2026/2027', 'homeroom_email' => 'siti.rahayu@smktarunasainskediri.com'],
        ];

        $classes = [];
        foreach ($classData as $data) {
            $classes[$data['name']] = SchoolClass::create([
                'name' => $data['name'],
                'grade' => $data['grade'],
                'academic_year' => $data['academic_year'],
                'homeroom_teacher_id' => $teacher($data['homeroom_email'])->id,
                'status' => 'active',
            ]);
        }

        // ---- Mata pelajaran ----
        $subjectData = [
            ['name' => 'Pendidikan Agama Islam & Budi Pekerti', 'code' => 'PAI'],
            ['name' => 'Pendidikan Pancasila dan Kewarganegaraan', 'code' => 'PPKn'],
            ['name' => 'Bahasa Indonesia', 'code' => 'BIN'],
            ['name' => 'Bahasa Inggris', 'code' => 'BIG'],
            ['name' => 'Matematika', 'code' => 'MAT'],
            ['name' => 'Fisika', 'code' => 'FIS'],
            ['name' => 'Informatika', 'code' => 'INF'],
            ['name' => 'Produk Kreatif & Kewirausahaan', 'code' => 'PKK'],
            ['name' => 'Pendidikan Jasmani, Olahraga & Kesehatan', 'code' => 'PJOK'],
            ['name' => 'Seni Budaya', 'code' => 'SBK'],
            ['name' => 'Dasar-Dasar Teknik Alat Berat', 'code' => 'DAT'],
            ['name' => 'Pemeliharaan Mesin Alat Berat', 'code' => 'PMB'],
            ['name' => 'Pemeliharaan Chassis & Sistem Pemindah Tenaga', 'code' => 'PCS'],
            ['name' => 'Pemeliharaan Kelistrikan Alat Berat', 'code' => 'PKL'],
            ['name' => 'Sistem Hidraulik & Pneumatik Alat Berat', 'code' => 'SHP'],
        ];

        $subjects = [];
        foreach ($subjectData as $data) {
            $subjects[$data['code']] = Subject::firstOrCreate(
                ['code' => $data['code']],
                ['name' => $data['name']],
            );
        }

        // ---- Relasi kelas-mapel-guru ----
        $map = [
            'X-TAB 1' => [
                ['PAI', 'zainal.abidin@smktarunasainskediri.com', 2],
                ['BIN', 'dewi.anggraini@smktarunasainskediri.com', 3],
                ['BIG', 'maya.puspita@smktarunasainskediri.com', 3],
                ['MAT', 'siti.rahayu@smktarunasainskediri.com', 4],
                ['FIS', 'rudi.hartono@smktarunasainskediri.com', 3],
                ['DAT', 'rudi.hartono@smktarunasainskediri.com', 6],
                ['INF', 'ahmad.fauzan@smktarunasainskediri.com', 2],
                ['PKK', 'rudi.hartono@smktarunasainskediri.com', 3],
                ['PJOK', 'fitri.handayani@smktarunasainskediri.com', 2],
            ],
            'XI-TAB 1' => [
                ['PAI', 'zainal.abidin@smktarunasainskediri.com', 2],
                ['BIN', 'dewi.anggraini@smktarunasainskediri.com', 3],
                ['BIG', 'maya.puspita@smktarunasainskediri.com', 3],
                ['MAT', 'siti.rahayu@smktarunasainskediri.com', 4],
                ['PMB', 'rudi.hartono@smktarunasainskediri.com', 6],
                ['SHP', 'rudi.hartono@smktarunasainskediri.com', 4],
                ['INF', 'ahmad.fauzan@smktarunasainskediri.com', 2],
                ['PKK', 'nina.marlina@smktarunasainskediri.com', 3],
                ['SBK', 'agus.salim@smktarunasainskediri.com', 2],
                ['PJOK', 'fitri.handayani@smktarunasainskediri.com', 2],
            ],
            'XII-TAB 1' => [
                ['PAI', 'zainal.abidin@smktarunasainskediri.com', 2],
                ['BIN', 'dewi.anggraini@smktarunasainskediri.com', 3],
                ['BIG', 'maya.puspita@smktarunasainskediri.com', 3],
                ['MAT', 'siti.rahayu@smktarunasainskediri.com', 4],
                ['PCS', 'rudi.hartono@smktarunasainskediri.com', 6],
                ['PKL', 'nina.marlina@smktarunasainskediri.com', 4],
                ['INF', 'ahmad.fauzan@smktarunasainskediri.com', 2],
                ['PKK', 'nina.marlina@smktarunasainskediri.com', 3],
                ['PJOK', 'fitri.handayani@smktarunasainskediri.com', 2],
            ],
        ];

        foreach ($map as $className => $assignments) {
            $class = $classes[$className];
            foreach ($assignments as [$code, $email, $hours]) {
                ClassSubject::firstOrCreate(
                    ['class_id' => $class->id, 'subject_id' => $subjects[$code]->id],
                    ['teacher_id' => $teacher($email)->id, 'hours_per_week' => $hours],
                );
            }
        }

        // ---- Menempatkan siswa pada kelas ----
        $roster = [
            'X-TAB 1' => ['arya.pratama@smktarunasainskediri.com', 'bella.safitri@smktarunasainskediri.com', 'chandra.wijaya@smktarunasainskediri.com', 'dina.rahmawati@smktarunasainskediri.com', 'erik.santoso@smktarunasainskediri.com', 'fira.hapsari@smktarunasainskediri.com', 'galih.ramadhan@smktarunasainskediri.com', 'hanum.sekar@smktarunasainskediri.com'],
            'XI-TAB 1' => ['iqbal.fadhilah@smktarunasainskediri.com', 'jasmine.putri@smktarunasainskediri.com', 'kamal.arifin@smktarunasainskediri.com', 'laila.nurjanah@smktarunasainskediri.com', 'miftahul.huda@smktarunasainskediri.com', 'nadia.salsabila@smktarunasainskediri.com'],
            'XII-TAB 1' => ['oscar.maulana@smktarunasainskediri.com', 'putri.ayunda@smktarunasainskediri.com', 'raka.dwi@smktarunasainskediri.com', 'salsa.amalia@smktarunasainskediri.com', 'taufik.hidayat@smktarunasainskediri.com', 'umar.khadafi@smktarunasainskediri.com'],
        ];

        foreach ($roster as $className => $emails) {
            foreach ($emails as $email) {
                $user = User::where('email', $email)->firstOrFail();
                $user->student()->updateOrCreate(['user_id' => $user->id], ['class_id' => $classes[$className]->id]);
            }
        }
    }
}