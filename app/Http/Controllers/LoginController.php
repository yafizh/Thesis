<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function index()
    {
        return view('login.index');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'username' => ['required'],
            'password' => ['required']
        ]);

        if ($request->get('username') === 'admin') {
            $user = User::where('status', 'super-admin')->first();
            if (Hash::check($request->get('password'), $user->password)) {
                Session::put('user', $user);
                return redirect()->to('/');
            }
        } else {
            $employee = Employee::where('nip', $request->get('username'))->first();
            $user = User::where('id', $employee->user->id)->first();
            if (Hash::check($request->get('password'), $user->password)) {
                Session::put('user', $user);
                return redirect()->to('/');
            }
        }

        return back()->with('loginError', 'Login failed!');
    }

    public function logout(Request $request)
    {
        return redirect('/');
    }
}
