<?php

namespace App\Http\Controllers\Cooperative;

use App\Http\Controllers\Controller;
use App\Models\CooperativeMember;
use App\Models\ProduceCollection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CollectionController extends Controller
{
    public function index(): View
    {
        $collections = auth()->user()->produceCollections()->with(['member', 'farmer'])->latest()->get();

        return view('cooperative.collections.index', compact('collections'));
    }

    public function create(): View
    {
        $members = auth()->user()->cooperativeMembers()->get()->sortBy('display_name');

        return view('cooperative.collections.create', compact('members'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'member_id' => ['nullable', 'exists:cooperative_members,id'],
            'contributor_name' => ['nullable', 'string', 'max:255'],
            'product_name' => ['required', 'string', 'max:255'],
            'collection_date' => ['required', 'date'],
            'quantity_collected' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'quality_grade' => ['nullable', 'string', 'max:50'],
            'collection_point' => ['nullable', 'string', 'max:255'],
            'price_per_unit' => ['nullable', 'numeric', 'min:0'],
        ]);
        if (empty($validated['member_id']) && empty(trim($validated['contributor_name'] ?? ''))) {
            return back()->withErrors(['member_id' => 'Select a member or enter the farmer name.']);
        }
        if (! empty($validated['member_id']) && (int) CooperativeMember::find($validated['member_id'])->cooperative_id !== (int) auth()->id()) {
            return back()->withErrors(['member_id' => 'Invalid member.']);
        }
        $validated['member_id'] = $validated['member_id'] ?: null;
        $validated['contributor_name'] = trim($validated['contributor_name'] ?? '') ?: null;

        $validated['cooperative_id'] = auth()->id();
        if (! empty($validated['price_per_unit'])) {
            $validated['total_value'] = $validated['quantity_collected'] * $validated['price_per_unit'];
        }

        ProduceCollection::create($validated);

        return redirect()->route('cooperative.collections.index')->with('success', 'Collection recorded.');
    }

    public function edit(ProduceCollection $collection): View|RedirectResponse
    {
        if ($collection->cooperative_id !== auth()->id()) {
            abort(403);
        }
        $members = auth()->user()->cooperativeMembers()->get()->sortBy('display_name');

        return view('cooperative.collections.edit', compact('collection', 'members'));
    }

    public function update(Request $request, ProduceCollection $collection): RedirectResponse
    {
        if ($collection->cooperative_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'member_id' => ['nullable', 'exists:cooperative_members,id'],
            'contributor_name' => ['nullable', 'string', 'max:255'],
            'product_name' => ['required', 'string', 'max:255'],
            'collection_date' => ['required', 'date'],
            'quantity_collected' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'quality_grade' => ['nullable', 'string', 'max:50'],
            'collection_point' => ['nullable', 'string', 'max:255'],
            'price_per_unit' => ['nullable', 'numeric', 'min:0'],
        ]);
        if (empty($validated['member_id']) && empty(trim($validated['contributor_name'] ?? ''))) {
            return back()->withErrors(['member_id' => 'Select a member or enter the farmer name.']);
        }
        if (! empty($validated['member_id']) && (int) CooperativeMember::find($validated['member_id'])->cooperative_id !== (int) auth()->id()) {
            return back()->withErrors(['member_id' => 'Invalid member.']);
        }
        $validated['member_id'] = $validated['member_id'] ?: null;
        $validated['contributor_name'] = trim($validated['contributor_name'] ?? '') ?: null;

        if (! empty($validated['price_per_unit'])) {
            $validated['total_value'] = $validated['quantity_collected'] * $validated['price_per_unit'];
        } else {
            $validated['total_value'] = null;
        }
        $collection->update($validated);

        return redirect()->route('cooperative.collections.index')->with('success', 'Collection updated.');
    }

    public function destroy(ProduceCollection $collection): RedirectResponse
    {
        if ($collection->cooperative_id !== auth()->id()) {
            abort(403);
        }
        $collection->delete();

        return redirect()->route('cooperative.collections.index')->with('success', 'Collection removed.');
    }
}
