<?php

namespace App\Http\Controllers;

use App\Models\Study;
use App\Models\StudyMember;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StudyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->status === "ADMIN")
            $studies = Study::latest()->get();
        else
            $studies = StudyMember::where('employee_id', auth()->user()->employee->id)->orderBy('id', 'DESC')->groupBy('study_id')->get()->map(function ($study) {
                return $study->study;
            });

        $studies = $studies->filter(function ($study) {
            return $study->proposal->status === "APPROVED";
        })->map(function ($study) {
            $submitted_date = new Carbon($study->proposal->submitted_date);
            $approved_date = new Carbon($study->proposal->approved_date);

            $today_date = Carbon::now();
            $study_duration = $approved_date->diffInDays($today_date);

            if ($study->report) {
                if ($study->report->status === "APPROVED") {
                    $report_approved_date = new Carbon($study->report->approved_date);
                    $study_duration = $approved_date->diffInDays($report_approved_date);
                    $report_approved_date = ($report_approved_date->day . " " . $report_approved_date->locale('ID')->getTranslatedMonthName() . " " . $report_approved_date->year);
                }
            }
            return (object)[
                "id" => $study->id,
                "head" => $study->members->filter(function ($member) {
                    return $member->status === "HEAD";
                })->first()->employee,
                "submitted_date" => ($submitted_date->day . " " . $submitted_date->locale('ID')->getTranslatedMonthName() . " " . $submitted_date->year),
                "approved_date" => ($approved_date->day . " " . $approved_date->locale('ID')->getTranslatedMonthName() . " " . $approved_date->year),
                "study_duration" => $study_duration,
                "report_approved_date" => $report_approved_date ??  false
            ];
        });
        return view('dashboard.study.index', [
            'studies' => $studies
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Study  $study
     * @return \Illuminate\Http\Response
     */
    public function show(Study $study)
    {
        $submitted_timestamp = new Carbon($study->proposal->submitted_date);
        $approved_timestamp = new Carbon($study->proposal->approved_date);

        $study_duration = $approved_timestamp->diffInDays(Carbon::now());

        if ($study->report) {
            if ($study->report->status === "APPROVED") {
                $report_submitted_timestamp = new Carbon($study->report->submitted_date);
                $report_approved_timestamp = new Carbon($study->report->approved_date);
                $study_duration = $approved_timestamp->diffInDays($report_approved_timestamp);
                $report_submitted_date = ($report_submitted_timestamp->day . " " . $report_submitted_timestamp->locale('ID')->getTranslatedMonthName() . " " . $report_submitted_timestamp->year);
                $report_submitted_time = ($report_submitted_timestamp->hour . ":" . $report_submitted_timestamp->minute);
                $report_approved_date = ($report_approved_timestamp->day . " " . $report_approved_timestamp->locale('ID')->getTranslatedMonthName() . " " . $report_approved_timestamp->year);
                $report_approved_time = ($report_approved_timestamp->hour . ":" . $report_approved_timestamp->minute);
                $report_reviewer = $study->report->employee;
            }
        }

        return view('dashboard.study.show', [
            'study' =>  (object)[
                "id" => $study->id,
                "title" => $study->research->title,
                "head" => $study->members->filter(function ($member) {
                    return $member->status === "HEAD";
                })->first()->employee,
                "study_member" => ($study->members->filter(function ($member) {
                    return $member->status === "RESEARCHER";
                }))->map(function ($member) {
                    return $member->employee;
                }),
                "extensionists_member" => ($study->members->filter(function ($member) {
                    return $member->status === "EXTENSIONISTS";
                }))->map(function ($member) {
                    return $member->employee;
                }),
                "submitted_date" => ($submitted_timestamp->day . " " . $submitted_timestamp->locale('ID')->getTranslatedMonthName() . " " . $submitted_timestamp->year),
                "submitted_time" => ($submitted_timestamp->hour . ":" . $submitted_timestamp->minute),
                "approved_date" => ($approved_timestamp->day . " " . $approved_timestamp->locale('ID')->getTranslatedMonthName() . " " . $approved_timestamp->year),
                "approved_time" => ($approved_timestamp->hour . ":" . $approved_timestamp->minute),
                "study_duration" => $study_duration,
                "reviewer" => $study->proposal->employee,
                "report_reviewer" => $report_reviewer ?? false,
                "report_submitted_date" => $report_submitted_date ?? false,
                "report_submitted_time" => $report_submitted_time ?? false,
                "report_approved_date" => $report_approved_date ?? false,
                "report_approved_time" => $report_approved_time ?? false,
                "file_proposal" => $study->proposal->file,
                "file_report" => $study->report->file ?? false,
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Study  $study
     * @return \Illuminate\Http\Response
     */
    public function edit(Study $study)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Study  $study
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Study $study)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Study  $study
     * @return \Illuminate\Http\Response
     */
    public function destroy(Study $study)
    {
        //
    }
}
