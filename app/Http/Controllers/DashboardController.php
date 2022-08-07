<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Proposal;
use App\Models\Report;
use App\Models\Research;
use App\Models\Study;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $anggaran = auth()->user()->employee->researches->filter(function ($research)
        {
            return !is_null($research->research->status_budget);
        });

        return view('dashboard.index', [
            "page" => "dashboard",
            "anggaran" => $anggaran,
            "employee" => Employee::all()->count(),
            "submitted_proposal" => Proposal::where('status', 'SUBMITTED')->get()->count(),
            "activity" => Research::all()->count() + Study::all()->count(),
            "submitted_report" => Report::where('status', 'SUBMITTED')->get()->count()
        ]);
    }
}
