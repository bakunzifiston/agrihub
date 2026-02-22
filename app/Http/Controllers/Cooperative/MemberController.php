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
        $user = auth()->user();
        $members = $user->cooperativeMembers()->with('farmer')->latest()->get();

        $activeMembers = $members->where('status', 'active')->count();
        $pendingMembers = $members->where('status', 'pending')->count();

        $kpis = [
            [
                'label' => 'Total Members',
                'value' => $members->count(),
                'color' => 'border-green-500',
            ],
            [
                'label' => 'Active',
                'value' => $activeMembers,
                'color' => 'border-blue-500',
            ],
            [
                'label' => 'Pending',
                'value' => $pendingMembers,
                'color' => 'border-yellow-500',
            ],
            [
                'label' => 'Inactive',
                'value' => $members->where('status', 'inactive')->count(),
                'color' => 'border-gray-400',
            ],
        ];

        return view('cooperative.members.index', compact('members', 'kpis'));
    }

    public function create(): View
    {
        $farmers = User::where('tenant_type', 'farmer')->orderBy('name')->get();

        return view('cooperative.members.create', compact('farmers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'farmer_id' => ['nullable', 'exists:users,id'],
            'full_name' => ['required', 'string', 'max:255'],
            'national_id' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
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
            'farmer_id' => ['nullable', 'exists:users,id'],
            'full_name' => ['required', 'string', 'max:255'],
            'national_id' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
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
