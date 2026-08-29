<?php

namespace Database\Seeders;

use App\Models\User;
// Tambahkan baris Hash ini agar fungsi Hash::make() bisa bekerja
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
        // 1. Akun Admin
        User::create([
            'name' => 'Administrator LMS',
            'email' => 'admin@unsur.ac.id',
            'password' => Hash::make('password123'),
            'nomor_induk' => 'ADM-001',
            'role' => 'admin',
            'status_akun' => 'aktif',
        ]);

        // 2. Akun Dosen
        User::create([
            'name' => 'Budi Dosen, S.T., M.Kom.',
            'email' => 'dosen@unsur.ac.id',
            'password' => Hash::make('password123'),
            'nomor_induk' => '0412345678',
            'role' => 'dosen',
            'status_akun' => 'aktif',
        ]);

        // 3. Akun Mahasiswa
        User::create([
            'name' => 'Mahasiswa Teknik',
            'email' => 'mahasiswa@unsur.ac.id',
            'password' => Hash::make('password123'),
            'nomor_induk' => '5520119001',
            'role' => 'mahasiswa',
            'status_akun' => 'aktif',
        ]);
    }
}
