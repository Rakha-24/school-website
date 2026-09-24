# SMK Taruna Sains Kediri — Website Sekolah

Sistem informasi sekolah untuk SMK Taruna Sains Kediri: situs profil sekolah untuk publik serta
portal mandiri untuk siswa, guru, dan administrator. Dibangun dengan **Laravel 12**, **Blade**,
**Tailwind CSS v4 (Vite)**, **Alpine.js**, dan **PostgreSQL**.

## Fitur

### Publik
- Beranda, profil sekolah (`/about`), informasi akademik (`/academic`), prestasi, kegiatan ekstrakurikuler, galeri, dan daftar berita.
- Halaman PPDB dengan info syarat, jadwal, dan FAQ.
- Formulir kontak (dengan throttle untuk mencegah spam).

### Portal Siswa (`/portal/student`)
- Dashboard ringkasan, jadwal pelajaran, materi yang sudah dipublikasikan guru, tugas, dan pengumpulan jawaban (teks atau berkas, sebelum tenggat).
- Nilai, rekapitulasi presensi, dan pengumuman.

### Portal Guru (`/portal/teacher`)
- Dashboard, jadwal mengajar, kelola materi & tugas (buat/edit/hapus milik sendiri).
- Penilaian pengumpulan tugas siswa dan pencatatan presensi kelas.

### Portal Admin (`/portal/admin`)
- Kelola: pengguna, guru, siswa, kelas, mata pelajaran, relasi kelas-mapel, jadwal, presensi, pengumuman, berita, prestasi, ekstrakurikuler, galeri, info PPDB, FAQ, pengaturan situs, dan pesan masuk.

## Stack

| Lapisan    | Teknologi                                  |
|------------|--------------------------------------------|
| Framework  | Laravel 12 (PHP 8.3+)                      |
| Database   | PostgreSQL 15+                             |
| Frontend   | Blade, Tailwind CSS v4 (Vite), Alpine.js   |
| Auth       | Breeze + otorisasi berbasis policy & gate  |
| Testing    | PHPUnit + RefreshDatabase                  |

## Persyaratan

- PHP 8.3+ dengan ekstensi `pgsql`
- Composer 2
- Node.js 20+ dan npm
- PostgreSQL 15+

## Instalasi

```bash
composer install
npm install
npm run build

cp .env.example .env
# atur koneksi database pada .env
php artisan key:generate

# siapkan basis data
createdb -h 127.0.0.1 -p 5433 -U school school
php artisan migrate --seed
php artisan storage:link
```

Konfigurasi `.env` minimum:

```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5433
DB_DATABASE=school
DB_USERNAME=school
DB_PASSWORD=
```

## Akun Demo (setelah `--seed`)

Semua akun menggunakan kata sandi `password123`.

| Peran      | Email                                        |
|------------|---------------------------------------------|
| Admin      | `admin@smktarunasainskediri.com`               |
| Guru       | `siti.rahayu@smktarunasainskediri.com`         |
| Siswa      | `arya.pratama@smktarunasainskediri.com`|

Setelah login, pengguna diarahkan ke dashboard perannya melalui `/portal`.

## Menjalankan Server

```bash
php artisan serve --port=8000
# publik : http://127.0.0.1:8000/
# portal : http://127.0.0.1:8000/portal
```

## Pengujian

Pengaruh ke basis data uji diatur di `phpunit.xml` (database `school_testing` pada host 5433).
Siapkan sekali:

```bash
psql -h 127.0.0.1 -p 5433 -U school -c "CREATE DATABASE school_testing;"
php artisan migrate:fresh --env=testing
```

Jalankan seluruh pengujian:

```bash
php artisan test          # atau vendor/bin/phpunit
```

Lint kode dengan Laravel Pint:

```bash
vendor/bin/pint --test
```

## Struktur Utama

```
app/
├── Http/Controllers/{Public,Student,Teacher,Admin}   # area sesuai peran
├── Http/Requests/…                                   # FormRequest + validasi
├── Models/                                           # Eloquent models
├── Notifications/                                    # notifikasi pengumpulan & penilaian
├── Policies/                                         # kebijakan kepemilikan & peran
├── Support/Access.php                                # helper pengecekan akses
└── View/Components/Layouts/                          # class component x-layouts.*
resources/views/
├── layouts/          # portal.blade.php, public.blade.php
├── public/           # halaman publik
├── admin/ ─ teacher/ ─ student/   # halaman portal per peran
└── components/       # icon, panel, empty-state, pagination, dll.
routes/web.php        # grup rute publik + portal per peran
```

## Catatan Keamanan

- Pemisahan akses berbasis peran lewat middleware `role:*` dan kebijakan (policy) Eloquent.
- Siswa hanya melihat materi/tugas kelasnya; guru hanya mengelola materi/tugas miliknya.
- Validasi seluruh input pada `FormRequest` di sisi server; tidak pernah mempercayai nilai dari client.
- Idempotensi dan pencegahan manipulasi diperhatikan pada spam kontak (throttle) dan operasi CRUD.

## Lisensi

Proyek ini dikembangkan untuk kebutuhan institusi pengelola proyek; silakan digunakan sesuai lisensi yang ditetapkan.