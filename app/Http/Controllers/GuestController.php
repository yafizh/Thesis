<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Guest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GuestController extends Controller
{

    public function index()
    {
        $guests = Guest::latest()->get()->map(function ($guest) {
            $guest->visit_date = ($guest->created_at->day . " " . $guest->created_at->getTranslatedMonthName() . " " . $guest->created_at->year);
            return $guest;
        });
        return view('dashboard.guests.index', [
            "guests" => $guests
        ]);
    }

    public function create()
    {
        return view('dashboard.guests.create', [
            'employees' => Employee::all(),
            'DAY_IN_INDONESIA' => ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jum'at", "Sabtu"]
        ]);
    }

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

    public function show(Guest $guest)
    {
        return view('dashboard.guests.show', [
            "guest" => $guest,
            "employees" => Employee::all(),
            'DAY_IN_INDONESIA' => ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jum'at", "Sabtu"]
        ]);
    }

    public function edit(Guest $guest)
    {
        return view('dashboard.guests.edit', [
            "guest" => $guest,
            "employees" => Employee::all(),
            'DAY_IN_INDONESIA' => ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jum'at", "Sabtu"]
        ]);
    }

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

        Guest::where('id', $guest->id)->update($validatedData);

        return redirect('/guests');
    }

    public function destroy(Guest $guest)
    {
        Guest::destroy($guest->id);
        return redirect('/guests');
    }

    public function report(Request $request)
    {
        if ($request->get('submit') === 'submit' || $request->get('submit') === 'reset') {
            $guests = Guest::latest()->get()->map(function ($guest) {
                return (object)[
                    'nik' => $guest->nik,
                    'name' => $guest->name,
                    'visit_date' => ($guest->created_at->day . " " . $guest->created_at->getTranslatedMonthName() . " " . $guest->created_at->year),
                    'employee' => $guest->employee->name,
                    'necessity' => $guest->necessity
                ];
            });
            return view('dashboard.guests.report', [
                'guests' => $guests
            ]);
        } elseif ($request->get('submit') === 'filter' || $request->get('submit') === 'print') {
            if (!empty($request->get('from')) && !empty($request->get('to')))
                $guests = Guest::whereBetween('created_at', [$request->get('from'), $request->get('to')])->get()->map(function ($guest) {
                    return (object)[
                        'nik' => $guest->nik,
                        'name' => $guest->name,
                        'visit_date' => ($guest->created_at->day . " " . $guest->created_at->getTranslatedMonthName() . " " . $guest->created_at->year),
                        'employee' => $guest->employee->name,
                        'necessity' => $guest->necessity
                    ];
                });
            else
                $guests = Guest::latest()->get()->map(function ($guest) {
                    return (object)[
                        'nik' => $guest->nik,
                        'name' => $guest->name,
                        'visit_date' => ($guest->created_at->day . " " . $guest->created_at->getTranslatedMonthName() . " " . $guest->created_at->year),
                        'employee' => $guest->employee->name,
                        'necessity' => $guest->necessity
                    ];
                });

            if ($request->get('submit') === 'filter') {
                return view('dashboard.guests.report', [
                    'from' => $request->get('from') ?? '',
                    'to' => $request->get('to') ?? '',
                    'guests' => $guests
                ]);
            } elseif ($request->get('submit') === 'print') {
                return view('dashboard.guests.print', [
                    'from' => $request->get('from') ?? '',
                    'to' => $request->get('to') ?? '',
                    'guests' => $guests
                ]);
            }
        }
    }
}
