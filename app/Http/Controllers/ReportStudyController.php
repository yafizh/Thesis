<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Report;
use App\Models\Study;
use App\Models\StudyMember;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportStudyController extends Controller
{
    public function index()
    {
        if (auth()->user()->status === "ADMIN" || auth()->user()->employee->status === "EXTERNAL") {
            $studies = Study::latest()->get();
            $studies = $studies->filter(function ($study) {
                return !is_null($study->report) && $study->report->status === "SUBMITTED";
            });
        } else {
            $studies = StudyMember::where('employee_id', auth()->user()->employee->id)->groupBy('study_id')->get()->map(function ($study) {
                return $study->study;
            });
            $studies = $studies->filter(function ($study) {
                return !is_null($study->report) && ($study->report->status === "SUBMITTED" || $study->report->status === "REJECTED");
            });
        }

        $reports = $studies->map(function ($study) {
            $submitted_date = new Carbon($study->report->submitted_date);
            return (object)[
                "study_id" => $study->id,
                "title" => $study->research->title,
                "head" => $study->members->filter(function ($member) {
                    return $member->status === "HEAD";
                })->first()->employee,
                "submitted_date" => ($submitted_date->day . " " . $submitted_date->getTranslatedMonthName() . " " . $submitted_date->year),
                "status" => $study->report->status === "SUBMITTED" ? "Menunggu Peninjauan" : "Telah Ditinjau",
            ];
        });
        return view('dashboard.study.report.index', [
            'page' => 'report-study',
            'reports' => $reports
        ]);
    }

    public function show(Study $report_study)
    {
        $submitted_date = new Carbon($report_study->proposal->submitted_date);

        $report = [
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
            "budgets" => $report_study->proposal->budgets,
            "submitted_date" => ($submitted_date->day . " " . $submitted_date->getTranslatedMonthName() . " " . $submitted_date->year),
            "submitted_time" => ($submitted_date->hour . ":" . ($submitted_date->minute < 10 ? "0" . $submitted_date->minute : $submitted_date->minute)),
            "file" => $report_study->report->file,
            "status" => $report_study->report->status,
            "comments" => $report_study->report->comments
        ];

        if ($report_study->report->approved_date) {
            $approved_date = new Carbon($report_study->report->approved_date);
            $report["approved_date"] = $approved_date->day . " " . $approved_date->getTranslatedMonthName() . " " . $approved_date->year;
            $report["approved_time"] = $approved_date->hour . ":" . ($approved_date->minute < 10 ? ("0" . $approved_date->minute) : $approved_date->minute);
        }

        return view('dashboard.study.report.show', [
            'page' => 'report-study',
            'report' =>  (object)$report,
        ]);
    }

    public function edit(Study $report_study)
    {
        if ($report_study->report) {
            $file = $report_study->report->file;
        }

        return view('dashboard.study.report.edit', [
            'page' => 'report-study',
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
                "budgets" => $report_study->proposal->budgets,
                "file" => $file ?? ''
            ],
        ]);
    }

    public function update(Request $request, Study $report_study)
    {
        $validatedData = $request->validate([
            'budged.*' => 'required|mimes:png,jpg|max:1024',
            'report' => 'required|mimes:pdf|max:2048',
        ], [
            "budged.*.mimes" => "File Nota harus bertipe .png atau .jpg",
            "budged.*.max" => "File Nota tidak boleh lebih dari 1MB",
            "report.mimes" => "File Laporan Akhir harus bertipe .pdf",
            "report.max" => "File Laporan Akhir tidak boleh lebih dari 2MB",
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

            foreach ($validatedData['budged'] as $index => $budged) {
                if ($report_study->proposal->budgets[$index]) Storage::delete($report_study->proposal->budgets[$index]);
                Budget::where('id', $report_study->proposal->budgets[$index]->id)->update([
                    "memorandum" => $request->file('budged')[$index]->store('memorandum'),
                ]);
            }

            Report::where('id', $report_study->report_id)->update($reportData);
            return redirect('report-study')->with('updated', $report_study->id);
        } else {
            if ($request->file('report'))
                $validatedData["file"] = $request->file('report')->store('report');

            foreach ($validatedData['budged'] as $index => $budged) {
                Budget::where('id', $report_study->proposal->budgets[$index]->id)->update([
                    "memorandum" => $request->file('budged')[$index]->store('memorandum'),
                ]);
            }

            Study::where('id', $report_study->id)->update([
                "report_id" => Report::create([
                    "file" => $validatedData['file'],
                    "submitted_date" => Carbon::now(),
                    "status" => "SUBMITTED",
                    "comments" => NULL
                ])->id
            ]);
            return redirect('report-study')->with('created', $report_study->id);
        }
    }

    public function destroy(Study $report_study)
    {
        Study::where('id', $report_study->id)->update(['report_id' => NULL]);
        if ($report_study->report->file) Storage::delete($report_study->report->file);
        Report::where('id', $report_study->report_id)->delete();
        return redirect('report-study')->with('deleted', $report_study->research->title);
    }

    public function approve(Request $request, Study $report_study)
    {
        $validatedData = $request->validate([
            'submit' => 'string',
            'comments' => 'required'
        ], [
            'comments.required' => "Keterangan perlu di-isi"
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
        return redirect('/report-study')->with($validatedData['status'], $report_study->research->title);
    }

    public function report(Request $request)
    {

        if ($request->get('submit') === 'submit' || $request->get('submit') === 'reset') {
            $reports = Study::latest()->get()->filter(function ($study) {
                return !is_null($study->report);
            });
            $reports = $reports->map(function ($study) {
                if ($study->report->status === "SUBMITTED")
                    $status = "Pengajuan";
                elseif ($study->report->status === "APPROVED")
                    $status = "Disetujui";
                elseif ($study->report->status === "REJECTED")
                    $status = "Ditolak";

                $submitted_date = new Carbon($study->report->submitted_date);

                $data = [
                    "title" => $study->research->title,
                    "head" => $study->members->filter(function ($member) {
                        return $member->status === "HEAD";
                    })->first()->employee,
                    "submitted_date" => ($submitted_date->day . " " . $submitted_date->getTranslatedMonthName() . " " . $submitted_date->year),
                    "status" => $status,
                ];

                if ($study->report->approved_date) {
                    $approved_date = new Carbon();

                    $data = array_merge($data, [
                        "reviewer" => $study->report->employee,
                        "approved_duration" => ($approved_date->diffInDays(Carbon::now()) . " Hari"),
                    ]);
                } else {
                    $data = array_merge($data, [
                        "reviewer" => "Masih Ditinjau",
                        "approved_duration" => "Masih Ditinjau",
                    ]);
                }

                return (object)$data;
            });
            return view('dashboard.study.report.report', [
                'page' => 'report_study_report',
                'reports' => $reports
            ]);
        } elseif ($request->get('submit') === 'filter' || $request->get('submit') === 'print') {
            if ($request->get('status') === "SUBMITTED") {
                $status['ID'] = "Pengajuan";
                $status['DB'] = "SUBMITTED";
            } else if ($request->get('status') === "APPROVED") {
                $status['ID'] = "Disetujui";
                $status['DB'] = "APPROVED";
            } else if ($request->get('status') === "REJECTED") {
                $status['ID'] = "Ditolak";
                $status['DB'] = "REJECTED";
            } else {
                $status['ID'] = "Semua";
                $status['DB'] = "";
            }
            if (!empty($request->get('from')) && !empty($request->get('to'))) {
                $from = $request->get('from');
                $to = $request->get('to');
                $studies =  Study::whereHas('report', function ($query) use ($from, $to, $status) {
                    $query->whereBetween('submitted_date', [$from, $to])->where("status", 'LIKE', '%' . $status['DB'] . '%');
                })->get()->filter(function ($study) {
                    return !is_null($study->report);
                });
                $reports = $studies->map(function ($study) {
                    if ($study->report->status === "SUBMITTED")
                        $status = "Pengajuan";
                    elseif ($study->report->status === "APPROVED")
                        $status = "Disetujui";
                    elseif ($study->report->status === "REJECTED")
                        $status = "Ditolak";

                    $submitted_date = new Carbon($study->report->submitted_date);

                    $data = [
                        "title" => $study->research->title,
                        "head" => $study->members->filter(function ($member) {
                            return $member->status === "HEAD";
                        })->first()->employee,
                        "submitted_date" => ($submitted_date->day . " " . $submitted_date->getTranslatedMonthName() . " " . $submitted_date->year),
                        "status" => $status,
                    ];

                    if ($study->report->approved_date) {
                        $approved_date = new Carbon();

                        $data = array_merge($data, [
                            "reviewer" => $study->report->employee,
                            "approved_duration" => ($approved_date->diffInDays(Carbon::now()) . " Hari"),
                        ]);
                    } else {
                        $data = array_merge($data, [
                            "reviewer" => "Masih Ditinjau",
                            "approved_duration" => "Masih Ditinjau",
                        ]);
                    }

                    return (object)$data;
                });
            } else {
                $studies = Study::whereHas('report', function ($query) use ($status) {
                    $query->where("status", 'LIKE', '%' . $status['DB'] . '%');
                })->latest()->get();
                $reports = $studies->map(function ($study) {
                    if ($study->report->status === "SUBMITTED")
                        $status = "Pengajuan";
                    elseif ($study->report->status === "APPROVED")
                        $status = "Disetujui";
                    elseif ($study->report->status === "REJECTED")
                        $status = "Ditolak";

                    $submitted_date = new Carbon($study->report->submitted_date);

                    $data = [
                        "title" => $study->research->title,
                        "head" => $study->members->filter(function ($member) {
                            return $member->status === "HEAD";
                        })->first()->employee,
                        "submitted_date" => ($submitted_date->day . " " . $submitted_date->getTranslatedMonthName() . " " . $submitted_date->year),
                        "status" => $status,
                    ];

                    if ($study->report->approved_date) {
                        $approved_date = new Carbon();

                        $data = array_merge($data, [
                            "reviewer" => $study->report->employee,
                            "approved_duration" => ($approved_date->diffInDays(Carbon::now()) . " Hari"),
                        ]);
                    } else {
                        $data = array_merge($data, [
                            "reviewer" => "Masih Ditinjau",
                            "approved_duration" => "Masih Ditinjau",
                        ]);
                    }

                    return (object)$data;
                });
            }

            if ($request->get('submit') === 'filter') {
                return view('dashboard.study.report.report', [
                    'page' => 'report_study_report',
                    'from' => $request->get('from') ?? '',
                    'to' => $request->get('to') ?? '',
                    'status' => $status['ID'],
                    'reports' => $reports
                ]);
            } elseif ($request->get('submit') === 'print') {
                return view('dashboard.study.report.print', [
                    'from' => $request->get('from') ?? '',
                    'to' => $request->get('to') ?? '',
                    'status' => $status['ID'],
                    'reports' => $reports
                ]);
            }
        }
    }
}
