<?php
namespace App\Http\Controllers;
use App\Models\DoorAssignment;
use Illuminate\Http\Request;

class DoorController extends Controller
{
    public function store(Request $request, $partyId)
    {
        $request->validate(['user_id' => 'required|exists:users,id', 'from' => 'required', 'till' => 'required']);
        DoorAssignment::create(['party_id' => $partyId, 'user_id' => $request->user_id, 'from' => $request->from, 'till' => $request->till]);
        return back()->with('success', 'Einlass-Einteilung gespeichert.');
    }

    public function destroy($partyId, $dId)
    {
        DoorAssignment::findOrFail($dId)->delete();
        return back()->with('success', 'Eintrag gelöscht.');
    }
}
