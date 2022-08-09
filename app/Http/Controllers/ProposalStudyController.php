<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Employee;
use App\Models\Proposal;
use App\Models\Research;
use App\Models\Study;
use App\Models\StudyMember;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProposalStudyController extends Controller
{
    public function index()
    {
        if (auth()->user()->status === "ADMIN") {
            $studies = Study::latest()->get();
            $proposals = $studies->filter(function ($study) {
                return $study->proposal->status === "SUBMITTED" || $study->proposal->status === "REJECTED";
            });
        } elseif (auth()->user()->employee->status === "EXTERNAL") {
            $studies = Study::latest()->get();
            $proposals = $studies->filter(function ($study) {
                return $study->proposal->status === "SUBMITTED";
            });
        } elseif (auth()->user()->employee->status === "INTERNAL") {
            $studies = StudyMember::where('employee_id', auth()->user()->employee->id)->groupBy('study_id')->get()->map(function ($study) {
                return $study->study;
            });
            $proposals = $studies->filter(function ($study) {
                return $study->proposal->status === "SUBMITTED" || $study->proposal->status === "REJECTED";
            });
        }

        $proposals = $proposals->map(function ($study) {
            $submitted_date = new Carbon($study->proposal->submitted_date);
            return (object)[
                "study_id" => $study->id,
                "title" => $study->research->title,
                "head" => $study->members->filter(function ($member) {
                    return $member->status === "HEAD";
                })->first()->employee,
                "submitted_date" => ($submitted_date->day . " " . $submitted_date->locale('ID')->getTranslatedMonthName() . " " . $submitted_date->year),
                "status" => $study->proposal->status === "SUBMITTED" ? "Menunggu Peninjauan" : "Telah Ditinjau",
            ];
        });

        return view('dashboard.study.proposal.index', [
            'page' => 'proposal-study',
            'proposals' => $proposals
        ]);
    }

    public function create()
    {
        $nip = auth()->user()->employee->nip;
        return view('dashboard.study.proposal.create', [
            'page' => 'proposal-study',
            'employees' => Employee::where('nip', '!=', $nip)->where('status', 'INTERNAL')->orderBy('name')->get()->filter(function ($employee) {
                return $employee->user->status === "EMPLOYEE";
            }),
            'researches' => Research::latest()->get()->filter(function ($research) {
                return $research->report;
            }),
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'research_id' => 'required',
            'research_member' => 'required|array',
            'extensionists_member' => 'required|array',
            'proposal' => 'required|mimes:pdf|max:2048',
            'name' => 'required|array',
            'cost' => 'required|array',
        ], [
            "proposal.mimes" => "File Proposal harus bertipe .pdf",
            "proposal.max" => "File Proposal tidak boleh lebih dari 2MB",
        ]);


        if ($request->file('proposal')) {
            $validatedData['proposal'] = $request->file('proposal')->store('proposal');
        }

        $proposal_id = Proposal::create([
            "file" => $validatedData['proposal'],
            "submitted_date" => Carbon::now(),
            "status" => "SUBMITTED"
        ])->id;

        $study_id = Study::create([
            "research_id" => $validatedData['research_id'],
            "proposal_id" => $proposal_id
        ])->id;

        foreach ($validatedData['name'] as $index => $name) {
            if (($index + 1) != count($validatedData['name'])) {
                if (!is_null($validatedData['name'][$index]) && !is_null($validatedData['cost'][$index])) {
                    Budget::create([
                        "proposal_id" => $proposal_id,
                        "name" => $validatedData['name'][$index],
                        "cost" => $validatedData['cost'][$index],
                    ]);
                }
            }
        }

        StudyMember::create([
            'employee_id' => auth()->user()->employee->id,
            'study_id' => $study_id,
            'status' => 'HEAD'
        ]);

        foreach ($validatedData['research_member'] as $research_member) {
            StudyMember::create([
                'employee_id' => $research_member,
                'study_id' => $study_id,
                'status' => 'RESEARCHER'
            ]);
        }

        foreach ($validatedData['extensionists_member'] as $extensionists_member) {
            StudyMember::create([
                'employee_id' => $extensionists_member,
                'study_id' => $study_id,
                'status' => 'EXTENSIONISTS'
            ]);
        }
        return redirect('/proposal-study')->with('created', $study_id);
    }

    public function show(Study $proposal_study)
    {
        $submitted_date = new Carbon($proposal_study->proposal->submitted_date);

        $proposal = [
            "study_id" => $proposal_study->id,
            "title" => $proposal_study->research->title,
            "head" => $proposal_study->members->filter(function ($member) {
                return $member->status === "HEAD";
            })->first()->employee,
            "study_member" => ($proposal_study->members->filter(function ($member) {
                return $member->status === "RESEARCHER";
            }))->map(function ($member) {
                return $member->employee;
            }),
            "extensionists_member" => ($proposal_study->members->filter(function ($member) {
                return $member->status === "EXTENSIONISTS";
            }))->map(function ($member) {
                return $member->employee;
            }),
            "budgets" => $proposal_study->proposal->budgets,
            "submitted_date" => ($submitted_date->day . " " . $submitted_date->locale('ID')->getTranslatedMonthName() . " " . $submitted_date->year),
            "submitted_time" => ($submitted_date->hour . ":" . ($submitted_date->minute < 10 ? ("0" . $submitted_date->minute) : $submitted_date->minute)),
            "comments" => $proposal_study->proposal->comments,
            "file" => $proposal_study->proposal->file,
            "status" => $proposal_study->proposal->status,
        ];

        if ($proposal_study->proposal->approved_date) {
            $approved_date = new Carbon($proposal_study->proposal->approved_date);
            $proposal["approved_date"] = $approved_date->day . " " . $approved_date->getTranslatedMonthName() . " " . $approved_date->year;
            $proposal["approved_time"] = $approved_date->hour . ":" . ($approved_date->minute < 10 ? ("0" . $approved_date->minute) : $approved_date->minute);
        }

        return view('dashboard.study.proposal.show', [
            'page' => 'proposal-study',
            'proposal' =>  (object)$proposal,
        ]);
    }

    public function edit(Study $proposal_study)
    {
        $head = $proposal_study->members->filter(function ($member) {
            return $member->status === "HEAD";
        })->first()->employee;
        return view('dashboard.study.proposal.edit', [
            'page' => 'proposal-study',
            'proposal' =>  (object)[
                "study_id" => $proposal_study->id,
                "research_id" => $proposal_study->research->id,
                "head" => $head,
                "study_member" => $proposal_study->members->filter(function ($member) {
                    return $member->status === "RESEARCHER";
                })->map(function ($member) {
                    return $member->employee_id;
                }),
                "extensionists_member" => $proposal_study->members->filter(function ($member) {
                    return $member->status === "EXTENSIONISTS";
                })->map(function ($member) {
                    return $member->employee_id;
                }),
                "budgets" => $proposal_study->proposal->budgets,
                "file" => $proposal_study->proposal->file
            ],
            'employees' => Employee::where('nip', '!=', $head->nip)->where('status', 'INTERNAL')->orderBy('name')->get()->filter(function ($employee) {
                return $employee->user->status === "EMPLOYEE";
            }),
            'researches' => Research::latest()->get()->filter(function ($research) {
                return $research->report;
            }),
        ]);
    }

    public function update(Request $request, Study $proposal_study)
    {
        $validatedData = $request->validate([
            'research_id' => 'required',
            'research_member' => 'required|array',
            'extensionists_member' => 'required|array',
            'name' => 'required|array',
            'cost' => 'required|array',
            'proposal' => 'mimes:pdf|max:2048',
        ], [
            "proposal.mimes" => "File Proposal harus bertipe .pdf",
            "proposal.max" => "File Proposal tidak boleh lebih dari 2MB",
        ]);


        $proposalData = [
            "submitted_date" => Carbon::now(),
            "status" => "SUBMITTED",
            "comments" => NULL,
            "employee_id" => NULL,
            "approved_date" => NULL,
        ];

        if ($request->file('proposal')) {
            if ($proposal_study->proposal->file) Storage::delete($proposal_study->proposal->file);
            $proposalData["file"] = $request->file('proposal')->store('proposal');
        }

        Proposal::where('id', $proposal_study->proposal_id)->update($proposalData);

        Study::where('id', $proposal_study->id)->update([
            "research_id" => $validatedData['research_id'],
        ]);

        Budget::where('proposal_id', $proposal_study->proposal->id)->delete();
        foreach ($validatedData['name'] as $index => $name) {
            if (($index + 1) != count($validatedData['name'])) {
                if (!is_null($validatedData['name'][$index]) && !is_null($validatedData['cost'][$index])) {
                    Budget::create([
                        "proposal_id" => $proposal_study->proposal->id,
                        "name" => $validatedData['name'][$index],
                        "cost" => $validatedData['cost'][$index],
                    ]);
                }
            }
        }

        StudyMember::where('study_id', $proposal_study->id)->where('status', '!=', 'HEAD')->delete();
        foreach ($validatedData['research_member'] as $research_member) {
            StudyMember::create([
                'employee_id' => $research_member,
                'study_id' => $proposal_study->id,
                'status' => 'RESEARCHER'
            ]);
        }

        foreach ($validatedData['extensionists_member'] as $extensionists_member) {
            StudyMember::create([
                'employee_id' => $extensionists_member,
                'study_id' => $proposal_study->id,
                'status' => 'EXTENSIONISTS'
            ]);
        }
        return redirect('/proposal-study')->with('updated', $proposal_study->id);
    }

    public function destroy(Study $proposal_study)
    {
        Budget::where('proposal_id', $proposal_study->proposal->id)->delete();
        StudyMember::where('study_id', $proposal_study->id)->delete();
        Study::destroy($proposal_study->id);
        if ($proposal_study->proposal->file) Storage::delete($proposal_study->proposal->file);
        Proposal::where('id', $proposal_study->proposal_id)->delete();
        return redirect('/proposal-study')->with('deleted', $proposal_study->research->title);
    }

    public function approve(Request $request, Study $proposal_study)
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

        $proposalData = [
            "employee_id" => auth()->user()->employee->id,
            "approved_date" => Carbon::now(),
            "status" => $validatedData['status'],
            "comments" => $validatedData['comments']
        ];

        Proposal::where('id', $proposal_study->proposal_id)->update($proposalData);
        return redirect('/proposal-study')->with($validatedData['status'], $proposal_study->research->title);
    }

    public function report(Request $request)
    {
        if ($request->get('submit') === 'submit' || $request->get('submit') === 'reset') {
            $proposals = Study::latest()->get()->map(function ($study) {
                if ($study->proposal->status === "SUBMITTED")
                    $status = "Pengajuan";
                elseif ($study->proposal->status === "APPROVED")
                    $status = "Disetujui";
                elseif ($study->proposal->status === "REJECTED")
                    $status = "Ditolak";
                elseif ($study->proposal->status === "WAITING")
                    $status = "Menunggu Pendanaan";

                $submitted_date = new Carbon($study->proposal->submitted_date);

                $data = [
                    "title" => $study->research->title,
                    "head" => $study->members->filter(function ($member) {
                        return $member->status === "HEAD";
                    })->first()->employee,
                    "submitted_date" => ($submitted_date->day . " " . $submitted_date->getTranslatedMonthName() . " " . $submitted_date->year),
                    "status" => $status,
                ];

                if ($study->proposal->approved_date) {
                    $approved_date = new Carbon();

                    $data = array_merge($data, [
                        "reviewer" => $study->proposal->employee,
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

            return view('dashboard.study.proposal.report', [
                'page' => 'proposal_study_report',
                'proposals' => $proposals
            ]);
        } elseif ($request->get('submit') === 'filter' || $request->get('submit') === 'print') {
            if ($request->get('status') === "SUBMITTED") {
                $status['ID'] = "Pengajuan";
                $status['DB'] = "SUBMITTED";
            } else if ($request->get('status') === "WAITING") {
                $status['ID'] = "Menunggu Pendanaan";
                $status['DB'] = "WAITING";
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
                $proposals = Study::whereHas('proposal', function ($query) use ($from, $to, $status) {
                    $query->whereBetween('submitted_date', [$from, $to])->where("status", 'LIKE', '%' . $status['DB'] . '%');
                })->get()
                    ->map(function ($study) {
                        if ($study->proposal->status === "SUBMITTED")
                            $status = "Pengajuan";
                        elseif ($study->proposal->status === "APPROVED")
                            $status = "Disetujui";
                        elseif ($study->proposal->status === "REJECTED")
                            $status = "Ditolak";
                        elseif ($study->proposal->status === "WAITING")
                            $status = "Menunggu Pendanaan";

                        $submitted_date = new Carbon($study->proposal->submitted_date);

                        $data = [
                            "title" => $study->research->title,
                            "head" => $study->members->filter(function ($member) {
                                return $member->status === "HEAD";
                            })->first()->employee,
                            "submitted_date" => ($submitted_date->day . " " . $submitted_date->getTranslatedMonthName() . " " . $submitted_date->year),
                            "status" => $status,
                        ];

                        if ($study->proposal->approved_date) {
                            $approved_date = new Carbon();

                            $data = array_merge($data, [
                                "reviewer" => $study->proposal->employee,
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
                $proposals =
                    Study::whereHas('proposal', function ($query) use ($status) {
                        $query->where("status", 'LIKE', '%' . $status['DB'] . '%');
                    })->latest()->get()->map(function ($study) {
                        if ($study->proposal->status === "SUBMITTED")
                            $status = "Pengajuan";
                        elseif ($study->proposal->status === "APPROVED")
                            $status = "Disetujui";
                        elseif ($study->proposal->status === "REJECTED")
                            $status = "Ditolak";
                        elseif ($study->proposal->status === "WAITING")
                            $status = "Menunggu Pendanaan";

                        $submitted_date = new Carbon($study->proposal->submitted_date);

                        $data = [
                            "title" => $study->research->title,
                            "head" => $study->members->filter(function ($member) {
                                return $member->status === "HEAD";
                            })->first()->employee,
                            "submitted_date" => ($submitted_date->day . " " . $submitted_date->getTranslatedMonthName() . " " . $submitted_date->year),
                            "status" => $status,
                        ];

                        if ($study->proposal->approved_date) {
                            $approved_date = new Carbon();

                            $data = array_merge($data, [
                                "reviewer" => $study->proposal->employee,
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
                return view('dashboard.study.proposal.report', [
                    'page' => 'proposal_study_report',
                    'from' => $request->get('from') ?? '',
                    'to' => $request->get('to') ?? '',
                    'status' => $status['ID'],
                    'proposals' => $proposals
                ]);
            } elseif ($request->get('submit') === 'print') {
                return view('dashboard.study.proposal.print', [
                    'from' => $request->get('from') ?? '',
                    'to' => $request->get('to') ?? '',
                    'status' => $status['ID'],
                    'proposals' => $proposals
                ]);
            }
        }
    }
}
