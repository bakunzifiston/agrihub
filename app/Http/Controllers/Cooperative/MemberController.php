<?php

namespace App\Http\Controllers\Cooperative;

use App\Http\Controllers\Controller;
use App\Models\CooperativeMember;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MemberController extends Controller
{
    public function index(): View
    {
        $members = auth()->user()->cooperativeMembers()->with('farmer')->latest()->get();

        return view('cooperative.members.index', compact('members'));
    }

    public function create(): View
    {
        $farmers = User::where('tenant_type', 'farmer')->orderBy('name')->get();

        return view('cooperative.members.create', compact('farmers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'farmer_id' => ['required', 'exists:users,id'],
            'membership_number' => ['nullable', 'string', 'max:100'],
            'join_date' => ['nullable', 'date'],
            'contribution_amount' => ['nullable', 'numeric', 'min:0'],
            'role' => ['required', 'string', 'in:member,leader'],
            'status' => ['required', 'string', 'in:active,inactive,suspended'],
        ]);

        $validated['cooperative_id'] = auth()->id();
        $validated['contribution_amount'] = $validated['contribution_amount'] ?? 0;

        CooperativeMember::create($validated);

        return redirect()->route('cooperative.members.index')->with('success', 'Member added successfully.');
    }

    public function edit(CooperativeMember $member): View|RedirectResponse
    {
        if ($member->cooperative_id !== auth()->id()) {
            abort(403);
        }
        $farmers = User::where('tenant_type', 'farmer')->orderBy('name')->get();

        return view('cooperative.members.edit', compact('member', 'farmers'));
    }

    public function update(Request $request, CooperativeMember $member): RedirectResponse
    {
        if ($member->cooperative_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'farmer_id' => ['required', 'exists:users,id'],
            'membership_number' => ['nullable', 'string', 'max:100'],
            'join_date' => ['nullable', 'date'],
            'contribution_amount' => ['nullable', 'numeric', 'min:0'],
            'role' => ['required', 'string', 'in:member,leader'],
            'status' => ['required', 'string', 'in:active,inactive,suspended'],
        ]);

        $validated['contribution_amount'] = $validated['contribution_amount'] ?? 0;
        $member->update($validated);

        return redirect()->route('cooperative.members.index')->with('success', 'Member updated successfully.');
    }

    public function destroy(CooperativeMember $member): RedirectResponse
    {
        if ($member->cooperative_id !== auth()->id()) {
            abort(403);
        }
        $member->delete();

        return redirect()->route('cooperative.members.index')->with('success', 'Member removed.');
    }
}
