<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Study;
use App\Models\StudyMember;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportStudyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->status === "ADMIN" || auth()->user()->employee->status === "EXTERNAL") {
            $studies = Study::latest()->get();
            $reports = $studies->filter(function ($study) {
                return !is_null($study->report) && $study->report->status === "SUBMITTED";
            });
        } else {
            $studies = StudyMember::where('employee_id', auth()->user()->employee->id)->groupBy('study_id')->get()->map(function ($study) {
                return $study->study;
            });
            $reports = $studies->filter(function ($study) {
                return !is_null($study->report) && ($study->report->status === "SUBMITTED" || $study->report->status === "REJECTED");
            });
        }

        $reports = $reports->map(function ($study) {
            $submitted_date = new Carbon($study->report->submitted_date);
            return (object)[
                "study_id" => $study->id,
                "head" => $study->members->filter(function ($member) {
                    return $member->status === "HEAD";
                })->first()->employee,
                "submitted_date" => ($submitted_date->day . " " . $submitted_date->locale('ID')->getTranslatedMonthName() . " " . $submitted_date->year),
                "status" => $study->report->status === "SUBMITTED" ? "Menunggu Peninjauan" : "Telah Ditinjau",
            ];
        });
        return view('dashboard.study.report.index', [
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
        return view('dashboard.study.report.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return redirect('report-study');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Study  $study
     * @return \Illuminate\Http\Response
     */
    public function show(Study $report_study)
    {
        $submitted_date = new Carbon($report_study->proposal->submitted_date);

        return view('dashboard.study.report.show', [
            'report' =>  (object)[
                "study_id" => $report_study->id,
                "title" => $report_study->research->title,
                "head" => $report_study->members->filter(function ($member) {
                    return $member->status === "HEAD";
                })->first()->employee,
                "study_member" => ($report_study->members->filter(function ($member) {
                    return $member->status === "RESEARCHER";
                }))->map(function ($member) {
                    return $member->employee;
                }),
                "extensionists_member" => ($report_study->members->filter(function ($member) {
                    return $member->status === "EXTENSIONISTS";
                }))->map(function ($member) {
                    return $member->employee;
                }),
                "submitted_date" => ($submitted_date->day . " " . $submitted_date->locale('ID')->getTranslatedMonthName() . " " . $submitted_date->year),
                "file" => $report_study->report->file,
                "status" => $report_study->report->status,
                "comments" => $report_study->report->comments
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Study  $study
     * @return \Illuminate\Http\Response
     */
    public function edit(Study $report_study)
    {
        $file = false;
        if ($report_study->report) {
            $file = $report_study->report->file;
        }

        return view('dashboard.study.report.edit', [
            'report' =>  (object)[
                "study_id" => $report_study->id,
                "title" => $report_study->research->title,
                "head" => $report_study->members->filter(function ($member) {
                    return $member->status === "HEAD";
                })->first()->employee,
                "study_member" => ($report_study->members->filter(function ($member) {
                    return $member->status === "RESEARCHER";
                }))->map(function ($member) {
                    return $member->employee;
                }),
                "extensionists_member" => ($report_study->members->filter(function ($member) {
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
     * @param  \App\Models\Study  $study
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Study $report_study)
    {
        $validatedData = $request->validate([
            "report" => "required"
        ]);
        if ($report_study->report) {

            $reportData = [
                "submitted_date" => Carbon::now(),
                "status" => "SUBMITTED"
            ];

            if ($request->file('report')) {
                if ($report_study->report->file) Storage::delete($report_study->report->file);
                $reportData["file"] = $request->file('report')->store('report');
            }

            Report::where('id', $report_study->report_id)->update($reportData);
        } else {
            if ($request->file('report'))
                $validatedData["file"] = $request->file('report')->store('report');

            Study::where('id', $report_study->id)->update([
                "report_id" => Report::create([
                    "file" => $validatedData['file'],
                    "submitted_date" => Carbon::now(),
                    "status" => "SUBMITTED",
                    "comments" => NULL
                ])->id
            ]);
        }
        return redirect('report-study');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Study  $study
     * @return \Illuminate\Http\Response
     */
    public function destroy(Study $report_study)
    {
        Study::where('id', $report_study->id)->update(['report_id' => NULL]);
        if ($report_study->report->file) Storage::delete($report_study->report->file);
        Report::where('id', $report_study->report_id)->delete();
        return redirect('report-study');
    }

    public function approve(Request $request, Study $report_study)
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

        Report::where('id', $report_study->report_id)->update($reportData);
        return redirect('/report-research');
    }
}
