<?php
namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Party;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    public function store(Request $request, int $partyId)
    {
        $request->validate([
            'artikel'     => 'required|string|max:255',
            'menge'       => 'required|integer|min:1|max:9999',
            'kabeltyp'    => 'nullable|string|max:255',
            'kabellaenge' => 'nullable|numeric|min:0|max:9999',
        ]);

        $party = Party::findOrFail($partyId);

        $maxOrder = $party->equipment()->max('sort_order') ?? 0;

        $party->equipment()->create([
            'artikel'     => $request->artikel,
            'menge'       => $request->menge,
            'kabeltyp'    => $request->kabeltyp,
            'kabellaenge' => $request->kabellaenge,
            'sort_order'  => $maxOrder + 1,
        ]);

        return back()->with('equipment_success', 'Artikel hinzugefügt.');
    }

    public function update(Request $request, int $partyId, int $itemId)
    {
        $request->validate([
            'artikel'     => 'required|string|max:255',
            'menge'       => 'required|integer|min:1|max:9999',
            'kabeltyp'    => 'nullable|string|max:255',
            'kabellaenge' => 'nullable|numeric|min:0|max:9999',
        ]);

        $item = Equipment::where('party_id', $partyId)->findOrFail($itemId);
        $item->update($request->only(['artikel','menge','kabeltyp','kabellaenge']));

        return back()->with('equipment_success', 'Artikel aktualisiert.');
    }

    public function destroy(int $partyId, int $itemId)
    {
        Equipment::where('party_id', $partyId)->findOrFail($itemId)->delete();
        return back()->with('equipment_success', 'Artikel entfernt.');
    }
}
