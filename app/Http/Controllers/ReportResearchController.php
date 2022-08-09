<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Report;
use App\Models\Research;
use App\Models\ResearchMember;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportResearchController extends Controller
{
    public function index()
    {
        if (auth()->user()->status === "ADMIN" || auth()->user()->employee->status === "EXTERNAL") {
            $researches = Research::latest()->get();
            $researches = $researches->filter(function ($research) {
                return !is_null($research->report) && $research->report->status === "SUBMITTED";
            });
        } else {
            $researches = ResearchMember::where('employee_id', auth()->user()->employee->id)->groupBy('research_id')->get()->map(function ($research) {
                return $research->research;
            });
            $researches = $researches->filter(function ($research) {
                return !is_null($research->report) && ($research->report->status === "SUBMITTED" || $research->report->status === "REJECTED");
            });
        }

        $reports = $researches->map(function ($research) {
            $submitted_date = new Carbon($research->report->submitted_date);
            return (object)[
                "research_id" => $research->id,
                "head" => $research->members->filter(function ($member) {
                    return $member->status === "HEAD";
                })->first()->employee,
                "submitted_date" => ($submitted_date->day . " " . $submitted_date->getTranslatedMonthName() . " " . $submitted_date->year),
                "status" => $research->report->status === "SUBMITTED" ? "Menunggu Peninjauan" : "Telah Ditinjau",
            ];
        });
        return view('dashboard.research.report.index', [
            'page' => 'report-research',
            'reports' => $reports
        ]);
    }

    public function show(Research $report_research)
    {
        $submitted_date = new Carbon($report_research->proposal->submitted_date);

        $report = [
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
            "budgets" => $report_research->proposal->budgets,
            "submitted_date" => ($submitted_date->day . " " . $submitted_date->getTranslatedMonthName() . " " . $submitted_date->year),
            "submitted_time" => ($submitted_date->hour . ":" . ($submitted_date->minute < 10 ? "0" . $submitted_date->minute : $submitted_date->minute)),
            "file" => $report_research->report->file,
            "status" => $report_research->report->status,
            "comments" => $report_research->report->comments
        ];

        if ($report_research->report->approved_date) {
            $approved_date = new Carbon($report_research->report->approved_date);
            $report["approved_date"] = $approved_date->day . " " . $approved_date->getTranslatedMonthName() . " " . $approved_date->year;
            $report["approved_time"] = $approved_date->hour . ":" . ($approved_date->minute < 10 ? ("0" . $approved_date->minute) : $approved_date->minute);
        }

        return view('dashboard.research.report.show', [
            'page' => 'report-research',
            'report' =>  (object)$report,
        ]);
    }

    public function edit(Research $report_research)
    {
        if ($report_research->report) {
            $file = $report_research->report->file;
        }

        return view('dashboard.research.report.edit', [
            'page' => 'report-research',
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
                "budgets" => $report_research->proposal->budgets,
                "file" => $file ?? ''
            ],
        ]);
    }

    public function update(Request $request, Research $report_research)
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

        if ($report_research->report) {
            $reportData = [
                "submitted_date" => Carbon::now(),
                "status" => "SUBMITTED"
            ];

            if ($request->file('report')) {
                if ($report_research->report->file) Storage::delete($report_research->report->file);
                $reportData["file"] = $request->file('report')->store('report');
            }

            foreach ($validatedData['budged'] as $index => $budged) {
                if ($report_research->proposal->budgets[$index]) Storage::delete($report_research->proposal->budgets[$index]);
                Budget::where('id', $report_research->proposal->budgets[$index]->id)->update([
                    "memorandum" => $request->file('budged')[$index]->store('memorandum'),
                ]);
            }

            Report::where('id', $report_research->report_id)->update($reportData);
            return redirect('report-research')->with('updated', $report_research->id);
        } else {
            if ($request->file('report'))
                $validatedData["file"] = $request->file('report')->store('report');

            foreach ($validatedData['budged'] as $index => $budged) {
                Budget::where('id', $report_research->proposal->budgets[$index]->id)->update([
                    "memorandum" => $request->file('budged')[$index]->store('memorandum'),
                ]);
            }
            Research::where('id', $report_research->id)->update([
                "report_id" => Report::create([
                    "file" => $validatedData['file'],
                    "submitted_date" => Carbon::now(),
                    "status" => "SUBMITTED",
                    "comments" => NULL
                ])->id
            ]);
            return redirect('report-research')->with('created', $report_research->id);
        }
    }

    public function destroy(Research $report_research)
    {
        Research::where('id', $report_research->id)->update(['report_id' => NULL]);
        if ($report_research->report->file) Storage::delete($report_research->report->file);
        Report::where('id', $report_research->report_id)->delete();
        return redirect('report-research')->with("deleted", $report_research->title);
    }

    public function approve(Request $request, Research $report_research)
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

        Report::where('id', $report_research->report_id)->update($reportData);
        return redirect('/report-research')->with($validatedData['status'], $report_research->title);
    }

    public function report(Request $request)
    {

        if ($request->get('submit') === 'submit' || $request->get('submit') === 'reset') {
            $reports = Research::latest()->get()->filter(function ($research) {
                return !is_null($research->report);
            });
            $reports = $reports->map(function ($research) {
                if ($research->report->status === "SUBMITTED")
                    $status = "Pengajuan";
                elseif ($research->report->status === "APPROVED")
                    $status = "Disetujui";
                elseif ($research->report->status === "REJECTED")
                    $status = "Ditolak";

                $submitted_date = new Carbon($research->report->submitted_date);

                $data = [
                    "title" => $research->title,
                    "head" => $research->members->filter(function ($member) {
                        return $member->status === "HEAD";
                    })->first()->employee,
                    "submitted_date" => ($submitted_date->day . " " . $submitted_date->getTranslatedMonthName() . " " . $submitted_date->year),
                    "status" => $status,
                ];

                if ($research->report->approved_date) {
                    $approved_date = new Carbon();

                    $data = array_merge($data, [
                        "reviewer" => $research->report->employee,
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

            return view('dashboard.research.report.report', [
                'page' => 'report_research_report',
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
                $researches =  Research::whereHas('report', function ($query) use ($from, $to, $status) {
                    $query->whereBetween('submitted_date', [$from, $to])->where("status", 'LIKE', '%' . $status['DB'] . '%');
                })->get()->filter(function ($research) {
                    return !is_null($research->report);
                });
                $reports = $researches->map(function ($research) {
                    if ($research->report->status === "SUBMITTED")
                        $status = "Pengajuan";
                    elseif ($research->report->status === "APPROVED")
                        $status = "Disetujui";
                    elseif ($research->report->status === "REJECTED")
                        $status = "Ditolak";

                    $submitted_date = new Carbon($research->report->submitted_date);

                    $data = [
                        "title" => $research->title,
                        "head" => $research->members->filter(function ($member) {
                            return $member->status === "HEAD";
                        })->first()->employee,
                        "submitted_date" => ($submitted_date->day . " " . $submitted_date->getTranslatedMonthName() . " " . $submitted_date->year),
                        "status" => $status,
                    ];

                    if ($research->report->approved_date) {
                        $approved_date = new Carbon();

                        $data = array_merge($data, [
                            "reviewer" => $research->report->employee,
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
                $researches = Research::whereHas('report', function ($query) use ($status) {
                    $query->where("status", 'LIKE', '%' . $status['DB'] . '%');
                })->latest()->get();
                $reports = $researches->map(function ($research) {
                    if ($research->report->status === "SUBMITTED")
                        $status = "Pengajuan";
                    elseif ($research->report->status === "APPROVED")
                        $status = "Disetujui";
                    elseif ($research->report->status === "REJECTED")
                        $status = "Ditolak";

                    $submitted_date = new Carbon($research->report->submitted_date);

                    $data = [
                        "title" => $research->title,
                        "head" => $research->members->filter(function ($member) {
                            return $member->status === "HEAD";
                        })->first()->employee,
                        "submitted_date" => ($submitted_date->day . " " . $submitted_date->getTranslatedMonthName() . " " . $submitted_date->year),
                        "status" => $status,
                    ];

                    if ($research->report->approved_date) {
                        $approved_date = new Carbon();

                        $data = array_merge($data, [
                            "reviewer" => $research->report->employee,
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
                return view('dashboard.research.report.report', [
                    'page' => 'report_research_report',
                    'from' => $request->get('from') ?? '',
                    'to' => $request->get('to') ?? '',
                    'status' => $status['ID'],
                    'reports' => $reports
                ]);
            } elseif ($request->get('submit') === 'print') {
                return view('dashboard.research.report.print', [
                    'from' => $request->get('from') ?? '',
                    'to' => $request->get('to') ?? '',
                    'status' => $status['ID'],
                    'reports' => $reports
                ]);
            }
        }
    }
}
