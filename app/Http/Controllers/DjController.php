<?php
namespace App\Http\Controllers;
use App\Models\DjLineup;
use Illuminate\Http\Request;

class DjController extends Controller
{
    public function store(Request $request, $partyId)
    {
        $request->validate(['dj_name' => 'required|string|max:100', 'from' => 'required', 'till' => 'required', 'style' => 'nullable|string|max:100', 'website' => 'nullable|url|max:255']);
        DjLineup::create(array_merge($request->only('dj_name','from','till','style','website'), ['party_id' => $partyId, 'sort_order' => DjLineup::where('party_id',$partyId)->count()]));
        return back()->with('success', 'DJ eingetragen.');
    }

    public function destroy($partyId, $djId)
    {
        DjLineup::findOrFail($djId)->delete();
        return back()->with('success', 'DJ entfernt.');
    }
}
