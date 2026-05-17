<?php

namespace Database\Seeders;

use App\Models\PasswordAdmin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PasswordAdminSeeder extends Seeder
{
    /**
     * Seed tabel password_admin dengan satu password default.
     * Password default: admin123
     */
    public function run(): void
    {
        // Hapus semua record lama agar tidak duplikat
        PasswordAdmin::truncate();

        PasswordAdmin::create([
            'password' => Hash::make('admin123'),
        ]);

        $this->command->info('Password admin berhasil ditambahkan. Password default: admin123');
    }
}
