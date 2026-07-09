<?php
namespace App\Http\Controllers;
use App\Mail\ContactFormMail;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function show()
    {
        return view('contact.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => 'nullable|string|max:120',
            'email'   => 'required|email:rfc,dns|max:255',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|min:10|max:5000',
        ], [
            'email.required'   => 'Bitte gib deine E-Mail-Adresse an.',
            'email.email'      => 'Bitte gib eine gültige E-Mail-Adresse ein.',
            'subject.required' => 'Bitte gib einen Betreff ein.',
            'message.required' => 'Bitte schreib eine Nachricht.',
            'message.min'      => 'Die Nachricht muss mindestens 10 Zeichen lang sein.',
        ]);

        // Schadcode-Schutz: HTML-Tags entfernen
        $data['name']    = $data['name']    ? strip_tags($data['name'])    : null;
        $data['subject'] = strip_tags($data['subject']);
        $data['message'] = strip_tags($data['message']);
        $data['ip']      = $request->ip();

        $contact = ContactMessage::create($data);

        // E-Mail an disclosure-kontakt@dafos.eu
        try {
            Mail::to('disclosure-kontakt@dafos.eu')
                ->send(new ContactFormMail($contact));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Kontaktformular-Mail fehlgeschlagen: ' . $e->getMessage());
        }

        return redirect('/')->with('contact_success', 'Deine Nachricht wurde gesendet. Wir melden uns bald bei dir!');
    }

    // Admin
    public function adminIndex()
    {
        $messages = ContactMessage::orderByDesc('created_at')->get();
        $unread   = ContactMessage::unread()->count();
        return view('contact.admin', compact('messages', 'unread'));
    }

    public function markRead(int $id)
    {
        ContactMessage::findOrFail($id)->update(['read' => true]);
        return back();
    }

    public function markUnread(int $id)
    {
        ContactMessage::findOrFail($id)->update(['read' => false]);
        return back();
    }

    public function adminDestroy(int $id)
    {
        ContactMessage::findOrFail($id)->delete();
        return back()->with('success', 'Nachricht gelöscht.');
    }
}
