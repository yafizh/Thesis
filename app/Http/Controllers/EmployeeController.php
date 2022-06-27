<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard.employees.index', [
            'employees' => Employee::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.employees.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nip' => 'required',
            'name' => 'required',
            'position' => 'required',
            'phone_number' => 'required',
            'sex' => 'required',
            'academic_background' => 'required',
            'start_date' => 'required',
            'birth' => 'required',
            'address' => 'required',
            'status' => 'required',
            'file_image' => 'required',
            'file_sk_pengangkatan' => 'required',
            'file_ktp' => 'required',
            'file_ijazah' => 'required',
        ]);

        if ($request->file('file_image')) {
            $validatedData['file_image'] = $request->file('file_image')->store('employee-image');
        }

        if ($request->file('file_ijazah')) {
            $validatedData['file_ijazah'] = $request->file('file_ijazah')->store('employee-ijazah');
        }

        if ($request->file('file_sk_pengangkatan')) {
            $validatedData['file_sk_pengangkatan'] = $request->file('file_sk_pengangkatan')->store('employee-sk-pengangkatan');
        }

        if ($request->file('file_ktp')) {
            $validatedData['file_ktp'] = $request->file('file_ktp')->store('employee-ktp');
        }

        Employee::create($validatedData);

        return redirect('/employees');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $employee)
    {
        return view('dashboard.employees.edit', [
            'employee' => $employee
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $employee)
    {
        $validatedData = $request->validate([
            'nip' => 'required',
            'name' => 'required',
            'position' => 'required',
            'phone_number' => 'required',
            'sex' => 'required',
            'academic_background' => 'required',
            'start_date' => 'required',
            'birth' => 'required',
            'address' => 'required',
            'status' => 'required',
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

        Employee::where('id', $employee->id)
            ->update($validatedData);

        return redirect('/employees');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        if ($employee->file_image) Storage::delete($employee->file_image);
        if ($employee->file_ktp) Storage::delete($employee->file_ktp);
        if ($employee->file_sk_pengangkatan) Storage::delete($employee->file_sk_pengangkatan);
        if ($employee->file_ijazah) Storage::delete($employee->file_ijazah);
        Employee::destroy($employee->id);
        return redirect('/employees');
    }
}
