<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Ahmad Nuzulur Rozaq',
            'kode_guru' => 'G-3245',
            'tanggal_lahir' => '20060504',
            'password' => Hash::make('04052006'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Ahmad Nazilir Rizqi',
            'kode_guru' => 'G-5689',
            'tanggal_lahir' => '20130129',
            'password' => Hash::make('29012013'),
            'role' => 'guru',
        ]);

        User::create([
            'name' => 'Amira Nuzhatur Ramizah',
            'kode_guru' => 'G-0945',
            'tanggal_lahir' => '20210915',
            'password' => Hash::make('15092021'),
            'role' => 'wali_kelas',
        ]);


    }
}
