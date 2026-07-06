<?php
namespace App\Http\Controllers;
use App\Models\Announcement;
use App\Models\Party;

class PublicController extends Controller
{
    public function index()
    {
        $party = Party::nextPublic()->with('djLineup')->first();
        $lastParty = $party ? null : Party::where('status', 'past')->with('djLineup')->orderByDesc('date')->first();
        $announcements = Announcement::active()->get();
        return view('public.index', compact('party', 'lastParty', 'announcements'));
    }

    public function party($id)
    {
        $party = Party::where('status', 'published')->with('djLineup')->findOrFail($id);
        return view('public.party', compact('party'));
    }
}
