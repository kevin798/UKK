<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('admin'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'petugas@petugas.com'],
            [
                'name' => 'Petugas',
                'password'=> bcrypt('petugas'),
                'role' => 'petugas',
            ]
        );
    }
}
