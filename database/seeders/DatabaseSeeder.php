<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Jalankan CategorySeeder
        $this->call(CategorySeeder::class);

        // 2. Buat akun demo Admin
        User::firstOrCreate(
            ['email' => 'admin@tiketbantu.com'],
            [
                'name' => 'Admin TiketBantu',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // 3. Buat akun demo Agen per Kategori
        $agents = [
            [
                'name' => 'Agen Jaringan',
                'email' => 'agen.jaringan@tiketbantu.com',
            ],
            [
                'name' => 'Agen Hardware',
                'email' => 'agen.hardware@tiketbantu.com',
            ],
            [
                'name' => 'Agen Software',
                'email' => 'agen.software@tiketbantu.com',
            ],
            [
                'name' => 'Agen Fasilitas',
                'email' => 'agen.fasilitas@tiketbantu.com',
            ],
        ];

        foreach ($agents as $agent) {
            User::firstOrCreate(
                ['email' => $agent['email']],
                [
                    'name' => $agent['name'],
                    'password' => Hash::make('password'),
                    'role' => 'agent',
                    'email_verified_at' => now(),
                ]
            );
        }

        // 4. Buat akun demo User/Pelapor
        User::firstOrCreate(
            ['email' => 'user@tiketbantu.com'],
            [
                'name' => 'Pelapor Umum',
                'password' => Hash::make('password'),
                'role' => 'user',
                'email_verified_at' => now(),
            ]
        );
    }
}
