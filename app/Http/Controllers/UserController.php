<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return view('dashboard.users.index', [
            'users' => User::where('status', '!=', 'EMPLOYEE')->get(),
        ]);
    }

    public function create()
    {
        return view('dashboard.users.create', [
            'users' => User::where('status', 'EMPLOYEE')->get()
        ]);
    }

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

        return redirect('/users')->with('created', $validatedData['name']);
    }

    public function edit(User $user)
    {
        return view('dashboard.users.edit', [
            'user' => $user
        ]);
    }

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

        return redirect('/users')->with('updated', $validatedData['name']);
    }

    public function destroy(User $user)
    {
        User::where('id', $user->id)
            ->update(['status' => 'EMPLOYEE']);
        return redirect('/users')->with('deleted', $user->name);
    }
}
