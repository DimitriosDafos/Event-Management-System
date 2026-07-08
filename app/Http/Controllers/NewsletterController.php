<?php
namespace App\Http\Controllers;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    // Public: Anmeldeseite
    public function show()
    {
        return view('newsletter.signup');
    }

    // Public: Anmeldung verarbeiten
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'name'  => 'nullable|string|max:120',
        ], [
            'email.required' => 'Bitte gib deine E-Mail-Adresse ein.',
            'email.email'    => 'Bitte gib eine gültige E-Mail-Adresse ein.',
            'email.unique'   => 'Diese E-Mail-Adresse ist bereits angemeldet.',
        ]);

        $exists = NewsletterSubscriber::where('email', $request->email)->first();

        if ($exists) {
            if ($exists->active) {
                return back()->with('info', 'Diese E-Mail-Adresse ist bereits für den Newsletter angemeldet.');
            }
            $exists->update(['active' => true, 'name' => $request->name]);
            return back()->with('success', 'Du wurdest erfolgreich wieder zum Newsletter angemeldet.');
        }

        NewsletterSubscriber::create([
            'email'  => $request->email,
            'name'   => $request->name,
            'ip'     => $request->ip(),
            'active' => true,
        ]);

        return back()->with('success', 'Danke! Du wurdest erfolgreich zum Newsletter angemeldet.');
    }

    // Admin: Übersicht
    public function adminIndex(Request $request)
    {
        $query = NewsletterSubscriber::orderByDesc('created_at');

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(fn($s) => $s->where('email','like',"%$q%")->orWhere('name','like',"%$q%"));
        }

        if ($request->filter === 'active') {
            $query->where('active', true);
        } elseif ($request->filter === 'inactive') {
            $query->where('active', false);
        }

        $subscribers = $query->get();
        $total  = NewsletterSubscriber::count();
        $active = NewsletterSubscriber::where('active', true)->count();

        return view('newsletter.admin', compact('subscribers', 'total', 'active'));
    }

    // Admin: CSV-Export
    public function export(Request $request)
    {
        $ids = $request->input('ids');

        $query = NewsletterSubscriber::where('active', true)->orderBy('email');
        if ($ids) {
            $query = NewsletterSubscriber::whereIn('id', explode(',', $ids))->orderBy('email');
        }

        $rows = $query->get();

        $csv = "Name,E-Mail,Angemeldet am\n";
        foreach ($rows as $r) {
            $csv .= '"' . str_replace('"', '""', $r->name ?? '') . '",'
                  . '"' . str_replace('"', '""', $r->email) . '",'
                  . '"' . $r->created_at->format('d.m.Y H:i') . '"' . "\n";
        }

        return response($csv, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="newsletter-' . now()->format('Y-m-d') . '.csv"',
        ]);
    }

    // Admin: Abmelden / Löschen
    public function adminDestroy(int $id)
    {
        NewsletterSubscriber::findOrFail($id)->delete();
        return back()->with('success', 'Eintrag gelöscht.');
    }

    // Admin: Aktiv-Status umschalten
    public function toggleActive(int $id)
    {
        $sub = NewsletterSubscriber::findOrFail($id);
        $sub->update(['active' => !$sub->active]);
        return back()->with('success', 'Status aktualisiert.');
    }
}
