<?php

namespace App\Http\Controllers;

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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
                "head" => $study->members->filter(function ($member) {
                    return $member->status === "HEAD";
                })->first()->employee,
                "submitted_date" => ($submitted_date->day . " " . $submitted_date->locale('ID')->getTranslatedMonthName() . " " . $submitted_date->year),
                "status" => $study->proposal->status === "SUBMITTED" ? "Menunggu Peninjauan" : "Telah Ditinjau",
            ];
        });

        return view('dashboard.study.proposal.index', [
            'proposals' => $proposals
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $nip = auth()->user()->employee->nip;
        return view('dashboard.study.proposal.create', [
            'employees' => Employee::where('nip', '!=', $nip)->where('status', 'INTERNAL')->orderBy('name')->get()->filter(function ($employee) {
                return $employee->user->status === "EMPLOYEE";
            }),
            'researches' => Research::latest()->get()->filter(function ($research) {
                return $research->report;
            }),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'research_id' => 'required',
            'research_member' => 'required|array',
            'extensionists_member' => 'required|array',
            'proposal' => 'required',
        ]);

        if ($request->file('proposal')) {
            $validatedData['proposal'] = $request->file('proposal')->store('proposal');
        }

        $id = Study::create([
            "research_id" => $validatedData['research_id'],
            "proposal_id" => Proposal::create([
                "file" => $validatedData['proposal'],
                "submitted_date" => Carbon::now(),
                "status" => "SUBMITTED"
            ])->id
        ])->id;

        StudyMember::create([
            'employee_id' => auth()->user()->employee->id,
            'study_id' => $id,
            'status' => 'HEAD'
        ]);

        foreach ($validatedData['research_member'] as $research_member) {
            StudyMember::create([
                'employee_id' => $research_member,
                'study_id' => $id,
                'status' => 'RESEARCHER'
            ]);
        }

        foreach ($validatedData['extensionists_member'] as $extensionists_member) {
            StudyMember::create([
                'employee_id' => $extensionists_member,
                'study_id' => $id,
                'status' => 'EXTENSIONISTS'
            ]);
        }
        return redirect('/proposal-study');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Study  $study
     * @return \Illuminate\Http\Response
     */
    public function show(Study $proposal_study)
    {
        $submitted_date = new Carbon($proposal_study->proposal->submitted_date);

        return view('dashboard.study.proposal.show', [
            'proposal' =>  (object)[
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
                "submitted_date" => ($submitted_date->day . " " . $submitted_date->locale('ID')->getTranslatedMonthName() . " " . $submitted_date->year),
                "comments" => $proposal_study->proposal->comments,
                "file" => $proposal_study->proposal->file,
                "status" => $proposal_study->proposal->status,
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Study  $study
     * @return \Illuminate\Http\Response
     */
    public function edit(Study $proposal_study)
    {
        $head = $proposal_study->members->filter(function ($member) {
            return $member->status === "HEAD";
        })->first()->employee;
        return view('dashboard.study.proposal.edit', [
            'proposal' =>  (object)[
                "study_id" => $proposal_study->id,
                "research_id" => $proposal_study->research->id,
                "head" => $head,
                "study_member" => $proposal_study->members->filter(function ($member) {
                    return $member->status === "RESEARCHER";
                }),
                "extensionists_member" => $proposal_study->members->filter(function ($member) {
                    return $member->status === "EXTENSIONISTS";
                }),
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Study  $study
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Study $proposal_study)
    {
        $validatedData = $request->validate([
            'research_id' => 'required',
            'research_member' => 'required',
            'extensionists_member' => 'required',
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
        return redirect('/proposal-study');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Study  $study
     * @return \Illuminate\Http\Response
     */
    public function destroy(Study $proposal_study)
    {
        StudyMember::where('study_id', $proposal_study->id)->delete();
        Study::destroy($proposal_study->id);
        if ($proposal_study->proposal->file) Storage::delete($proposal_study->proposal->file);
        Proposal::where('id', $proposal_study->proposal_id)->delete();
        return redirect('/proposal-study');
    }

    public function approve(Request $request, Study $proposal_study)
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

        $proposalData = [
            "employee_id" => auth()->user()->employee->id,
            "approved_date" => Carbon::now(),
            "status" => $validatedData['status'],
            "comments" => $validatedData['comments']
        ];

        Proposal::where('id', $proposal_study->proposal_id)->update($proposalData);
        return redirect('/proposal-study');
    }
}
