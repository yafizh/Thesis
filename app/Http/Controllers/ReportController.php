<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Research;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class ReportController extends Controller
{
    public function index()
    {
        return view('dashboard.report.main');
    }

    public function employee()
    {
        $employees = Employee::all();
        $employees->map(function ($employee) {
            $newEmployee = $employee;
            $start_date = new Carbon($newEmployee->start_date);
            $newEmployee->start_date = ($start_date->day . " " . $start_date->locale('ID')->getTranslatedMonthName() . " " . $start_date->year);
            return $newEmployee;
        });
        return view('dashboard.report.employee', [
            "employees" => $employees,
        ]);
    }

    public function employee_report(Request $request)
    {
        $validatedData = $request->validate([
            'from' => 'required',
            'to' => 'required',
        ]);
        $employees = Employee::whereBetween('start_date', [$validatedData['from'], $validatedData['to']])->where('status', 'LIKE', '%' . $request['status'] ?? "" . '%')->get();
        $employees->map(function ($employee) {
            $newEmployee = $employee;
            $start_date = new Carbon($newEmployee->start_date);
            $newEmployee->start_date = ($start_date->day . " " . $start_date->locale('ID')->getTranslatedMonthName() . " " . $start_date->year);
            $newEmployee['work_duration'] = $start_date->diffInDays(Carbon::now());
            return $newEmployee;
        });
        if ($request['submit'] === "filter") {
            return view('dashboard.report.employee', [
                "employees" => $employees,
                "from" => $validatedData['from'],
                "to" => $validatedData['to'],
                "status" => $validatedData['status'] ?? "",
                "filtered" => true,
            ]);
        } elseif ($request['submit'] === "print") {
            $from = new Carbon($validatedData['from']);
            $to = new Carbon($validatedData['to']);
            return view('dashboard.print.employee', [
                "employees" => $employees,
                "from" => ($from->day . " " . $from->locale('ID')->getTranslatedMonthName() . " " . $from->year),
                "to" => ($to->day . " " . $to->locale('ID')->getTranslatedMonthName() . " " . $to->year),
                "status" => $validatedData['status'] ?? NULL,
                "filtered" => true,
            ]);
        }
    }

    public function proposal_research()
    {
        $researches = Research::latest()->get();

        $proposals = $researches->map(function ($research) {
            $submitted_date = new Carbon($research->proposal->submitted_date);
            $approved_date = new Carbon($research->proposal->approved_date);

            $status = "";
            if ($research->proposal->status === "SUBMITTED")
                $status = "Pengajuan";
            elseif ($research->proposal->status === "APPROVED")
                $status = "Disetujui";
            elseif ($research->proposal->status === "REJECTED")
                $status = "Ditolak";

            return (object)[
                "title" => $research->title,
                "head" => $research->members->filter(function ($member) {
                    return $member->status === "HEAD";
                })->first()->employee,
                "submitted_date" => ($submitted_date->day . " " . $submitted_date->locale('ID')->getTranslatedMonthName() . " " . $submitted_date->year),
                "approved_duration" => $research->proposal->approved_date ? $approved_date->diffInDays(Carbon::now()) : "Masih Ditinjau",
                "status" => $status,
            ];
        });

        return view('dashboard.report.proposal_research', [
            "proposals" => $proposals,
        ]);
    }

    public function proposal_research_report(Request $request)
    {
        $validatedData = $request->validate([
            'from' => 'required',
            'to' => 'required',
        ]);

        $researches = Research::whereHas('proposal', function ($query) use ($validatedData, $request)
        {
            $query->whereBetween('submitted_date', [$validatedData['from'], $validatedData['to']])->where('status', 'LIKE', '%' . $request['status'] ?? "" . '%');
        })->get();

        $proposals = $researches->map(function ($research) {
            $submitted_date = new Carbon($research->proposal->submitted_date);
            $approved_date = new Carbon($research->proposal->approved_date);

            $status = "";
            if ($research->proposal->status === "SUBMITTED")
                $status = "Pengajuan";
            elseif ($research->proposal->status === "APPROVED")
                $status = "Disetujui";
            elseif ($research->proposal->status === "REJECTED")
                $status = "Ditolak";

            return (object)[
                "title" => $research->title,
                "head" => $research->members->filter(function ($member) {
                    return $member->status === "HEAD";
                })->first()->employee,
                "submitted_date" => ($submitted_date->day . " " . $submitted_date->locale('ID')->getTranslatedMonthName() . " " . $submitted_date->year),
                "approved_duration" => $research->proposal->approved_date ? $approved_date->diffInDays(Carbon::now()) : "Masih Ditinjau",
                "status" => $status,
            ];
        });

        if ($request['submit'] === "filter") {
            return view('dashboard.report.proposal_research', [
                "proposals" => $proposals,
                "from" => $validatedData['from'],
                "to" => $validatedData['to'],
                "status" => $validatedData['status'] ?? "",
                "filtered" => true,
            ]);
        } elseif ($request['submit'] === "print") {
            $from = new Carbon($validatedData['from']);
            $to = new Carbon($validatedData['to']);
            return view('dashboard.print.proposal_research', [
                "proposals" => $proposals,
                "from" => ($from->day . " " . $from->locale('ID')->getTranslatedMonthName() . " " . $from->year),
                "to" => ($to->day . " " . $to->locale('ID')->getTranslatedMonthName() . " " . $to->year),
                "status" => $validatedData['status'] ?? NULL,
                "filtered" => true,
            ]);
        }
    }
}
