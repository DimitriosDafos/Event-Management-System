<?php
namespace App\Http\Controllers;
use App\Models\BarShift;
use App\Models\Party;
use Illuminate\Http\Request;

class BarShiftController extends Controller
{
    public function store(Request $request, $partyId)
    {
        $request->validate(['area' => 'required|string|max:100', 'from' => 'required', 'till' => 'required']);
        $party = Party::findOrFail($partyId);
        $count = BarShift::where('party_id', $partyId)->where('area', $request->area)->where('from', $request->from)->count();
        if ($count >= 2) return back()->withErrors(['area' => 'Dieser Slot ist bereits voll (max. 2 Personen).']);
        BarShift::create(['party_id' => $partyId, 'user_id' => auth()->id(), 'area' => $request->area, 'from' => $request->from, 'till' => $request->till]);
        return back()->with('success', 'Schicht eingetragen.');
    }

    public function destroy($partyId, $shiftId)
    {
        $shift = BarShift::findOrFail($shiftId);
        if ($shift->user_id !== auth()->id() && !auth()->user()->isAdmin()) abort(403);
        $shift->delete();
        return back()->with('success', 'Schicht entfernt.');
    }
}
