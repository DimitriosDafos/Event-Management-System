<?php
namespace App\Http\Controllers;
use App\Models\Party;

class DashboardController extends Controller
{
    public function index()
    {
        $upcoming = Party::upcoming()->get();
        $past = Party::where('status','past')->orderByDesc('date')->limit(5)->get();
        return view('dashboard', compact('upcoming', 'past'));
    }
}
