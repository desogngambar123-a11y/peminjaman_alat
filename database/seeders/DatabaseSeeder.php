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
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Admin MUSABA',
                'email' => 'admin@musaba.test',
                'password' => Hash::make('123'),
                'role' => 'Admin',
            ]
        );

        User::updateOrCreate(
            ['username' => 'siswa'],
            [
                'name' => 'Siswa MUSABA',
                'email' => 'siswa@musaba.test',
                'password' => Hash::make('123'),
                'role' => 'Siswa',
            ]
        );
    }
}
