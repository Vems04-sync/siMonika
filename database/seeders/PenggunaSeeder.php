<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Hash;

class PenggunaSeeder extends Seeder
{
    public function run()
    {
        // Membuat beberapa user untuk testing
        $users = [
            [
                'nama' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin'
            ]
        ];

        foreach ($users as $user) {
            Pengguna::create($user);
        }
    }
} 