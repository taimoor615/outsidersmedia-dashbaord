<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientNote;
use Illuminate\Http\Request;

class ClientNotesController extends Controller
{
    /**
     * Display all notes with optional client filter.
     */
    public function index(Request $request)
    {
        $query = ClientNote::with('client')
            ->orderBy('created_at', 'desc');

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        $notes   = $query->paginate(20)->withQueryString();
        $clients = Client::orderBy('name')->get(['id', 'name']);

        return view('admin.notes.index', compact('notes', 'clients'));
    }

    /**
     * Store a new note from a team member / admin.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|integer|exists:clients,id',
            'note'      => 'required|string|min:1|max:2000',
        ]);

        // Ensure client exists (not soft-deleted)
        $client = \App\Models\Client::findOrFail($validated['client_id']);

        ClientNote::create([
            'client_id'   => $client->id,
            'added_by'    => auth()->id(),
            'author_name' => auth()->user()->name,
            'note'        => $validated['note'],
        ]);

        return back()->with('success', 'Note added successfully.');
    }

    /**
     * Update an existing note.
     */
    public function update(Request $request, ClientNote $note)
    {
        $validated = $request->validate([
            'note' => 'required|string|max:2000',
        ]);

        $note->update(['note' => $validated['note']]);

        return back()->with('success', 'Note updated successfully.');
    }

    /**
     * Delete a note (admin only).
     */
    public function destroy(ClientNote $note)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Only administrators can delete notes.');
        }

        $note->delete();

        return back()->with('success', 'Note deleted.');
    }
}
