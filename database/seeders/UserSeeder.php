<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat atau memperbarui akun Super Admin
        User::updateOrCreate(
            ['email' => 'admin@rumahkaos.com'], // Kunci unik pencarian
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'), // Password otomatis di-hash aman
                'email_verified_at' => now(),
            ]
        );
    }
}