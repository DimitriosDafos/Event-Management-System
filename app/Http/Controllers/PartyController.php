<?php
namespace App\Http\Controllers;
use App\Models\Party;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartyController extends Controller
{
    public function index()
    {
        $upcoming = Party::upcoming()->get();
        $past = Party::where('status','past')->orderByDesc('date')->get();
        return view('party.index', compact('upcoming','past'));
    }

    public function show($id)
    {
        $party = Party::with(['barShifts.user','doorAssignments.user','djLineup','todos.users','todos.createdBy','income.createdBy'])->findOrFail($id);
        if ($party->isDraft() && !auth()->user()->isAdmin()) abort(403);
        $users = \App\Models\User::where('active',true)->orderBy('name')->get();
        return view('party.show', compact('party','users'));
    }

    public function create() { return view('party.form', ['party' => null]); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'      => 'required|string|max:200',
            'date'       => 'required|date',
            'start_time' => 'required',
            'end_time'   => 'nullable',
            'location'   => 'nullable|string|max:200',
            'address'    => 'nullable|string|max:300',
            'is_special' => 'boolean',
        ]);
        $party = Party::create($data);
        return redirect()->route('parties.show', $party->id)->with('success', 'Party erstellt.');
    }

    public function edit($id)
    {
        $party = Party::findOrFail($id);
        return view('party.form', compact('party'));
    }

    public function update(Request $request, $id)
    {
        $party = Party::findOrFail($id);
        $data = $request->validate([
            'title'      => 'required|string|max:200',
            'date'       => 'required|date',
            'start_time' => 'required',
            'end_time'   => 'nullable',
            'location'   => 'nullable|string|max:200',
            'address'    => 'nullable|string|max:300',
            'is_special' => 'boolean',
        ]);
        $party->update($data);
        return redirect()->route('parties.show', $party->id)->with('success', 'Party aktualisiert.');
    }

    public function updateStatus(Request $request, $id)
    {
        $party = Party::findOrFail($id);
        $request->validate(['status' => 'required|in:draft,published,past']);
        $party->update(['status' => $request->status]);
        return back()->with('success', 'Status geändert.');
    }

    public function updateDescription(Request $request, $id)
    {
        $party = Party::findOrFail($id);
        $request->validate(['description' => 'nullable|string|max:3000']);
        $party->update(['description' => $request->description]);
        return back()->with('success', 'Beschreibung gespeichert.');
    }

    public function uploadFlyer(Request $request, $id)
    {
        $request->validate(['flyer' => 'required|image|mimes:jpeg,jpg,png,webp,gif|max:5120']);
        $party = Party::findOrFail($id);
        if ($party->flyer_path) Storage::disk('public')->delete($party->flyer_path);
        $path = $request->file('flyer')->store("flyer/{$party->id}", 'public');
        $party->update(['flyer_path' => $path]);
        return back()->with('success', 'Flyer hochgeladen.');
    }
}
