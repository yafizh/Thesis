<?php

namespace App\Http\Controllers;

use App\Models\Research;
use App\Models\ResearchMember;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ResearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->status === "ADMIN")
            $researches = Research::latest()->get();
        else
            $researches = ResearchMember::where('employee_id', auth()->user()->employee->id)->orderBy('id', 'DESC')->groupBy('research_id')->get()->map(function ($research) {
                return $research->research;
            });

        $researches = $researches->filter(function ($research) {
            return $research->proposal->status === "APPROVED";
        })->map(function ($research) {
            $submitted_date = new Carbon($research->proposal->submitted_date);
            $approved_date = new Carbon($research->proposal->approved_date);

            $today_date = Carbon::now();
            $research_duration = $approved_date->diffInDays($today_date);

            if ($research->report) {
                if ($research->report->status === "APPROVED") {
                    $report_approved_date = new Carbon($research->report->approved_date);
                    $research_duration = $approved_date->diffInDays($report_approved_date);
                    $report_approved_date = ($report_approved_date->day . " " . $report_approved_date->locale('ID')->getTranslatedMonthName() . " " . $report_approved_date->year);
                }
            }
            return (object)[
                "id" => $research->id,
                "head" => $research->members->filter(function ($member) {
                    return $member->status === "HEAD";
                })->first()->employee,
                "submitted_date" => ($submitted_date->day . " " . $submitted_date->locale('ID')->getTranslatedMonthName() . " " . $submitted_date->year),
                "approved_date" => ($approved_date->day . " " . $approved_date->locale('ID')->getTranslatedMonthName() . " " . $approved_date->year),
                "research_duration" => $research_duration,
                "report_approved_date" => $report_approved_date ??  false
            ];
        });
        return view('dashboard.research.index', [
            'researches' => $researches
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
     * @param  \App\Models\Research  $research
     * @return \Illuminate\Http\Response
     */
    public function show(Research $research)
    {
        $submitted_timestamp = new Carbon($research->proposal->submitted_date);
        $approved_timestamp = new Carbon($research->proposal->approved_date);

        $research_duration = $approved_timestamp->diffInDays(Carbon::now());

        if ($research->report) {
            if ($research->report->status === "APPROVED") {
                $report_submitted_timestamp = new Carbon($research->report->submitted_date);
                $report_approved_timestamp = new Carbon($research->report->approved_date);
                $research_duration = $approved_timestamp->diffInDays($report_approved_timestamp);
                $report_submitted_date = ($report_submitted_timestamp->day . " " . $report_submitted_timestamp->locale('ID')->getTranslatedMonthName() . " " . $report_submitted_timestamp->year);
                $report_submitted_time = ($report_submitted_timestamp->hour . ":" . $report_submitted_timestamp->minute);
                $report_approved_date = ($report_approved_timestamp->day . " " . $report_approved_timestamp->locale('ID')->getTranslatedMonthName() . " " . $report_approved_timestamp->year);
                $report_approved_time = ($report_approved_timestamp->hour . ":" . $report_approved_timestamp->minute);
                $report_reviewer = $research->report->employee;
            }
        }

        return view('dashboard.research.show', [
            'research' =>  (object)[
                "id" => $research->id,
                "title" => $research->title,
                "head" => $research->members->filter(function ($member) {
                    return $member->status === "HEAD";
                })->first()->employee,
                "research_member" => ($research->members->filter(function ($member) {
                    return $member->status === "RESEARCHER";
                }))->map(function ($member) {
                    return $member->employee;
                }),
                "extensionists_member" => ($research->members->filter(function ($member) {
                    return $member->status === "EXTENSIONISTS";
                }))->map(function ($member) {
                    return $member->employee;
                }),
                "submitted_date" => ($submitted_timestamp->day . " " . $submitted_timestamp->locale('ID')->getTranslatedMonthName() . " " . $submitted_timestamp->year),
                "submitted_time" => ($submitted_timestamp->hour . ":" . $submitted_timestamp->minute),
                "approved_date" => ($approved_timestamp->day . " " . $approved_timestamp->locale('ID')->getTranslatedMonthName() . " " . $approved_timestamp->year),
                "approved_time" => ($approved_timestamp->hour . ":" . $approved_timestamp->minute),
                "research_duration" => $research_duration,
                "reviewer" => $research->proposal->employee,
                "report_reviewer" => $report_reviewer ?? false,
                "report_submitted_date" => $report_submitted_date ?? false,
                "report_submitted_time" => $report_submitted_time ?? false,
                "report_approved_date" => $report_approved_date ?? false,
                "report_approved_time" => $report_approved_time ?? false,
                "file_proposal" => $research->proposal->file,
                "file_report" => $research->report->file ?? false,
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Research  $research
     * @return \Illuminate\Http\Response
     */
    public function edit(Research $research)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Research  $research
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Research $research)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Research  $research
     * @return \Illuminate\Http\Response
     */
    public function destroy(Research $research)
    {
        //
    }
}
