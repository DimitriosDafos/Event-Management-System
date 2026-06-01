<?php
namespace App\Http\Controllers;
use App\Models\Party;

class PublicController extends Controller
{
    public function index()
    {
        $party = Party::nextPublic()->with('djLineup')->first();
        return view('public.index', compact('party'));
    }

    public function party($id)
    {
        $party = Party::where('status','published')->with('djLineup')->findOrFail($id);
        return view('public.party', compact('party'));
    }
}
