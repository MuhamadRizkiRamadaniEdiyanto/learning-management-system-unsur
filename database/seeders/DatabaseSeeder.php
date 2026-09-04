<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\Material;
use App\Models\Schedule;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $password = Hash::make('password123');

        User::updateOrCreate(
            ['email' => 'admin@unsur.ac.id'],
            [
                'name' => 'Administrator LMS',
                'password' => $password,
                'nomor_induk' => 'ADM-001',
                'role' => 'admin',
                'status_akun' => 'aktif',
            ]
        );

        $dosen = collect([
            ['name' => 'Budi Santoso, S.T., M.Kom.', 'email' => 'dosen@unsur.ac.id', 'nomor_induk' => '0412345678'],
            ['name' => 'Citra Lestari, S.T., M.T.', 'email' => 'citra.lestari@unsur.ac.id', 'nomor_induk' => '0412345679'],
            ['name' => 'Dedi Kurniawan, S.T., M.Kom.', 'email' => 'dedi.kurniawan@unsur.ac.id', 'nomor_induk' => '0412345680'],
        ])->map(fn(array $data) => User::updateOrCreate(
            ['email' => $data['email']],
            [...$data, 'password' => $password, 'role' => 'dosen', 'status_akun' => 'aktif']
        ));

        $mahasiswa = collect(range(1, 10))->map(function (int $number) use ($password) {
            $email = $number === 1 ? 'mahasiswa@unsur.ac.id' : sprintf('mahasiswa%02d@unsur.ac.id', $number);
            $nomorInduk = $number === 1 ? '5520119001' : '552021' . str_pad((string) $number, 4, '0', STR_PAD_LEFT);

            return User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => sprintf('Mahasiswa Teknik %02d', $number),
                    'password' => $password,
                    'nomor_induk' => $nomorInduk,
                    'role' => 'mahasiswa',
                    'status_akun' => 'aktif',
                ]
            );
        });

        $courseData = [
            ['kode_matkul' => 'TIF101', 'nama' => 'Algoritma dan Pemrograman', 'dosen' => 0, 'hari' => 'senin', 'mulai' => '07:30', 'selesai' => '10:00', 'ruangan' => 'Lab Komputer 1'],
            ['kode_matkul' => 'TIF102', 'nama' => 'Basis Data', 'dosen' => 0, 'hari' => 'selasa', 'mulai' => '10:15', 'selesai' => '12:45', 'ruangan' => 'Lab Komputer 2'],
            ['kode_matkul' => 'TIF201', 'nama' => 'Rekayasa Perangkat Lunak', 'dosen' => 1, 'hari' => 'rabu', 'mulai' => '07:30', 'selesai' => '10:00', 'ruangan' => 'Ruang 201'],
            ['kode_matkul' => 'TIF202', 'nama' => 'Jaringan Komputer', 'dosen' => 1, 'hari' => 'kamis', 'mulai' => '13:00', 'selesai' => '15:30', 'ruangan' => 'Lab Jaringan'],
            ['kode_matkul' => 'TIF301', 'nama' => 'Kecerdasan Buatan', 'dosen' => 2, 'hari' => 'jumat', 'mulai' => '07:30', 'selesai' => '10:00', 'ruangan' => 'Ruang 301'],
            ['kode_matkul' => 'TIF302', 'nama' => 'Keamanan Informasi', 'dosen' => 2, 'hari' => 'sabtu', 'mulai' => '08:00', 'selesai' => '10:30', 'ruangan' => 'Ruang 302'],
        ];

        foreach ($courseData as $data) {
            $course = Course::updateOrCreate(
                ['kode_matkul' => $data['kode_matkul']],
                ['nama' => $data['nama'], 'deskripsi' => 'Mata kuliah Fakultas Teknik untuk pengujian LMS.', 'dosen_id' => $dosen[$data['dosen']]->id]
            );

            Schedule::updateOrCreate(
                ['course_id' => $course->id, 'hari' => $data['hari'], 'jam_mulai' => $data['mulai'], 'ruangan' => $data['ruangan']],
                ['jam_selesai' => $data['selesai']]
            );

            Material::updateOrCreate(
                ['course_id' => $course->id, 'judul' => 'Modul ' . $data['nama']],
                ['deskripsi' => 'Materi pengantar untuk ' . $data['nama'], 'tipe_materi' => 'youtube', 'file_path' => 'materials/dummy.pdf', 'link_youtube' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ']
            );

            Assignment::updateOrCreate(
                ['course_id' => $course->id, 'judul' => 'Tugas 1 - ' . $data['nama']],
                ['deskripsi' => 'Kerjakan tugas pengantar sesuai instruksi dosen.', 'tenggat_waktu' => Carbon::now()->addDays(14)]
            );

            $course->mahasiswa()->sync($mahasiswa->filter(fn(User $student) => $student->id % 3 !== $course->id % 3)->pluck('id'));
        }
    }
}
