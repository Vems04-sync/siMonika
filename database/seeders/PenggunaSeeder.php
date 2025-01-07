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
                'password' => 'admin123'
            ],
            [
                'nama' => 'User Test',
                'email' => 'user@test.com',
                'password' => 'user123'
            ]
        ];

        foreach ($users as $user) {
            Pengguna::create($user);
        }
    }
} 