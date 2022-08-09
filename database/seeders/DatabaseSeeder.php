<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Guest;
use App\Models\User;
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

        $employees = [
            [
                "nip" => "199610102019022001",
                "name" => "Ida Faridatul Alawiyah, S.ST",
                'position' => 'Penyuluh Pertanian Pertama',
                'sex' => 0,
                'phone_number' => '027045026900',
                'academic_background' => 'Sarjana',
                'start_date' => '2020-03-11',
                'birth' => '1996-10-10',
                'address' => 'Psr. Agus Salim No. 620',
                'file_image' => 'employee-image/ida.jpg',
                'file_ijazah' => '',
                'file_sk_pengangkatan' => '',
                'file_ktp' => '',
                "employee_status" => "INTERNAL",
                "user_status" => "ADMIN",
            ],
            [
                "nip" => "198609262015051007",
                "name" => "Muhammad Ali",
                'position' => 'Penyuluh Pertanian Pertama',
                'sex' => 1,
                'phone_number' => '057812841606',
                'academic_background' => 'Sarjana',
                'start_date' => '2020-03-11',
                'birth' => '1986-09-26',
                'address' => 'Dk. Babadak No. 557',
                'file_image' => 'employee-image/m ali.jpg',
                'file_ijazah' => '',
                'file_sk_pengangkatan' => '',
                'file_ktp' => '',
                "employee_status" => "INTERNAL",
                "user_status" => "RECEPTIONIST",
            ],
            [
                "nip" => "198609262015051002",
                "name" => "Awanis, S.TP., M.Si",
                'position' => 'Penyuluh Pertanian Pertama',
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
                "employee_status" => "INTERNAL",
                "user_status" => "EMPLOYEE",
            ],
            [
                "nip" => "198609262015051003",
                "name" => "Muhammad Syarif, S.ST",
                'position' => 'Penyuluh Pertanian Pertama',
                'sex' => 1,
                'phone_number' => '0896630803',
                'academic_background' => 'Sarjana',
                'start_date' => '2020-03-11',
                'birth' => '1995-01-01',
                'address' => '',
                'file_image' => 'employee-image/m ali.jpg',
                'file_ijazah' => '',
                'file_sk_pengangkatan' => '',
                'file_ktp' => '',
                "employee_status" => "INTERNAL",
                "user_status" => "EMPLOYEE",
            ],
            [
                "nip" => "198609262015051331",
                "name" => "Puspita Harum Maharani, M.Sc",
                'position' => 'Peneliti Pertama',
                'sex' => 0,
                'phone_number' => '0818901382',
                'academic_background' => 'Sarjana',
                'start_date' => '2005-05-03',
                'birth' => '1995-01-01',
                'address' => '',
                'file_image' => 'employee-image/puspita.jpg',
                'file_ijazah' => '',
                'file_sk_pengangkatan' => '',
                'file_ktp' => '',
                'employee_status' => 'INTERNAL',
                "user_status" => "EMPLOYEE",
            ],
            [
                "nip" => "198609262115051331",
                "name" => "Ahmad Isa Anshari, SE",
                'position' => 'Penata Muda',
                'sex' => 1,
                'phone_number' => '',
                'academic_background' => 'Sarjana',
                'start_date' => '2005-05-03',
                'birth' => '1995-01-01',
                'address' => '',
                'file_image' => 'employee-image/puspita.jpg',
                'file_ijazah' => '',
                'file_sk_pengangkatan' => '',
                'file_ktp' => '',
                'employee_status' => 'EXTERNAL',
                "user_status" => "EMPLOYEE",
            ],
        ];

        foreach ($employees as $employee) {
            Employee::create([
                'user_id' => User::create([
                    'name' => $employee['name'],
                    'username' => $employee['nip'],
                    'password' => bcrypt($employee['nip']),
                    'status' => $employee['user_status']
                ])->id,
                'nip' => $employee['nip'],
                'name' => $employee['name'],
                'position' => $employee['position'],
                'sex' => $employee['sex'],
                'phone_number' => $employee['phone_number'],
                'academic_background' => $employee['academic_background'],
                'start_date' => $employee['start_date'],
                'birth' => $employee['birth'],
                'address' => $employee['address'],
                'file_image' => $employee['file_image'],
                'file_ijazah' => $employee['file_ijazah'],
                'file_sk_pengangkatan' => $employee['file_sk_pengangkatan'],
                'file_ktp' => $employee['file_ktp'],
                'status' => $employee['employee_status']
            ]);
        }


        // Guests
        Guest::create([
            "employee_id" => 4,
            "user_id" => 4,
            "nik" => '3525015201880002',
            "name" => 'Nursahid Arya Suyudi',
            "phone_number" => '087821235511',
            "sex" => 1,
            "agency" => 'UNISKA',
            "necessity" => 'Magang',
            "image" => '',
        ]);

        // Research
        // $proposal_id = Proposal::create([
        //     "employee_id" => 5,
        //     "file" => "",
        //     "submitted_date" => Carbon::now(),
        //     "status" => "APPROVED",
        // ])->id;
        // $report_id = Report::create([
        //     "employee_id" => 5,
        //     "file" => "",
        //     "submitted_date" => Carbon::now(),
        //     "approved_date" => Carbon::now(),
        //     "status" => "APPROVED",
        // ])->id;
        // $research_id = Research::create([
        //     "proposal_id" => $proposal_id,
        //     "report_id" => $report_id,
        //     "title" => "APLIKASI BUKU TAMU",
        // ])->id;
        // ResearchMember::create([
        //     "employee_id" => 1,
        //     "research_id" => $research_id,
        //     "status" => "HEAD"
        // ]);
        // ResearchMember::create([
        //     "employee_id" => 2,
        //     "research_id" => $research_id,
        //     "status" => "RESEARCHER"
        // ]);
    }
}
