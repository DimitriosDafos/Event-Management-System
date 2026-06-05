<?php
namespace App\Http\Controllers;
use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function store(Request $request, $partyId)
    {
        $request->validate([
            'what'       => 'required|string|max:300',
            'due_date'   => 'nullable|date',
            'due_time'   => 'nullable',
            'user_ids'   => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $todo = Todo::create(array_merge(
            $request->only('what','due_date','due_time'),
            ['party_id' => $partyId, 'created_by' => auth()->id()]
        ));

        if ($request->filled('user_ids')) {
            $todo->users()->sync($request->input('user_ids'));
        }

        return back()->with('success', 'ToDo hinzugefügt.');
    }

    public function markDone(Request $request, $partyId, $todoId)
    {
        $todo = Todo::with('users')->findOrFail($todoId);
        if (!$todo->isAssigned(auth()->id()) && !auth()->user()->isAdmin()) abort(403);
        $todo->update(['done' => !$todo->done, 'done_at' => !$todo->done ? now() : null]);
        return back()->with('success', $todo->done ? 'Als erledigt markiert.' : 'Als offen markiert.');
    }

    public function updateCosts(Request $request, $partyId, $todoId)
    {
        $todo = Todo::with('users')->findOrFail($todoId);
        if (!$todo->isAssigned(auth()->id()) && !auth()->user()->isAdmin()) abort(403);
        $request->validate(['costs' => 'required|numeric|min:0']);
        $todo->update(['costs' => $request->costs, 'costs_entered_by' => auth()->id()]);
        return back()->with('success', 'Kosten eingetragen.');
    }

    public function adminUpdate(Request $request, $partyId, $todoId)
    {
        $todo = Todo::findOrFail($todoId);
        $request->validate([
            'what'       => 'required|string|max:300',
            'due_date'   => 'nullable|date',
            'due_time'   => 'nullable',
            'user_ids'   => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
            'costs'      => 'nullable|numeric|min:0',
        ]);
        $todo->update($request->only('what','due_date','due_time','costs'));
        $todo->users()->sync($request->input('user_ids', []));
        return back()->with('success', 'ToDo aktualisiert.');
    }

    public function destroy($partyId, $todoId)
    {
        Todo::findOrFail($todoId)->delete();
        return back()->with('success', 'ToDo gelöscht.');
    }
}
