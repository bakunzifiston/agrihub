<?php

namespace App\Http\Controllers\Cooperative;

use App\Http\Controllers\Controller;
use App\Models\CooperativeClient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function index(): View
    {
        $clients = auth()->user()->cooperativeClients()->orderBy('name')->get();
        return view('cooperative.clients.index', compact('clients'));
    }

    public function create(): View
    {
        return view('cooperative.clients.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'client_type' => ['required', 'string', 'in:individual,shop,company,other'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'tax_id' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);
        $validated['cooperative_id'] = auth()->id();
        CooperativeClient::create($validated);
        return redirect()->route('cooperative.clients.index')->with('success', 'Client added.');
    }

    public function edit(CooperativeClient $client): View|RedirectResponse
    {
        if ((int) $client->cooperative_id !== (int) auth()->id()) {
            abort(403);
        }
        return view('cooperative.clients.edit', compact('client'));
    }

    public function update(Request $request, CooperativeClient $client): RedirectResponse
    {
        if ((int) $client->cooperative_id !== (int) auth()->id()) {
            abort(403);
        }
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'client_type' => ['required', 'string', 'in:individual,shop,company,other'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'tax_id' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);
        $client->update($validated);
        return redirect()->route('cooperative.clients.index')->with('success', 'Client updated.');
    }

    public function destroy(CooperativeClient $client): RedirectResponse
    {
        if ((int) $client->cooperative_id !== (int) auth()->id()) {
            abort(403);
        }
        $client->delete();
        return redirect()->route('cooperative.clients.index')->with('success', 'Client removed.');
    }
}
