<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Study;
use App\Models\StudyMember;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StudyController extends Controller
{
    public function index()
    {
        if (auth()->user()->status === "ADMIN" || auth()->user()->employee->status === "EXTERNAL")
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
                    $report_approved_date = ($report_approved_date->day . " " . $report_approved_date->getTranslatedMonthName() . " " . $report_approved_date->year);
                }
            }
            return (object)[
                "id" => $study->id,
                "head" => $study->members->filter(function ($member) {
                    return $member->status === "HEAD";
                })->first()->employee,
                "submitted_date" => ($submitted_date->day . " " . $submitted_date->getTranslatedMonthName() . " " . $submitted_date->year),
                "approved_date" => ($approved_date->day . " " . $approved_date->getTranslatedMonthName() . " " . $approved_date->year),
                "study_duration" => $study_duration,
                "report_approved_date" => $report_approved_date ??  false
            ];
        });
        return view('dashboard.study.index', [
            'page' => 'study',
            'studies' => $studies
        ]);
    }

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
                $report_submitted_date = ($report_submitted_timestamp->day . " " . $report_submitted_timestamp->getTranslatedMonthName() . " " . $report_submitted_timestamp->year);
                $report_submitted_time = ($report_submitted_timestamp->hour . ":" . ($report_submitted_timestamp->minute < 10 ? '0' . $report_submitted_timestamp->minute : $report_submitted_timestamp->minute));
                $report_approved_date = ($report_approved_timestamp->day . " " . $report_approved_timestamp->getTranslatedMonthName() . " " . $report_approved_timestamp->year);
                $report_approved_time = ($report_approved_timestamp->hour . ":" . ($report_approved_timestamp->minute < 10 ? '0' . $report_approved_timestamp->minute : $report_approved_timestamp->minute));
                $report_reviewer = $study->report->employee;
                $status = "APPROVED";
            } else {
                $status = "SUBMITTED";
            }
        } elseif (!$study->status_budget) {
            $status = "WAITING";
        } else {
            $status = "ONGOING";
        }

        return view('dashboard.study.show', [
            'page' => 'study',
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
                "budgets" => $study->proposal->budgets,
                "submitted_date" => ($submitted_timestamp->day . " " . $submitted_timestamp->getTranslatedMonthName() . " " . $submitted_timestamp->year),
                "submitted_time" => ($submitted_timestamp->hour . ":" . ($submitted_timestamp->minute < 10 ? '0' . $submitted_timestamp->minute : $submitted_timestamp->minute)),
                "approved_date" => ($approved_timestamp->day . " " . $approved_timestamp->getTranslatedMonthName() . " " . $approved_timestamp->year),
                "approved_time" => ($approved_timestamp->hour . ":" . ($approved_timestamp->minute < 10 ? '0' . $approved_timestamp->minute : $approved_timestamp->minute)),
                "study_duration" => $study_duration,
                "reviewer" => $study->proposal->employee,
                "report_reviewer" => $report_reviewer ?? false,
                "report_submitted_date" => $report_submitted_date ?? false,
                "report_submitted_time" => $report_submitted_time ?? false,
                "report_approved_date" => $report_approved_date ?? false,
                "report_approved_time" => $report_approved_time ?? false,
                "file_proposal" => $study->proposal->file,
                "file_report" => $study->report->file ?? false,
                "status" => $status
            ],
        ]);
    }

    public function budget(Study $study)
    {
        Study::where('id', $study->id)->update([
            'status_budget' => 'SEND',
        ]);
        return back();
    }

    public function report(Request $request)
    {
        if ($request->get('submit') === 'submit' || $request->get('submit') === 'reset') {
            $studies = Study::whereHas('proposal', function ($query) {
                return $query->where('status', 'APPROVED');
            })->latest()->get()->map(function ($study) {
                $proposal_approved_date = new Carbon($study->proposal->approved_date);

                $data = [
                    "title" => $study->research->title,
                    "head" => $study->members->filter(function ($member) {
                        return $member->status === "HEAD";
                    })->first()->employee,
                    "start_date" => ($proposal_approved_date->day . " " . $proposal_approved_date->getTranslatedMonthName() . " " . $proposal_approved_date->year),
                ];

                if ($study->report) {
                    if ($study->report->status === "APPROVED") {
                        $report_approved_date = new Carbon($study->report->approved_date);

                        $data = array_merge($data, [
                            "reviewer" => $study->proposal->employee,
                            "study_duration" => ($proposal_approved_date->diffInDays($report_approved_date) . " Hari"),
                            "status" => "Selesai"
                        ]);
                    } else {
                        $data = array_merge($data, [
                            "reviewer" => "Menunggu Peninjauan",
                            "study_duration" => "Menunggu Peninjauan",
                            "status" => "Menunggu Peninjauan",
                        ]);
                    }
                } else {
                    $data = array_merge($data, [
                        "reviewer" => "Sedang Berjalan",
                        "study_duration" => "Sedang Berjalan",
                        "status" => "Sedang Berjalan",
                    ]);
                }

                return (object)$data;
            });

            return view('dashboard.study.report', [
                'page' => 'study_report',
                'studies' => $studies
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
                    $studies = Study::whereHas('proposal', function ($query)  use ($from, $to) {
                        return $query->whereBetween('submitted_date', [$from, $to]);
                    })->whereHas('report', function ($query) {
                        return $query->where('status', 'APPROVED');
                    })->latest()->get();
                } else if ($request->get('status') === "ONGOING") {
                    $studies = Study::whereHas('proposal', function ($query)  use ($from, $to) {
                        return $query->whereBetween('submitted_date', [$from, $to]);
                    })->whereHas('report', function ($query) {
                        return $query->where('status', '!=', 'APPROVED');
                    })->latest()->get();
                } else {
                    $studies = Study::whereHas('proposal', function ($query) use ($from, $to) {
                        return $query->whereBetween('submitted_date', [$from, $to])->where('status', 'APPROVED');
                    })->latest()->get();
                }

                $studies = $studies->map(function ($study) {
                    $proposal_approved_date = new Carbon($study->proposal->approved_date);

                    $data = [
                        "title" => $study->research->title,
                        "head" => $study->members->filter(function ($member) {
                            return $member->status === "HEAD";
                        })->first()->employee,
                        "start_date" => ($proposal_approved_date->day . " " . $proposal_approved_date->getTranslatedMonthName() . " " . $proposal_approved_date->year),
                    ];

                    if ($study->report) {
                        if ($study->report->status === "APPROVED") {
                            $report_approved_date = new Carbon($study->report->approved_date);

                            $data = array_merge($data, [
                                "reviewer" => $study->proposal->employee,
                                "study_duration" => ($proposal_approved_date->diffInDays($report_approved_date) . " Hari"),
                                "status" => "Selesai"
                            ]);
                        } else {
                            $data = array_merge($data, [
                                "reviewer" => "Menunggu Peninjauan",
                                "study_duration" => "Menunggu Peninjauan",
                                "status" => "Menunggu Peninjauan",
                            ]);
                        }
                    } else {
                        $data = array_merge($data, [
                            "reviewer" => "Sedang Berjalan",
                            "study_duration" => "Sedang Berjalan",
                            "status" => "Sedang Berjalan",
                        ]);
                    }

                    return (object)$data;
                });
            } else {
                if ($request->get('status') === "FINISH") {
                    $studies = Study::whereHas('report', function ($query) {
                        return $query->where('status', 'APPROVED');
                    })->latest()->get();
                } else if ($request->get('status') === "ONGOING") {
                    $studies = Study::whereHas('report', function ($query) {
                        return $query->where('status', '!=', 'APPROVED');
                    })->latest()->get();
                } else {
                    $studies = Study::whereHas('proposal', function ($query) {
                        return $query->where('status', 'APPROVED');
                    })->latest()->get();
                }
                $studies = $studies->map(function ($study) {
                    $proposal_approved_date = new Carbon($study->proposal->approved_date);

                    $data = [
                        "title" => $study->research->title,
                        "head" => $study->members->filter(function ($member) {
                            return $member->status === "HEAD";
                        })->first()->employee,
                        "start_date" => ($proposal_approved_date->day . " " . $proposal_approved_date->getTranslatedMonthName() . " " . $proposal_approved_date->year),
                    ];

                    if ($study->report) {
                        if ($study->report->status === "APPROVED") {
                            $report_approved_date = new Carbon($study->report->approved_date);

                            $data = array_merge($data, [
                                "reviewer" => $study->proposal->employee,
                                "study_duration" => ($proposal_approved_date->diffInDays($report_approved_date) . " Hari"),
                                "status" => "Selesai"
                            ]);
                        } else {
                            $data = array_merge($data, [
                                "reviewer" => "Menunggu Peninjauan",
                                "study_duration" => "Menunggu Peninjauan",
                                "status" => "Menunggu Peninjauan",
                            ]);
                        }
                    } else {
                        $data = array_merge($data, [
                            "reviewer" => "Sedang Berjalan",
                            "study_duration" => "Sedang Berjalan",
                            "status" => "Sedang Berjalan",
                        ]);
                    }

                    return (object)$data;
                });
            }

            if ($request->get('submit') === 'filter') {
                return view('dashboard.study.report', [
                    'page' => 'study_report',
                    'from' => $request->get('from') ?? '',
                    'to' => $request->get('to') ?? '',
                    'status' => $status['ID'],
                    'studies' => $studies
                ]);
            } elseif ($request->get('submit') === 'print') {
                return view('dashboard.study.print', [
                    'from' => $request->get('from') ?? '',
                    'to' => $request->get('to') ?? '',
                    'status' => $status['ID'],
                    'studies' => $studies
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
                "head" => $employee->studies->filter(function ($employee) {
                    return $employee->status === "HEAD";
                })->count() . " Kali",
                "researcher" => $employee->studies->filter(function ($employee) {
                    return $employee->status === "RESEARCHER";
                })->count() . " Kali",
                "extensionist" => $employee->studies->filter(function ($employee) {
                    return $employee->status === "EXTENSIONIST";
                })->count() . " Kali",
            ];
        });
        if ($request->get('submit') === 'submit' || $request->get('submit') === 'reset') {
            return view('dashboard.study.report_member', [
                'page' => 'study_member_report',
                'members' => $members
            ]);
        } elseif ($request->get('submit') === 'print') {
            return view('dashboard.study.print_member', [
                'members' => $members
            ]);
        }
    }
}
