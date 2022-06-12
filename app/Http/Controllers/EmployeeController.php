<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
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
        return view(
            'dashboard.employees.index',
            ['employees' => Employee::all()]
        );
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
        $validated = $request->validate([
            'nip' => 'required|unique:employees|max:18',
            'name' => 'required|max:255',
            'position' => 'required|max:255',
            'sex' => 'required',
            'phone_number' => 'required|unique:employees|max:15',
            'address' => 'required',
        ]);

        if ($request->file('photo'))
            $validated['photo'] = $request->file('photo')->store('employee-photo');

        $id = (Employee::create($validated))->id;
        User::create(['employee_id' => $id, 'password' => bcrypt($request->get('nip'))]);

        return redirect('/employees')->with('success', 'Data karyawan berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        return view('dashboard.employees.show', ['employee' => $employee]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $employee)
    {
        return view('dashboard.employees.edit', ['employee' => $employee]);
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
        $rules = [
            'name' => 'required|max:255',
            'position' => 'required|max:255',
            'sex' => 'required',
            'address' => 'required',
        ];

        if ($employee->nip !== $request->get('nip'))
            $rules['nip'] = 'required|unique:employees|max:18';
        if ($employee->phone_number !== $request->get('phone_number'))
            $rules['phone_number'] = 'required|unique:employees|max:15';

        $validated = $request->validate($rules);

        if ($request->file('photo'))
            $validated['photo'] = $request->file('photo')->store('employee-photo');
        else
            $validated['photo'] = $employee->photo;

        Employee::where('id', $employee->id)->update($validated);

        return redirect('/employees')->with('success', 'Data karyawan berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        if ($employee->photo) Storage::delete($employee->photo);
        User::where('employee_id', $employee->id)->delete();
        Employee::destroy($employee->id);
        return redirect('/employees')->with('success', 'Data karyawan berhasil dihapus!');
    }
}
