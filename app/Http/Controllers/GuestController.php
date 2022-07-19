<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Guest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard.guests.index', [
            "guests" => Guest::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.guests.create', [
            'employees' => Employee::all(),
            'DAY_IN_INDONESIA' => ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jum'at", "Sabtu"]
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
            'nik' => 'required',
            'name' => 'required',
            'sex' => 'required',
            'phone_number' => 'required',
            'employee_id' => 'required',
            'agency' => 'required',
            'necessity' => 'required',
            'image' => 'required',
        ]);

        $validatedData['user_id'] = auth()->user()->id;

        Guest::create($validatedData);

        return redirect('/guests');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Guest  $guest
     * @return \Illuminate\Http\Response
     */
    public function show(Guest $guest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Guest  $guest
     * @return \Illuminate\Http\Response
     */
    public function edit(Guest $guest)
    {
        return view('dashboard.guests.edit', [
            "guest" => $guest,
            "employees" => Employee::all(),
            'DAY_IN_INDONESIA' => ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jum'at", "Sabtu"]
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Guest  $guest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Guest $guest)
    {
        $validatedData = $request->validate([
            'nik' => 'required',
            'name' => 'required',
            'sex' => 'required',
            'phone_number' => 'required',
            'employee_id' => 'required',
            'agency' => 'required',
            'necessity' => 'required',
            'image' => 'required',
        ]);

        $validatedData['user_id'] = auth()->user()->id;

        Guest::where('id', $guest->id)->update($validatedData);

        return redirect('/guests');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Guest  $guest
     * @return \Illuminate\Http\Response
     */
    public function destroy(Guest $guest)
    {
        Guest::destroy($guest->id);

        return redirect('/guests');
    }
}
