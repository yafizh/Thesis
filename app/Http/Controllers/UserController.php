<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard.users.index', [
            'users' => User::where('status', '!=', 'EMPLOYEE')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.users.create', [
            'users' => User::where('status', 'EMPLOYEE')->get()
        ]);
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
            'username' => 'required',
            'password' => 'required',
            'status' => 'required',
        ]);

        $userUpdate = [
            'password' => bcrypt($validatedData['password']),
            'status' => $validatedData['status'],
        ];

        User::where('username', $validatedData['nip'])
            ->update($userUpdate);

        return redirect('/users');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('dashboard.users.edit', [
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'nip' => 'required',
            'name' => 'required',
            'username' => 'required',
            'password' => 'required',
            'status' => 'required',
        ]);

        $userUpdate = [
            'status' => $validatedData['status'],
        ];
        if (!Hash::check($validatedData['password'], $user->password))
            $userUpdate['password'] = bcrypt($validatedData['password']);

        User::where('id', $user->id)
            ->update($userUpdate);

        return redirect('/users');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        User::where('id', $user->id)
            ->update(['status' => 'EMPLOYEE']);
        return redirect('/users');
    }
}
