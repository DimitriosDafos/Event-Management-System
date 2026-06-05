<?php
namespace App\Http\Controllers;
use App\Models\BarShift;
use App\Models\Party;
use Illuminate\Http\Request;

class BarShiftController extends Controller
{
    public function store(Request $request, $partyId)
    {
        $isAdmin = auth()->user()->isAdmin();

        $rules = [
            'area'       => 'required|string|max:100',
            'sort_order' => 'required|integer|min:1|max:99',
            'from'       => 'required',
            'till'       => 'required',
        ];
        if ($isAdmin) $rules['user_id'] = 'required|exists:users,id';
        $request->validate($rules);

        $userId = $isAdmin ? $request->user_id : auth()->id();

        $count = BarShift::where('party_id', $partyId)
                         ->where('area', $request->area)
                         ->where('sort_order', $request->sort_order)
                         ->where('from', $request->from)
                         ->count();
        if ($count >= 2) return back()->withErrors(['area' => 'Dieser Slot ist bereits voll (max. 2 Personen).']);

        BarShift::create([
            'party_id'   => $partyId,
            'user_id'    => $userId,
            'area'       => $request->area,
            'sort_order' => $request->sort_order,
            'from'       => $request->from,
            'till'       => $request->till,
        ]);
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
