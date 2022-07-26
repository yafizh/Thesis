<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Research;
use App\Models\ResearchMember;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportResearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->status === "ADMIN" || auth()->user()->employee->status === "EXTERNAL") {
            $researches = Research::latest()->get();
            $reports = $researches->filter(function ($research) {
                return !is_null($research->report) && $research->report->status === "SUBMITTED";
            });
        } else {
            $researches = ResearchMember::where('employee_id', auth()->user()->employee->id)->groupBy('research_id')->get()->map(function ($research) {
                return $research->research;
            });
            $reports = $researches->filter(function ($research) {
                return !is_null($research->report) && ($research->report->status === "SUBMITTED" || $research->report->status === "REJECTED");
            });
        }

        $reports = $reports->map(function ($research) {
            $submitted_date = new Carbon($research->report->submitted_date);
            return (object)[
                "research_id" => $research->id,
                "head" => $research->members->filter(function ($member) {
                    return $member->status === "HEAD";
                })->first()->employee,
                "submitted_date" => ($submitted_date->day . " " . $submitted_date->locale('ID')->getTranslatedMonthName() . " " . $submitted_date->year),
                "status" => $research->report->status === "SUBMITTED" ? "Menunggu Peninjauan" : "Telah Ditinjau",
            ];
        });
        return view('dashboard.research.report.index', [
            'reports' => $reports
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.research.report.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return redirect('report-research');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Research  $research
     * @return \Illuminate\Http\Response
     */
    public function show(Research $report_research)
    {
        $submitted_date = new Carbon($report_research->proposal->submitted_date);

        return view('dashboard.research.report.show', [
            'report' =>  (object)[
                "research_id" => $report_research->id,
                "title" => $report_research->title,
                "head" => $report_research->members->filter(function ($member) {
                    return $member->status === "HEAD";
                })->first()->employee,
                "research_member" => ($report_research->members->filter(function ($member) {
                    return $member->status === "RESEARCHER";
                }))->map(function ($member) {
                    return $member->employee;
                }),
                "extensionists_member" => ($report_research->members->filter(function ($member) {
                    return $member->status === "EXTENSIONISTS";
                }))->map(function ($member) {
                    return $member->employee;
                }),
                "submitted_date" => ($submitted_date->day . " " . $submitted_date->locale('ID')->getTranslatedMonthName() . " " . $submitted_date->year),
                "file" => $report_research->report->file,
                "status" => $report_research->report->status,
                "comments" => $report_research->report->comments
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Research  $research
     * @return \Illuminate\Http\Response
     */
    public function edit(Research $report_research)
    {
        $file = false;
        if ($report_research->report) {
            $file = $report_research->report->file;
        }

        return view('dashboard.research.report.edit', [
            'report' =>  (object)[
                "research_id" => $report_research->id,
                "title" => $report_research->title,
                "head" => $report_research->members->filter(function ($member) {
                    return $member->status === "HEAD";
                })->first()->employee,
                "research_member" => ($report_research->members->filter(function ($member) {
                    return $member->status === "RESEARCHER";
                }))->map(function ($member) {
                    return $member->employee;
                }),
                "extensionists_member" => ($report_research->members->filter(function ($member) {
                    return $member->status === "EXTENSIONISTS";
                }))->map(function ($member) {
                    return $member->employee;
                }),
                "file" => $file
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Research  $research
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Research $report_research)
    {
        $validatedData = $request->validate([
            "report" => "required"
        ]);
        if ($report_research->report) {

            $reportData = [
                "submitted_date" => Carbon::now(),
                "status" => "SUBMITTED"
            ];

            if ($request->file('report')) {
                if ($report_research->report->file) Storage::delete($report_research->report->file);
                $reportData["file"] = $request->file('report')->store('report');
            }

            Report::where('id', $report_research->report_id)->update($reportData);
        } else {
            if ($request->file('report'))
                $validatedData["file"] = $request->file('report')->store('report');

            Research::where('id', $report_research->id)->update([
                "report_id" => Report::create([
                    "file" => $validatedData['file'],
                    "submitted_date" => Carbon::now(),
                    "status" => "SUBMITTED",
                    "comments" => NULL
                ])->id
            ]);
        }
        return redirect('report-research');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Research  $research
     * @return \Illuminate\Http\Response
     */
    public function destroy(Research $report_research)
    {
        Research::where('id', $report_research->id)->update(['report_id' => NULL]);
        if ($report_research->report->file) Storage::delete($report_research->report->file);
        Report::where('id', $report_research->report_id)->delete();
        return redirect('report-research');
    }

    public function approve(Request $request, Research $report_research)
    {
        $validatedData = $request->validate([
            'submit' => 'string',
            'comments' => 'string|required'
        ]);

        $approvStatus = "APPROVED";
        $rejectStatus = "REJECTED";
        if ($validatedData['submit'] === $approvStatus) $validatedData['status'] = $approvStatus;
        elseif ($validatedData['submit'] === $rejectStatus) $validatedData['status'] = $rejectStatus;
        else redirect()->back();

        $reportData = [
            "employee_id" => auth()->user()->employee->id,
            "approved_date" => Carbon::now(),
            "status" => $validatedData['status'],
            "comments" => $validatedData['comments']
        ];

        Report::where('id', $report_research->report_id)->update($reportData);
        return redirect('/report-research');
    }
}
