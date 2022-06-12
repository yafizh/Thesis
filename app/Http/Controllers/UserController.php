<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where('status', '!=', 'super-admin')->where('status', '!=', 'employee')->get();
        return view('dashboard.users.index', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employees = Employee::join('users', 'employees.id', 'users.employee_id')->where('users.status', '=', 'employee')->get();
        return view('dashboard.users.create', ['employees' => $employees]);
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
            'employee_id' => 'required',
            'status' => 'required',
        ]);

        User::where('employee_id', $request->get('employee_id'))->update(['status' => $request->get('status')]);

        return redirect('/users')->with('success', 'Data user berhasil ditambahkan!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        User::where('id', $user->id)->update(['status' => 'employee']);

        return redirect('/users')->with('success', 'Data user berhasil dihapus!');
    }
}
