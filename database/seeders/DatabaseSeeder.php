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
        User::create([
            'employee_id' => null,
            'password' => bcrypt('admin'),
            'status' => 'super-admin',
        ]);

        $nip = '198609262015051001';
        $id = (Employee::create([
            'nip' => $nip,
            'name' => 'Ida Faridatul Alawiyah, S.ST',
            'position' => 'Penyuluh Pertanian Pertama',
            'sex' => '0',
            'phone_number' => '0863397364254',
            'address' => 'Jl Kemang Tmr IV/18, Dki Jakarta',
            'photo' => ''
        ]))->id;

        User::create([
            'employee_id' => $id,
            'password' => bcrypt($nip),
            'status' => 'employee',
        ]);

        $nip = '198609262015051002';
        $id = (Employee::create([
            'nip' => $nip,
            'name' => 'Awanis, S.TP., M.Si',
            'position' => 'Pascapanen',
            'sex' => '0',
            'phone_number' => '086595415871',
            'address' => 'Perum Taman Elang Blok: N No: 6 Tangerang, Banten',
            'photo' => ''
        ]))->id;

        User::create([
            'employee_id' => $id,
            'password' => bcrypt($nip),
            'status' => 'employee',
        ]);
    }
}
