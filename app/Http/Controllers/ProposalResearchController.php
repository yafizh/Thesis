<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Employee;
use App\Models\Proposal;
use App\Models\Research;
use App\Models\ResearchMember;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProposalResearchController extends Controller
{
    public function index()
    {
        if (auth()->user()->status === "ADMIN") {
            $researches = Research::latest()->get();
            $proposals = $researches->filter(function ($research) {
                return $research->proposal->status === "SUBMITTED" || $research->proposal->status === "REJECTED";
            });
        } elseif (auth()->user()->employee->status === "EXTERNAL") {
            $researches = Research::latest()->get();
            $proposals = $researches->filter(function ($research) {
                return $research->proposal->status === "SUBMITTED";
            });
        } elseif (auth()->user()->employee->status === "INTERNAL") {
            $researches = ResearchMember::where('employee_id', auth()->user()->employee->id)->groupBy('research_id')->get()->map(function ($research) {
                return $research->research;
            });
            $proposals = $researches->filter(function ($research) {
                return $research->proposal->status === "SUBMITTED" || $research->proposal->status === "REJECTED";
            });
        }

        $proposals = $proposals->map(function ($research) {
            $submitted_date = new Carbon($research->proposal->submitted_date);
            return (object)[
                "research_id" => $research->id,
                "title" => $research->title,
                "head" => $research->members->filter(function ($member) {
                    return $member->status === "HEAD";
                })->first()->employee,
                "submitted_date" => ($submitted_date->day . " " . $submitted_date->getTranslatedMonthName() . " " . $submitted_date->year),
                "status" => $research->proposal->status === "SUBMITTED" ? "Menunggu Peninjauan" : "Telah Ditinjau",
            ];
        });

        return view('dashboard.research.proposal.index', [
            'page' => 'proposal-research',
            'proposals' => $proposals
        ]);
    }

    public function create()
    {
        $nip = auth()->user()->employee->nip;
        return view('dashboard.research.proposal.create', [
            'page' => 'proposal-research',
            'employees' => Employee::where('nip', '!=', $nip)->where('status', 'INTERNAL')->orderBy('name')->get()->filter(function ($employee) {
                return $employee->user->status === "EMPLOYEE";
            }),
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required',
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
        $research_id = Research::create([
            "title" => $validatedData['title'],
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

        ResearchMember::create([
            'employee_id' => auth()->user()->employee->id,
            'research_id' => $research_id,
            'status' => 'HEAD'
        ]);

        foreach ($validatedData['research_member'] as $research_member) {
            ResearchMember::create([
                'employee_id' => $research_member,
                'research_id' => $research_id,
                'status' => 'RESEARCHER'
            ]);
        }

        foreach ($validatedData['extensionists_member'] as $extensionists_member) {
            ResearchMember::create([
                'employee_id' => $extensionists_member,
                'research_id' => $research_id,
                'status' => 'EXTENSIONISTS'
            ]);
        }

        return redirect('/proposal-research')->with('created', $research_id);
    }

    public function show(Research $proposal_research)
    {
        $submitted_date = new Carbon($proposal_research->proposal->submitted_date);

        $proposal = [
            "research_id" => $proposal_research->id,
            "title" => $proposal_research->title,
            "head" => $proposal_research->members->filter(function ($member) {
                return $member->status === "HEAD";
            })->first()->employee,
            "research_member" => ($proposal_research->members->filter(function ($member) {
                return $member->status === "RESEARCHER";
            }))->map(function ($member) {
                return $member->employee;
            }),
            "extensionists_member" => ($proposal_research->members->filter(function ($member) {
                return $member->status === "EXTENSIONISTS";
            }))->map(function ($member) {
                return $member->employee;
            }),
            "budgets" => $proposal_research->proposal->budgets,
            "submitted_date" => ($submitted_date->day . " " . $submitted_date->getTranslatedMonthName() . " " . $submitted_date->year),
            "submitted_time" => ($submitted_date->hour . ":" . ($submitted_date->minute < 10 ? ("0" . $submitted_date->minute) : $submitted_date->minute)),
            "comments" => $proposal_research->proposal->comments,
            "file" => $proposal_research->proposal->file,
            "status" => $proposal_research->proposal->status,
        ];

        if ($proposal_research->proposal->approved_date) {
            $approved_date = new Carbon($proposal_research->proposal->approved_date);
            $proposal["approved_date"] = $approved_date->day . " " . $approved_date->getTranslatedMonthName() . " " . $approved_date->year;
            $proposal["approved_time"] = $approved_date->hour . ":" . ($approved_date->minute < 10 ? ("0" . $approved_date->minute) : $approved_date->minute);
        }


        return view('dashboard.research.proposal.show', [
            'page' => 'proposal-research',
            'proposal' => (object)$proposal,
        ]);
    }

    public function edit(Research $proposal_research)
    {
        $head = $proposal_research->members->filter(function ($member) {
            return $member->status === "HEAD";
        })->first()->employee;
        return view('dashboard.research.proposal.edit', [
            'page' => 'proposal-research',
            'proposal' =>  (object)[
                "research_id" => $proposal_research->id,
                "title" => $proposal_research->title,
                "head" => $head,
                "research_member" => $proposal_research->members->filter(function ($member) {
                    return $member->status === "RESEARCHER";
                })->map(function ($member) {
                    return $member->employee_id;
                }),
                "extensionists_member" => $proposal_research->members->filter(function ($member) {
                    return $member->status === "EXTENSIONISTS";
                })->map(function ($member) {
                    return $member->employee_id;
                }),
                "budgets" => $proposal_research->proposal->budgets,
                "file" => $proposal_research->proposal->file
            ],
            'employees' => Employee::where('nip', '!=', $head->nip)->where('status', 'INTERNAL')->orderBy('name')->get()->filter(function ($employee) {
                return $employee->user->status === "EMPLOYEE";
            }),
        ]);
    }

    public function update(Request $request, Research $proposal_research)
    {
        $validatedData = $request->validate([
            'title' => 'required',
            'research_member' => 'required|array',
            'extensionists_member' => 'required|array',
            'name' => 'required|array',
            'cost' => 'required|array',
            'proposal' => 'mimes:pdf|size:2048'
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
            if ($proposal_research->proposal->file) Storage::delete($proposal_research->proposal->file);
            $proposalData["file"] = $request->file('proposal')->store('proposal');
        }

        Proposal::where('id', $proposal_research->proposal_id)->update($proposalData);

        Research::where('id', $proposal_research->id)->update([
            "title" => $validatedData['title'],
        ]);


        Budget::where('proposal_id', $proposal_research->proposal->id)->delete();
        foreach ($validatedData['name'] as $index => $name) {
            if (($index + 1) != count($validatedData['name'])) {
                if (!is_null($validatedData['name'][$index]) && !is_null($validatedData['cost'][$index])) {
                    Budget::create([
                        "proposal_id" => $proposal_research->proposal->id,
                        "name" => $validatedData['name'][$index],
                        "cost" => $validatedData['cost'][$index],
                    ]);
                }
            }
        }

        ResearchMember::where('research_id', $proposal_research->id)->where('status', '!=', 'HEAD')->delete();
        foreach ($validatedData['research_member'] as $research_member) {
            ResearchMember::create([
                'employee_id' => $research_member,
                'research_id' => $proposal_research->id,
                'status' => 'RESEARCHER'
            ]);
        }

        foreach ($validatedData['extensionists_member'] as $extensionists_member) {
            ResearchMember::create([
                'employee_id' => $extensionists_member,
                'research_id' => $proposal_research->id,
                'status' => 'EXTENSIONISTS'
            ]);
        }

        return redirect('/proposal-research')->with('updated', $proposal_research->id);
    }

    public function destroy(Research $proposal_research)
    {
        Budget::where('proposal_id', $proposal_research->proposal->id)->delete();
        ResearchMember::where('research_id', $proposal_research->id)->delete();
        Research::destroy($proposal_research->id);
        if ($proposal_research->proposal->file) Storage::delete($proposal_research->proposal->file);
        Proposal::where('id', $proposal_research->proposal_id)->delete();
        return redirect('/proposal-research')->with("deleted", $proposal_research->title);
    }

    public function approve(Request $request, Research $proposal_research)
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

        Proposal::where('id', $proposal_research->proposal_id)->update($proposalData);
        return redirect('/proposal-research')->with($validatedData['status'], $proposal_research->title);
    }

    public function report(Request $request)
    {
        if ($request->get('submit') === 'submit' || $request->get('submit') === 'reset') {
            $proposals = Research::latest()->get()->map(function ($research) {
                if ($research->proposal->status === "SUBMITTED")
                    $status = "Pengajuan";
                elseif ($research->proposal->status === "APPROVED")
                    $status = "Disetujui";
                elseif ($research->proposal->status === "REJECTED")
                    $status = "Ditolak";
                elseif ($research->proposal->status === "WAITING")
                    $status = "Menunggu Pendanaan";

                $submitted_date = new Carbon($research->proposal->submitted_date);

                $data = [
                    "title" => $research->title,
                    "head" => $research->members->filter(function ($member) {
                        return $member->status === "HEAD";
                    })->first()->employee,
                    "submitted_date" => ($submitted_date->day . " " . $submitted_date->getTranslatedMonthName() . " " . $submitted_date->year),
                    "status" => $status,
                ];

                if ($research->proposal->approved_date) {
                    $approved_date = new Carbon();

                    $data = array_merge($data, [
                        "reviewer" => $research->proposal->employee,
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

            return view('dashboard.research.proposal.report', [
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
                $proposals =
                    Research::whereBetween('submitted_date', [$request->get('from'), $request->get('to')])
                    ->whereHas('proposal', function ($query) use ($status) {
                        $query->where("status", 'LIKE', '%' . $status['DB'] . '%');
                    })
                    ->get()
                    ->map(function ($research) {
                        if ($research->proposal->status === "SUBMITTED")
                            $status = "Pengajuan";
                        elseif ($research->proposal->status === "APPROVED")
                            $status = "Disetujui";
                        elseif ($research->proposal->status === "REJECTED")
                            $status = "Ditolak";
                        elseif ($research->proposal->status === "WAITING")
                            $status = "Menunggu Pendanaan";

                        $submitted_date = new Carbon($research->proposal->submitted_date);

                        $data = [
                            "title" => $research->title,
                            "head" => $research->members->filter(function ($member) {
                                return $member->status === "HEAD";
                            })->first()->employee,
                            "submitted_date" => ($submitted_date->day . " " . $submitted_date->getTranslatedMonthName() . " " . $submitted_date->year),
                            "status" => $status,
                        ];

                        if ($research->proposal->approved_date) {
                            $approved_date = new Carbon();

                            $data = array_merge($data, [
                                "reviewer" => $research->proposal->employee,
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
                    Research::whereHas('proposal', function ($query) use ($status) {
                        $query->where("status", 'LIKE', '%' . $status['DB'] . '%');
                    })->latest()->get()->map(function ($research) {
                        if ($research->proposal->status === "SUBMITTED")
                            $status = "Pengajuan";
                        elseif ($research->proposal->status === "APPROVED")
                            $status = "Disetujui";
                        elseif ($research->proposal->status === "REJECTED")
                            $status = "Ditolak";
                        elseif ($research->proposal->status === "WAITING")
                            $status = "Menunggu Pendanaan";

                        $submitted_date = new Carbon($research->proposal->submitted_date);

                        $data = [
                            "title" => $research->title,
                            "head" => $research->members->filter(function ($member) {
                                return $member->status === "HEAD";
                            })->first()->employee,
                            "submitted_date" => ($submitted_date->day . " " . $submitted_date->getTranslatedMonthName() . " " . $submitted_date->year),
                            "status" => $status,
                        ];

                        if ($research->proposal->approved_date) {
                            $approved_date = new Carbon();

                            $data = array_merge($data, [
                                "reviewer" => $research->proposal->employee,
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
                return view('dashboard.research.proposal.report', [
                    'from' => $request->get('from') ?? '',
                    'to' => $request->get('to') ?? '',
                    'status' => $status['ID'],
                    'proposals' => $proposals
                ]);
            } elseif ($request->get('submit') === 'print') {
                return view('dashboard.research.proposal.print', [
                    'from' => $request->get('from') ?? '',
                    'to' => $request->get('to') ?? '',
                    'status' => $status['ID'],
                    'proposals' => $proposals
                ]);
            }
        }
    }
}
