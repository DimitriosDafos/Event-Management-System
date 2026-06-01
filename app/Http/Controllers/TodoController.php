<?php
namespace App\Http\Controllers;
use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function store(Request $request, $partyId)
    {
        if (!auth()->user()->isAdmin()) abort(403);
        $request->validate(['what' => 'required|string|max:300', 'due_date' => 'nullable|date', 'due_time' => 'nullable', 'assigned_to' => 'nullable|exists:users,id']);
        Todo::create(array_merge($request->only('what','due_date','due_time','assigned_to'), ['party_id' => $partyId, 'created_by' => auth()->id()]));
        return back()->with('success', 'ToDo hinzugefügt.');
    }

    public function markDone(Request $request, $partyId, $todoId)
    {
        $todo = Todo::findOrFail($todoId);
        if ($todo->assigned_to !== auth()->id() && !auth()->user()->isAdmin()) abort(403);
        $todo->update(['done' => !$todo->done, 'done_at' => !$todo->done ? now() : null]);
        return back()->with('success', $todo->done ? 'Als erledigt markiert.' : 'Als offen markiert.');
    }

    public function updateCosts(Request $request, $partyId, $todoId)
    {
        $todo = Todo::findOrFail($todoId);
        if ($todo->assigned_to !== auth()->id() && !auth()->user()->isAdmin()) abort(403);
        $request->validate(['costs' => 'required|numeric|min:0']);
        $todo->update(['costs' => $request->costs, 'costs_entered_by' => auth()->id()]);
        return back()->with('success', 'Kosten eingetragen.');
    }

    public function adminUpdate(Request $request, $partyId, $todoId)
    {
        $todo = Todo::findOrFail($todoId);
        $request->validate(['what' => 'required|string|max:300', 'due_date' => 'nullable|date', 'due_time' => 'nullable', 'assigned_to' => 'nullable|exists:users,id', 'costs' => 'nullable|numeric|min:0']);
        $todo->update($request->only('what','due_date','due_time','assigned_to','costs'));
        return back()->with('success', 'ToDo aktualisiert.');
    }

    public function destroy($partyId, $todoId)
    {
        if (!auth()->user()->isAdmin()) abort(403);
        Todo::findOrFail($todoId)->delete();
        return back()->with('success', 'ToDo gelöscht.');
    }
}
