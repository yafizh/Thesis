<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::orderBy("name")->get()->map(function ($employee) {
            $start_date = new Carbon($employee->start_date);
            $employee->start_date = $start_date->day . " " . $start_date->getTranslatedMonthName() . " " . $start_date->year;
            return $employee;
        });
        // dd($employees);
        return view('dashboard.employees.index', [
            'page' => 'employees',
            'employees' => $employees
        ]);
    }

    public function create()
    {
        return view('dashboard.employees.create', [
            'page' => 'employees',
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nip' => 'required|size:18',
            'name' => 'required',
            'position' => 'required',
            'phone_number' => 'required|min:10|max:15|starts_with:+,0',
            'sex' => 'required',
            'academic_background' => 'required',
            'start_date' => 'required',
            'birth' => 'required',
            'address' => 'required',
            'status' => 'required',
            'file_image' => 'required|mimes:png,jpg|max:1024',
            'file_sk_pengangkatan' => 'required|mimes:pdf|max:2048',
            'file_ktp' => 'required|mimes:pdf|max:2048',
            'file_ijazah' => 'required|mimes:pdf|max:2048',
        ], [
            "nip.size" => "NIK Harus Memiliki 18 Angka",
            "phone_number.min" => "Nomor Telepon Tidak Dapat Kurang Dari 10 Angka",
            "phone_number.max" => "Nomor Telepon Tidak Dapat Lebih Dari 15 Angka",
            "phone_number.starts_with" => "Nomor Telepon Harus Memiliki Awal Kode Negara Atau Angka 0",
            "file_image.mimes" => "File Gambar harus bertipe .png atau .jpg",
            "file_image.max" => "File Gambar tidak boleh lebih dari 1MB",
            "file_sk_pengangkatan.mimes" => "File SK Pengangkatan harus bertipe .pdf",
            "file_sk_pengangkatan.max" => "File SK Pengangkatan tidak boleh lebih dari 2MB",
            "file_ktp.mimes" => "File KTP harus bertipe .pdf",
            "file_ktp.max" => "File KTP tidak boleh lebih dari 2MB",
            "file_ijazah.mimes" => "File Ijazah harus bertipe .pdf",
            "file_ijazah.max" => "File Ijazah tidak boleh lebih dari 2MB",
        ]);

        if ($request->file('file_image'))
            $validatedData['file_image'] = $request->file('file_image')->store('employee-image');

        if ($request->file('file_ijazah'))
            $validatedData['file_ijazah'] = $request->file('file_ijazah')->store('employee-ijazah');

        if ($request->file('file_sk_pengangkatan'))
            $validatedData['file_sk_pengangkatan'] = $request->file('file_sk_pengangkatan')->store('employee-sk-pengangkatan');

        if ($request->file('file_ktp'))
            $validatedData['file_ktp'] = $request->file('file_ktp')->store('employee-ktp');

        $user_id = User::create([
            "name" => $validatedData['name'],
            "username" => $validatedData['nip'],
            "password" => bcrypt($validatedData['nip']),
            "status" => "EMPLOYEE",
        ])->id;

        $validatedData['user_id'] = $user_id;
        $employee_id = Employee::create($validatedData)->id;

        return redirect('/employees')->with('created', $employee_id);
    }

    public function show(Employee $employee)
    {
        $birth = new Carbon($employee->birth);
        $start_date = new Carbon($employee->start_date);
        $employee->birth = ($birth->day . " " . $birth->getTranslatedMonthName() . " " . $birth->year);
        $employee->start_date = ($start_date->day . " " . $start_date->getTranslatedMonthName() . " " . $start_date->year);
        return view('dashboard.employees.show', [
            'page' => 'employees',
            'employee' => $employee
        ]);
    }

    public function edit(Employee $employee)
    {
        return view('dashboard.employees.edit', [
            'page' => 'employees',
            'employee' => $employee
        ]);
    }

    public function update(Request $request, Employee $employee)
    {
        $validatedData = $request->validate([
            'nip' => 'required|size:18',
            'name' => 'required',
            'position' => 'required',
            'phone_number' => 'required|min:10|max:15|starts_with:+,0',
            'sex' => 'required',
            'academic_background' => 'required',
            'start_date' => 'required',
            'birth' => 'required',
            'address' => 'required',
            'status' => 'required',
            'file_image' => 'mimes:png,jpg|max:1024',
            'file_sk_pengangkatan' => 'mimes:pdf|max:2048',
            'file_ktp' => 'mimes:pdf|max:2048',
            'file_ijazah' => 'mimes:pdf|max:2048',
        ], [
            "nip.size" => "NIK Harus Memiliki 18 Angka",
            "phone_number.min" => "Nomor Telepon Tidak Dapat Kurang Dari 10 Angka",
            "phone_number.max" => "Nomor Telepon Tidak Dapat Lebih Dari 15 Angka",
            "phone_number.starts_with" => "Nomor Telepon Harus Memiliki Awal Kode Negara Atau Angka 0",
            "file_image.mimes" => "File Gambar harus bertipe .png atau .jpg",
            "file_image.max" => "File Gambar tidak boleh lebih dari 1MB",
            "file_sk_pengangkatan.mimes" => "File SK Pengangkatan harus bertipe .pdf",
            "file_sk_pengangkatan.max" => "File SK Pengangkatan tidak boleh lebih dari 2MB",
            "file_ktp.mimes" => "File KTP harus bertipe .pdf",
            "file_ktp.max" => "File KTP tidak boleh lebih dari 2MB",
            "file_ijazah.mimes" => "File Ijazah harus bertipe .pdf",
            "file_ijazah.max" => "File Ijazah tidak boleh lebih dari 2MB",
        ]);

        if ($request->file('file_image')) {
            if ($employee->file_image) Storage::delete($employee->file_image);
            $validatedData['file_image'] = $request->file('file_image')->store('employee-image');
        }

        if ($request->file('file_ijazah')) {
            if ($employee->file_ijazah) Storage::delete($employee->file_ijazah);
            $validatedData['file_ijazah'] = $request->file('file_ijazah')->store('employee-ijazah');
        }

        if ($request->file('file_sk_pengangkatan')) {
            if ($employee->file_sk_pengangkatan) Storage::delete($employee->file_sk_pengangkatan);
            $validatedData['file_sk_pengangkatan'] = $request->file('file_sk_pengangkatan')->store('employee-sk-pengangkatan');
        }

        if ($request->file('file_ktp')) {
            if ($employee->file_ktp) Storage::delete($employee->file_ktp);
            $validatedData['file_ktp'] = $request->file('file_ktp')->store('employee-ktp');
        }

        User::where('id', $employee->user_id)->update([
            'name' => $validatedData['name'],
            'username' => $validatedData['nip']
        ]);
        Employee::where('id', $employee->id)
            ->update($validatedData);

        if (auth()->user()->status === "ADMIN")
            return redirect('/employees')->with('updated', $employee->id);
        else
            return redirect('/employees/' . $employee->id);
    }

    public function destroy(Employee $employee)
    {
        if ($employee->file_image) Storage::delete($employee->file_image);
        if ($employee->file_ktp) Storage::delete($employee->file_ktp);
        if ($employee->file_sk_pengangkatan) Storage::delete($employee->file_sk_pengangkatan);
        if ($employee->file_ijazah) Storage::delete($employee->file_ijazah);
        Employee::destroy($employee->id);
        User::where('id', $employee->user_id)->delete();
        return redirect('/employees')->with('deleted', $employee->name);
    }

    public function manage_password(Request $request, Employee $employee)
    {
        $validatedData = $request->validate([
            "manage" => "required",
        ]);

        if ($validatedData['manage'] === "CHANGE")
            return $this->change_password($request, $employee);

        if ($validatedData['manage'] === "RESET")
            return $this->reset_password($employee);

        return "Something Wrong";
    }

    public function reset_password(Employee $employee)
    {
        User::where('id', $employee->user->id)->update([
            "password" => bcrypt($employee->nip),
        ]);

        return back()->with('success', "Reset Password Berhasil! Password Baru = NIK");
    }
    public function change_password(Request $request, Employee $employee)
    {
        $validatedData = $request->validate([
            "old_password" => "required",
            "new_password" => "required",
            "confirm_new_password" => "required",
        ], [
            'old_password.required' => "Password Lama Perlu Diisi",
            'new_password.required' => "Password Baru Perlu Diisi",
            'confirm_new_password.required' => "Konfirmasi Password Baru Perlu Diisi",
        ]);
        if (!$this->isOldPassword($validatedData['old_password'], $employee->user->password))
            return back()->with('failed', "Password Lama Salah!");

        if (!$this->isPasswordSame($validatedData['new_password'], $validatedData['confirm_new_password']))
            return back()->with('failed', "Password Baru Tidak Sama!");

        User::where('id', $employee->user->id)->update([
            "password" => bcrypt($validatedData['new_password'])
        ]);
        return back()->with('success', "Password Berhasil Diperbaharui!");
    }

    public function isOldPassword($new_password, $old_password)
    {
        if (Hash::check($new_password, $old_password)) return true;
    }

    public function isPasswordSame($password1, $password2)
    {
        if ($password1 == $password2) return true;
    }

    public function report(Request $request)
    {
        if ($request->get('submit') === 'submit' || $request->get('submit') === 'reset') {
            $employees = Employee::orderBy('name')->get()->map(function ($employee) {
                $start_date = new Carbon($employee->start_date);

                $employee->start_date = ($start_date->day . " " . $start_date->getTranslatedMonthName() . " " . $start_date->year);

                $interval = "";
                if ($start_date->diff(Carbon::now())->format('%y'))
                    $interval .= $start_date->diff(Carbon::now())->format('%y') . " Tahun";
                if ($start_date->diff(Carbon::now())->format('%m'))
                    $interval .= " " . $start_date->diff(Carbon::now())->format('%m') . " Bulan";
                if ($start_date->diff(Carbon::now())->format('%d'))
                    $interval .= " " . $start_date->diff(Carbon::now())->format('%d') . " Hari";
                if (!$interval) $interval = "Baru Hari ini";

                $employee->work_duration = $interval;
                return $employee;
            });
            return view('dashboard.employees.report', [
                'page' => 'employee_report',
                'employees' => $employees
            ]);
        } elseif ($request->get('submit') === 'filter' || $request->get('submit') === 'print') {
            if (!empty($request->get('from')) && !empty($request->get('to')))
                $employees = Employee::whereBetween('start_date', [$request->get('from'), $request->get('to')])->where('status', 'LIKE', '%' . $request->get('status') . '%')->get()->map(function ($employee) {
                    $start_date = new Carbon($employee->start_date);

                    $employee->start_date = ($start_date->day . " " . $start_date->getTranslatedMonthName() . " " . $start_date->year);
                    $employee->work_duration = $start_date->diffInDays(Carbon::now()) . " Hari";
                    return $employee;
                });
            else
                $employees = Employee::where('status', 'LIKE', '%' . $request->get('status') . '%')->orderBy('name')->get()->map(function ($employee) {
                    $start_date = new Carbon($employee->start_date);

                    $employee->start_date = ($start_date->day . " " . $start_date->getTranslatedMonthName() . " " . $start_date->year);
                    $employee->work_duration = $start_date->diffInDays(Carbon::now()) . " Hari";
                    return $employee;
                });

            if ($request->get('submit') === 'filter') {
                return view('dashboard.employees.report', [
                    'page' => 'employee_report',
                    'from' => $request->get('from') ?? '',
                    'to' => $request->get('to') ?? '',
                    'status' => $request->get('status'),
                    'employees' => $employees
                ]);
            } elseif ($request->get('submit') === 'print') {
                return view('dashboard.employees.print', [
                    'from' => $request->get('from') ?? '',
                    'to' => $request->get('to') ?? '',
                    'status' => $request->get('status'),
                    'employees' => $employees
                ]);
            }
        }
    }
}
