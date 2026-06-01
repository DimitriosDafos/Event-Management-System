<?php
namespace App\Http\Controllers;
use App\Models\Income;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    public function store(Request $request, $partyId)
    {
        $request->validate(['description' => 'required|string|max:200', 'amount' => 'required|numeric|min:0', 'date' => 'required|date']);
        Income::create(array_merge($request->only('description','amount','date'), ['party_id' => $partyId, 'created_by' => auth()->id()]));
        return back()->with('success', 'Einnahme eingetragen.');
    }

    public function destroy($partyId, $iId)
    {
        Income::findOrFail($iId)->delete();
        return back()->with('success', 'Einnahme gelöscht.');
    }
}
