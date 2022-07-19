<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $nip = "198609262015051001";
        $name = "Ida Faridatul Alawiyah, S.ST";
        $id_user = User::create([
            'name' => $name,
            'username' => $nip,
            'password' => bcrypt($nip),
            'status' => 'EMPLOYEE'
        ])->id;
        Employee::create([
            'user_id' => $id_user,
            'nip' => $nip,
            'name' => $name,
            'position' => 'Penyuluh Pertanian Pertama',
            'sex' => 0,
            'phone_number' => '0896630805',
            'academic_background' => 'Sarjana',
            'start_date' => '2020-03-11',
            'birth' => '1995-01-01',
            'address' => '',
            'file_image' => 'employee-image/ida.jpg',
            'file_ijazah' => '',
            'file_sk_pengangkatan' => '',
            'file_ktp' => '',
            'status' => 'INTERNAL'
        ]);

        $nip = "198609262015051002";
        $name = "Awanis, S.TP., M.Si";
        $id_user = User::create([
            'name' => $name,
            'username' => $nip,
            'password' => bcrypt($nip),
            'status' => 'ADMIN'
        ])->id;
        Employee::create([
            'user_id' => $id_user,
            'nip' => $nip,
            'name' => $name,
            'position' => 'Peneliti Pertama',
            'sex' => 0,
            'phone_number' => '08966308051',
            'academic_background' => 'Sarjana',
            'start_date' => '2015-11-21',
            'birth' => '1995-01-01',
            'address' => '',
            'file_image' => 'employee-image/awanis.jpg',
            'file_ijazah' => '',
            'file_sk_pengangkatan' => '',
            'file_ktp' => '',
            'status' => 'INTERNAL'
        ]);

        $nip = "198609262015051003";
        $name = "Muhammad Syarif, S.ST";
        $id_user = User::create([
            'name' => $name,
            'username' => $nip,
            'password' => bcrypt($nip),
            'status' => 'RECEPTIONIST'
        ])->id;
        Employee::create([
            'user_id' => $id_user,
            'nip' => $nip,
            'name' => $name,
            'position' => 'Peneliti Pertama',
            'sex' => 0,
            'phone_number' => '08966308052',
            'academic_background' => 'Sarjana',
            'start_date' => '2005-05-03',
            'birth' => '1995-01-01',
            'address' => '',
            'file_image' => 'employee-image/m syarif.jpg',
            'file_ijazah' => '',
            'file_sk_pengangkatan' => '',
            'file_ktp' => '',
            'status' => 'INTERNAL'
        ]);
    }
}
