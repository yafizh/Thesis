<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Proposal;
use App\Models\Research;
use App\Models\ResearchMember;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProposalResearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
                "head" => $research->members->filter(function ($member) {
                    return $member->status === "HEAD";
                })->first()->employee,
                "submitted_date" => ($submitted_date->day . " " . $submitted_date->locale('ID')->getTranslatedMonthName() . " " . $submitted_date->year),
                "status" => $research->proposal->status === "SUBMITTED" ? "Menunggu Peninjauan" : "Telah Ditinjau",
            ];
        });

        return view('dashboard.research.proposal.index', [
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
        return view('dashboard.research.proposal.create', [
            'employees' => Employee::where('nip', '!=', $nip)->where('status', 'INTERNAL')->orderBy('name')->get()->filter(function ($employee) {
                return $employee->user->status === "EMPLOYEE";
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
            'title' => 'required',
            'research_member' => 'required|array',
            'extensionists_member' => 'required|array',
            'proposal' => 'required',
        ]);

        if ($request->file('proposal')) {
            $validatedData['proposal'] = $request->file('proposal')->store('proposal');
        }

        $id = Research::create([
            "title" => $validatedData['title'],
            "proposal_id" => Proposal::create([
                "file" => $validatedData['proposal'],
                "submitted_date" => Carbon::now(),
                "status" => "SUBMITTED"
            ])->id
        ])->id;

        ResearchMember::create([
            'employee_id' => auth()->user()->employee->id,
            'research_id' => $id,
            'status' => 'HEAD'
        ]);

        foreach ($validatedData['research_member'] as $research_member) {
            ResearchMember::create([
                'employee_id' => $research_member,
                'research_id' => $id,
                'status' => 'RESEARCHER'
            ]);
        }

        foreach ($validatedData['extensionists_member'] as $extensionists_member) {
            ResearchMember::create([
                'employee_id' => $extensionists_member,
                'research_id' => $id,
                'status' => 'EXTENSIONISTS'
            ]);
        }
        return redirect('/proposal-research');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Research  $research
     * @return \Illuminate\Http\Response
     */
    public function show(Research $proposal_research)
    {
        $submitted_date = new Carbon($proposal_research->proposal->submitted_date);

        return view('dashboard.research.proposal.show', [
            'proposal' =>  (object)[
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
                "submitted_date" => ($submitted_date->day . " " . $submitted_date->locale('ID')->getTranslatedMonthName() . " " . $submitted_date->year),
                "comments" => $proposal_research->proposal->comments,
                "file" => $proposal_research->proposal->file,
                "status" => $proposal_research->proposal->status,
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Research  $research
     * @return \Illuminate\Http\Response
     */
    public function edit(Research $proposal_research)
    {
        $head = $proposal_research->members->filter(function ($member) {
            return $member->status === "HEAD";
        })->first()->employee;
        return view('dashboard.research.proposal.edit', [
            'proposal' =>  (object)[
                "research_id" => $proposal_research->id,
                "title" => $proposal_research->title,
                "head" => $head,
                "research_member" => $proposal_research->members->filter(function ($member) {
                    return $member->status === "RESEARCHER";
                }),
                "extensionists_member" => $proposal_research->members->filter(function ($member) {
                    return $member->status === "EXTENSIONISTS";
                }),
                "file" => $proposal_research->proposal->file
            ],
            'employees' => Employee::where('nip', '!=', $head->nip)->where('status', 'INTERNAL')->orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Research  $research
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Research $proposal_research)
    {
        $validatedData = $request->validate([
            'title' => 'required',
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
            if ($proposal_research->proposal->file) Storage::delete($proposal_research->proposal->file);
            $proposalData["file"] = $request->file('proposal')->store('proposal');
        }

        Proposal::where('id', $proposal_research->proposal_id)->update($proposalData);

        Research::where('id', $proposal_research->id)->update([
            "title" => $validatedData['title'],
        ]);

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
        return redirect('/proposal-research');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Research  $research
     * @return \Illuminate\Http\Response
     */
    public function destroy(Research $proposal_research)
    {
        ResearchMember::where('research_id', $proposal_research->id)->delete();
        Research::destroy($proposal_research->id);
        if ($proposal_research->proposal->file) Storage::delete($proposal_research->proposal->file);
        Proposal::where('id', $proposal_research->proposal_id)->delete();
        return redirect('/proposal-research');
    }

    public function approve(Request $request, Research $proposal_research)
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

        Proposal::where('id', $proposal_research->proposal_id)->update($proposalData);
        return redirect('/proposal-research');
    }
}
