<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public const DEFAULT_PASSWORD = 'password123';

    public function run(): void
    {
        $password = self::DEFAULT_PASSWORD;

        // ---- Admin ----
        $admin = User::create([
            'name' => 'Budi Santoso',
            'email' => 'admin@smktarunasainskediri.com',
            'password' => $password,
            'role' => User::ROLE_ADMIN,
        ]);
        $admin->markEmailAsVerified();

        // ---- Guru ----
        $teachers = [
            ['name' => 'Siti Rahayu, S.Pd.', 'email' => 'siti.rahayu@smktarunasainskediri.com', 'teacher_number' => '197603122003012001', 'phone' => '0812-2001-1001'],
            ['name' => 'Ahmad Fauzan, S.Kom.', 'email' => 'ahmad.fauzan@smktarunasainskediri.com', 'teacher_number' => '198503162008011002', 'phone' => '0812-2001-1002'],
            ['name' => 'Dewi Anggraini, S.Pd.', 'email' => 'dewi.anggraini@smktarunasainskediri.com', 'teacher_number' => '198811122010122001', 'phone' => '0812-2001-1003'],
            ['name' => 'Rudi Hartono, S.T.', 'email' => 'rudi.hartono@smktarunasainskediri.com', 'teacher_number' => '198002052005011003', 'phone' => '0812-2001-1004'],
            ['name' => 'Maya Puspita, S.Pd.', 'email' => 'maya.puspita@smktarunasainskediri.com', 'teacher_number' => '199003102014082001', 'phone' => '0812-2001-1005'],
            ['name' => 'Zainal Abidin, S.Ag.', 'email' => 'zainal.abidin@smktarunasainskediri.com', 'teacher_number' => '197408051999031004', 'phone' => '0812-2001-1006'],
            ['name' => 'Fitri Handayani, S.Pd.', 'email' => 'fitri.handayani@smktarunasainskediri.com', 'teacher_number' => '199101212015042001', 'phone' => '0812-2001-1007'],
            ['name' => 'Hendra Kurniawan, S.Kom.', 'email' => 'hendra.kurniawan@smktarunasainskediri.com', 'teacher_number' => '198707182011011005', 'phone' => '0812-2001-1008'],
            ['name' => 'Nina Marlina, M.Pd.', 'email' => 'nina.marlina@smktarunasainskediri.com', 'teacher_number' => '198512202009022001', 'phone' => '0812-2001-1009'],
            ['name' => 'Agus Salim, S.Sn.', 'email' => 'agus.salim@smktarunasainskediri.com', 'teacher_number' => '199006252015031006', 'phone' => '0812-2001-1010'],
        ];

        foreach ($teachers as $data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $password,
                'role' => User::ROLE_TEACHER,
            ]);
            $user->markEmailAsVerified();

            Teacher::create([
                'user_id' => $user->id,
                'teacher_number' => $data['teacher_number'],
                'phone' => $data['phone'],
                'status' => 'active',
            ]);
        }

        // ---- Siswa ----
        $students = [
            ['name' => 'Arya Pratama', 'email' => 'arya.pratama@smktarunasainskediri.com', 'nis' => '2682203101', 'nisn' => '0123456701', 'enrollment_year' => 2026],
            ['name' => 'Bella Safitri', 'email' => 'bella.safitri@smktarunasainskediri.com', 'nis' => '2682203102', 'nisn' => '0123456702', 'enrollment_year' => 2026],
            ['name' => 'Chandra Wijaya', 'email' => 'chandra.wijaya@smktarunasainskediri.com', 'nis' => '2682203103', 'nisn' => '0123456703', 'enrollment_year' => 2026],
            ['name' => 'Dina Rahmawati', 'email' => 'dina.rahmawati@smktarunasainskediri.com', 'nis' => '2682203104', 'nisn' => '0123456704', 'enrollment_year' => 2026],
            ['name' => 'Erik Santoso', 'email' => 'erik.santoso@smktarunasainskediri.com', 'nis' => '2682203105', 'nisn' => '0123456705', 'enrollment_year' => 2026],
            ['name' => 'Fira Hapsari', 'email' => 'fira.hapsari@smktarunasainskediri.com', 'nis' => '2682203106', 'nisn' => '0123456706', 'enrollment_year' => 2026],
            ['name' => 'Galih Ramadhan', 'email' => 'galih.ramadhan@smktarunasainskediri.com', 'nis' => '2682203107', 'nisn' => '0123456707', 'enrollment_year' => 2026],
            ['name' => 'Hanum Sekar', 'email' => 'hanum.sekar@smktarunasainskediri.com', 'nis' => '2682203108', 'nisn' => '0123456708', 'enrollment_year' => 2026],
            ['name' => 'Iqbal Fadhilah', 'email' => 'iqbal.fadhilah@smktarunasainskediri.com', 'nis' => '2682203109', 'nisn' => '0123456709', 'enrollment_year' => 2026],
            ['name' => 'Jasmine Putri', 'email' => 'jasmine.putri@smktarunasainskediri.com', 'nis' => '2682203110', 'nisn' => '0123456710', 'enrollment_year' => 2026],
            ['name' => 'Kamal Arifin', 'email' => 'kamal.arifin@smktarunasainskediri.com', 'nis' => '2682203111', 'nisn' => '0123456711', 'enrollment_year' => 2026],
            ['name' => 'Laila Nurjanah', 'email' => 'laila.nurjanah@smktarunasainskediri.com', 'nis' => '2682203112', 'nisn' => '0123456712', 'enrollment_year' => 2026],
            ['name' => 'Miftahul Huda', 'email' => 'miftahul.huda@smktarunasainskediri.com', 'nis' => '2682203113', 'nisn' => '0123456713', 'enrollment_year' => 2025],
            ['name' => 'Nadia Salsabila', 'email' => 'nadia.salsabila@smktarunasainskediri.com', 'nis' => '2682203114', 'nisn' => '0123456714', 'enrollment_year' => 2025],
            ['name' => 'Oscar Maulana', 'email' => 'oscar.maulana@smktarunasainskediri.com', 'nis' => '2682203115', 'nisn' => '0123456715', 'enrollment_year' => 2025],
            ['name' => 'Putri Ayunda', 'email' => 'putri.ayunda@smktarunasainskediri.com', 'nis' => '2682203116', 'nisn' => '0123456716', 'enrollment_year' => 2025],
            ['name' => 'Raka Dwi Saputra', 'email' => 'raka.dwi@smktarunasainskediri.com', 'nis' => '2682203117', 'nisn' => '0123456717', 'enrollment_year' => 2024],
            ['name' => 'Salsa Amalia', 'email' => 'salsa.amalia@smktarunasainskediri.com', 'nis' => '2682203118', 'nisn' => '0123456718', 'enrollment_year' => 2024],
            ['name' => 'Taufik Hidayat', 'email' => 'taufik.hidayat@smktarunasainskediri.com', 'nis' => '2682203119', 'nisn' => '0123456719', 'enrollment_year' => 2024],
            ['name' => 'Umar Khadafi', 'email' => 'umar.khadafi@smktarunasainskediri.com', 'nis' => '2682203120', 'nisn' => '0123456720', 'enrollment_year' => 2024],
        ];

        foreach ($students as $data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $password,
                'role' => User::ROLE_STUDENT,
            ]);
            $user->markEmailAsVerified();

            Student::create([
                'user_id' => $user->id,
                'student_number' => $data['nis'],
                'national_student_number' => $data['nisn'],
                'enrollment_year' => $data['enrollment_year'],
                'status' => 'active',
            ]);
        }
    }
}