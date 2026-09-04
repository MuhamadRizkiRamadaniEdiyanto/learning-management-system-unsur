# Panduan Route Grouping LMS

## Boundary akses

Semua route privat harus diawali middleware `auth`. Tambahkan `role:<role>` untuk halaman yang hanya boleh dibuka satu jenis akun:

```php
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')->name('admin.')
    ->group(function () {
        // Dashboard, CRUD pengguna, mata kuliah, jadwal, dan laporan.
    });

Route::middleware(['auth', 'role:dosen'])
    ->prefix('dosen')->name('dosen.')
    ->group(function () {
        // Dashboard, materi, tugas, penilaian, dan pesan dosen.
    });

Route::middleware(['auth', 'role:mahasiswa'])
    ->prefix('mahasiswa')->name('mahasiswa.')
    ->group(function () {
        // Dashboard, mata kuliah, materi, tugas, pengumpulan, dan pesan mahasiswa.
    });
```

`RoleMiddleware` di `app/Http/Middleware/RoleMiddleware.php` menolak role yang tidak tercantum dengan HTTP 403. Alias `role` didaftarkan di `bootstrap/app.php`.

## Route lintas role

Route API/course yang dipakai oleh lebih dari satu role boleh diletakkan di group `auth`, tetapi controller wajib memanggil policy melalui `$this->authorize(...)`. Contoh yang sudah diterapkan:

- Dosen hanya melihat course yang diampu.
- Mahasiswa hanya melihat course yang diikuti.
- Materi dan tugas dibatasi oleh kepemilikan course atau enrollment.
- Pengumpulan hanya boleh dibuat/diubah oleh mahasiswa yang terdaftar dan sebelum deadline.

Endpoint perubahan tetap diberi group role khusus. Contohnya, pembuatan materi/tugas dan penilaian memakai `['auth', 'role:dosen']`, sedangkan pembuatan pengumpulan memakai `['auth', 'role:mahasiswa']`.

## Checklist route baru

1. Tentukan apakah route khusus `admin`, `dosen`, `mahasiswa`, atau lintas role.
2. Tambahkan `auth` dan `role` pada group yang sesuai.
3. Tambahkan policy untuk resource dan panggil `$this->authorize(...)` di controller.
4. Gunakan prefix URL dan name group agar route tidak saling bertabrakan.
5. Jalankan `php artisan route:list` dan uji akses dengan akun ketiga role.

Jangan mengandalkan prefix URL saja sebagai keamanan; prefix dapat ditebak dan middleware/policy adalah boundary sebenarnya.
