<?php
namespace App\Http\Controllers;
use App\Models\DjLineup;
use Illuminate\Http\Request;

class DjController extends Controller
{
    public function store(Request $request, $partyId)
    {
        $request->validate([
            'dj_name'    => 'required|string|max:100',
            'sort_order' => 'required|integer|min:1|max:99',
            'from'       => 'required',
            'till'       => 'required',
            'style'      => 'nullable|string|max:100',
            'website'    => 'nullable|url|max:255',
        ]);
        DjLineup::create([
            'party_id'   => $partyId,
            'dj_name'    => $request->dj_name,
            'sort_order' => $request->sort_order,
            'from'       => $request->from,
            'till'       => $request->till,
            'style'      => $request->style,
            'website'    => $request->website,
        ]);
        return back()->with('success', 'DJ eingetragen.');
    }

    public function destroy($partyId, $djId)
    {
        DjLineup::findOrFail($djId)->delete();
        return back()->with('success', 'DJ entfernt.');
    }
}
