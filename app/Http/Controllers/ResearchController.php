<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Research;
use App\Models\ResearchMember;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ResearchController extends Controller
{
    public function index()
    {
        if (auth()->user()->status === "ADMIN" || auth()->user()->employee->status === "EXTERNAL")
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
                    $report_approved_date = ($report_approved_date->day . " " . $report_approved_date->getTranslatedMonthName() . " " . $report_approved_date->year);
                }
            }
            return (object)[
                "id" => $research->id,
                "head" => $research->members->filter(function ($member) {
                    return $member->status === "HEAD";
                })->first()->employee,
                "submitted_date" => ($submitted_date->day . " " . $submitted_date->getTranslatedMonthName() . " " . $submitted_date->year),
                "approved_date" => ($approved_date->day . " " . $approved_date->getTranslatedMonthName() . " " . $approved_date->year),
                "research_duration" => $research_duration,
                "report_approved_date" => $report_approved_date ??  false
            ];
        });
        return view('dashboard.research.index', [
            'page' => 'research',
            'researches' => $researches
        ]);
    }

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
                $report_submitted_date = ($report_submitted_timestamp->day . " " . $report_submitted_timestamp->getTranslatedMonthName() . " " . $report_submitted_timestamp->year);
                $report_submitted_time = ($report_submitted_timestamp->hour . ":" . $report_submitted_timestamp->minute);
                $report_approved_date = ($report_approved_timestamp->day . " " . $report_approved_timestamp->getTranslatedMonthName() . " " . $report_approved_timestamp->year);
                $report_approved_time = ($report_approved_timestamp->hour . ":" . $report_approved_timestamp->minute);
                $report_reviewer = $research->report->employee;
                $status = "APPROVED";
            } else {
                $status = "SUBMITTED";
            }
        } elseif (!$research->status_budget) {
            $status = "WAITING";
        } else {
            $status = "ONGOING";
        }
        return view('dashboard.research.show', [
            'page' => 'research',
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
                "budgets" => $research->proposal->budgets,
                "submitted_date" => ($submitted_timestamp->day . " " . $submitted_timestamp->getTranslatedMonthName() . " " . $submitted_timestamp->year),
                "submitted_time" => ($submitted_timestamp->hour . ":" . ($submitted_timestamp->minute < 10 ? ("0" . $submitted_timestamp->minute) : $submitted_timestamp->minute)),
                "approved_date" => ($approved_timestamp->day . " " . $approved_timestamp->getTranslatedMonthName() . " " . $approved_timestamp->year),
                "approved_time" => ($approved_timestamp->hour . ":" . ($approved_timestamp->minute < 10 ? ("0" . $approved_timestamp->minute) : $approved_timestamp->minute)),
                "research_duration" => $research_duration,
                "reviewer" => $research->proposal->employee,
                "report_reviewer" => $report_reviewer ?? false,
                "report_submitted_date" => $report_submitted_date ?? false,
                "report_submitted_time" => $report_submitted_time ?? false,
                "report_approved_date" => $report_approved_date ?? false,
                "report_approved_time" => $report_approved_time ?? false,
                "file_proposal" => $research->proposal->file,
                "file_report" => $research->report->file ?? false,
                "status" => $status
            ],
        ]);
    }

    public function budget(Research $research)
    {
        Research::where('id', $research->id)->update([
            'status_budget' => 'SEND',
        ]);
        return back();
    }

    public function report(Request $request)
    {
        if ($request->get('submit') === 'submit' || $request->get('submit') === 'reset') {
            $researches = Research::whereHas('proposal', function ($query) {
                return $query->where('status', 'APPROVED');
            })->latest()->get()->map(function ($research) {
                $proposal_approved_date = new Carbon($research->proposal->approved_date);

                $data = [
                    "title" => $research->title,
                    "head" => $research->members->filter(function ($member) {
                        return $member->status === "HEAD";
                    })->first()->employee,
                    "start_date" => ($proposal_approved_date->day . " " . $proposal_approved_date->getTranslatedMonthName() . " " . $proposal_approved_date->year),
                ];

                if ($research->report) {
                    if ($research->report->status === "APPROVED") {
                        $report_approved_date = new Carbon($research->report->approved_date);

                        $data = array_merge($data, [
                            "reviewer" => $research->proposal->employee,
                            "research_duration" => ($proposal_approved_date->diffInDays($report_approved_date) . " Hari"),
                            "status" => "Selesai"
                        ]);
                    } else {
                        $data = array_merge($data, [
                            "reviewer" => "Menunggu Peninjauan",
                            "research_duration" => "Menunggu Peninjauan",
                            "status" => "Menunggu Peninjauan",
                        ]);
                    }
                } else {
                    $data = array_merge($data, [
                        "reviewer" => "Sedang Berjalan",
                        "research_duration" => "Sedang Berjalan",
                        "status" => "Sedang Berjalan",
                    ]);
                }

                return (object)$data;
            });

            return view('dashboard.research.report', [
                'researches' => $researches
            ]);
        } elseif ($request->get('submit') === 'filter' || $request->get('submit') === 'print') {
            if ($request->get('status') === "FINISH") {
                $status['ID'] = "Selesai";
                $status['DB'] = "FINISH";
            } else if ($request->get('status') === "ONGOING") {
                $status['ID'] = "Sedang Berjalan";
                $status['DB'] = "ONGOING";
            } else {
                $status['ID'] = "Semua";
                $status['DB'] = "";
            }

            if (!empty($request->get('from')) && !empty($request->get('to'))) {
                $from = $request->get('from');
                $to = $request->get('to');
                if ($request->get('status') === "FINISH") {
                    $researches = Research::whereHas('proposal', function ($query)  use ($from, $to) {
                        return $query->whereBetween('submitted_date', [$from, $to]);
                    })->whereHas('report', function ($query) {
                        return $query->where('status', 'APPROVED');
                    })->latest()->get();
                } else if ($request->get('status') === "ONGOING") {
                    $researches = Research::whereHas('proposal', function ($query)  use ($from, $to) {
                        return $query->whereBetween('submitted_date', [$from, $to]);
                    })->whereHas('report', function ($query) {
                        return $query->where('status', '!=', 'APPROVED');
                    })->latest()->get();
                } else {
                    $researches = Research::whereHas('proposal', function ($query) use ($from, $to) {
                        return $query->whereBetween('submitted_date', [$from, $to])->where('status', 'APPROVED');
                    })->latest()->get();
                }

                $researches = $researches->map(function ($research) {
                    $proposal_approved_date = new Carbon($research->proposal->approved_date);

                    $data = [
                        "title" => $research->title,
                        "head" => $research->members->filter(function ($member) {
                            return $member->status === "HEAD";
                        })->first()->employee,
                        "start_date" => ($proposal_approved_date->day . " " . $proposal_approved_date->getTranslatedMonthName() . " " . $proposal_approved_date->year),
                    ];

                    if ($research->report) {
                        if ($research->report->status === "APPROVED") {
                            $report_approved_date = new Carbon($research->report->approved_date);

                            $data = array_merge($data, [
                                "reviewer" => $research->proposal->employee,
                                "research_duration" => ($proposal_approved_date->diffInDays($report_approved_date) . " Hari"),
                                "status" => "Selesai"
                            ]);
                        } else {
                            $data = array_merge($data, [
                                "reviewer" => "Menunggu Peninjauan",
                                "research_duration" => "Menunggu Peninjauan",
                                "status" => "Menunggu Peninjauan",
                            ]);
                        }
                    } else {
                        $data = array_merge($data, [
                            "reviewer" => "Sedang Berjalan",
                            "research_duration" => "Sedang Berjalan",
                            "status" => "Sedang Berjalan",
                        ]);
                    }

                    return (object)$data;
                });
            } else {
                if ($request->get('status') === "FINISH") {
                    $researches = Research::whereHas('report', function ($query) {
                        return $query->where('status', 'APPROVED');
                    })->latest()->get();
                } else if ($request->get('status') === "ONGOING") {
                    $researches = Research::whereHas('report', function ($query) {
                        return $query->where('status', '!=', 'APPROVED');
                    })->latest()->get();
                } else {
                    $researches = Research::whereHas('proposal', function ($query) {
                        return $query->where('status', 'APPROVED');
                    })->latest()->get();
                }
                $researches = $researches->map(function ($research) {
                    $proposal_approved_date = new Carbon($research->proposal->approved_date);

                    $data = [
                        "title" => $research->title,
                        "head" => $research->members->filter(function ($member) {
                            return $member->status === "HEAD";
                        })->first()->employee,
                        "start_date" => ($proposal_approved_date->day . " " . $proposal_approved_date->getTranslatedMonthName() . " " . $proposal_approved_date->year),
                    ];

                    if ($research->report) {
                        if ($research->report->status === "APPROVED") {
                            $report_approved_date = new Carbon($research->report->approved_date);

                            $data = array_merge($data, [
                                "reviewer" => $research->proposal->employee,
                                "research_duration" => ($proposal_approved_date->diffInDays($report_approved_date) . " Hari"),
                                "status" => "Selesai"
                            ]);
                        } else {
                            $data = array_merge($data, [
                                "reviewer" => "Menunggu Peninjauan",
                                "research_duration" => "Menunggu Peninjauan",
                                "status" => "Menunggu Peninjauan",
                            ]);
                        }
                    } else {
                        $data = array_merge($data, [
                            "reviewer" => "Sedang Berjalan",
                            "research_duration" => "Sedang Berjalan",
                            "status" => "Sedang Berjalan",
                        ]);
                    }

                    return (object)$data;
                });
            }

            if ($request->get('submit') === 'filter') {
                return view('dashboard.research.report', [
                    'from' => $request->get('from') ?? '',
                    'to' => $request->get('to') ?? '',
                    'status' => $status['ID'],
                    'researches' => $researches
                ]);
            } elseif ($request->get('submit') === 'print') {
                return view('dashboard.research.print', [
                    'from' => $request->get('from') ?? '',
                    'to' => $request->get('to') ?? '',
                    'status' => $status['ID'],
                    'researches' => $researches
                ]);
            }
        }
    }

    public function member_report(Request $request)
    {
        $members = Employee::orderBy('name')->get()->map(function ($employee) {
            return (object)[
                "nip" => $employee->nip,
                "name" => $employee->name,
                "head" => $employee->researches->filter(function ($employee) {
                    return $employee->status === "HEAD";
                })->count() . " Kali",
                "researcher" => $employee->researches->filter(function ($employee) {
                    return $employee->status === "RESEARCHER";
                })->count() . " Kali",
                "extensionist" => $employee->researches->filter(function ($employee) {
                    return $employee->status === "EXTENSIONIST";
                })->count() . " Kali",
            ];
        });
        if ($request->get('submit') === 'submit' || $request->get('submit') === 'reset') {
            return view('dashboard.research.report_member', [
                'members' => $members
            ]);
        } elseif ($request->get('submit') === 'print') {
            return view('dashboard.research.print_member', [
                'members' => $members
            ]);
        }
    }
}
