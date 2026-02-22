<?php

namespace App\Http\Controllers\Cooperative;

use App\Http\Controllers\Controller;
use App\Models\CooperativeWarehouse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WarehouseController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $warehouses = $user->cooperativeWarehouses()->withCount('inventory')->with('managerMember')->latest()->get();

        $totalCapacity = $warehouses->sum('storage_capacity');
        $totalItems = $warehouses->sum('inventory_count');

        $kpis = [
            [
                'label' => 'Warehouses',
                'value' => $warehouses->count(),
                'color' => 'border-green-500',
            ],
            [
                'label' => 'Total Capacity',
                'value' => number_format($totalCapacity, 0),
                'color' => 'border-blue-500',
            ],
            [
                'label' => 'Inventory Items',
                'value' => $totalItems,
                'color' => 'border-purple-500',
            ],
            [
                'label' => 'Active',
                'value' => $warehouses->where('status', 'active')->count(),
                'color' => 'border-yellow-500',
            ],
        ];

        return view('cooperative.warehouses.index', compact('warehouses', 'kpis'));
    }

    public function create(): View
    {
        $members = auth()->user()->cooperativeMembers()->get()->sortBy('display_name');

        return view('cooperative.warehouses.create', compact('members'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'city' => ['nullable', 'string', 'max:100'],
            'district' => ['nullable', 'string', 'max:100'],
            'sector' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'gps_latitude' => ['nullable', 'numeric'],
            'gps_longitude' => ['nullable', 'numeric'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'manager_member_id' => ['nullable', 'exists:cooperative_members,id'],
            'manager_name' => ['nullable', 'string', 'max:255'],
        ]);
        if (! empty($validated['manager_member_id'])) {
            $member = \App\Models\CooperativeMember::find($validated['manager_member_id']);
            if ($member && (int) $member->cooperative_id !== (int) auth()->id()) {
                return back()->withErrors(['manager_member_id' => 'Invalid member.']);
            }
        }
        $validated['cooperative_id'] = auth()->id();
        $validated['manager_member_id'] = $validated['manager_member_id'] ?: null;
        $validated['manager_name'] = trim($validated['manager_name'] ?? '') ?: null;

        CooperativeWarehouse::create($validated);

        return redirect()->route('cooperative.warehouses.index')->with('success', 'Warehouse added.');
    }

    public function edit(CooperativeWarehouse $warehouse): View|RedirectResponse
    {
        if ($warehouse->cooperative_id !== auth()->id()) {
            abort(403);
        }
        $members = auth()->user()->cooperativeMembers()->get()->sortBy('display_name');

        return view('cooperative.warehouses.edit', compact('warehouse', 'members'));
    }

    public function update(Request $request, CooperativeWarehouse $warehouse): RedirectResponse
    {
        if ($warehouse->cooperative_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'city' => ['nullable', 'string', 'max:100'],
            'district' => ['nullable', 'string', 'max:100'],
            'sector' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'gps_latitude' => ['nullable', 'numeric'],
            'gps_longitude' => ['nullable', 'numeric'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'manager_member_id' => ['nullable', 'exists:cooperative_members,id'],
            'manager_name' => ['nullable', 'string', 'max:255'],
        ]);
        if (! empty($validated['manager_member_id'])) {
            $member = \App\Models\CooperativeMember::find($validated['manager_member_id']);
            if ($member && (int) $member->cooperative_id !== (int) auth()->id()) {
                return back()->withErrors(['manager_member_id' => 'Invalid member.']);
            }
        }
        $validated['manager_member_id'] = $validated['manager_member_id'] ?: null;
        $validated['manager_name'] = trim($validated['manager_name'] ?? '') ?: null;

        $warehouse->update($validated);

        return redirect()->route('cooperative.warehouses.index')->with('success', 'Warehouse updated.');
    }

    public function destroy(CooperativeWarehouse $warehouse): RedirectResponse
    {
        if ($warehouse->cooperative_id !== auth()->id()) {
            abort(403);
        }
        $warehouse->delete();

        return redirect()->route('cooperative.warehouses.index')->with('success', 'Warehouse removed.');
    }
}
