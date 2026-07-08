<?php
namespace App\Http\Controllers;
use App\Mail\NewsletterCampaignMail;
use App\Models\Newsletter;
use App\Models\NewsletterSend;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NewsletterCampaignController extends Controller
{
    public function index()
    {
        $newsletters = Newsletter::with('sentBy')->orderByDesc('sent_at')->get();
        return view('newsletter.campaigns.index', compact('newsletters'));
    }

    public function create()
    {
        $subscriberCount = NewsletterSubscriber::where('active', true)->count();
        return view('newsletter.campaigns.create', compact('subscriberCount'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:200',
            'body'    => 'required|string|max:20000',
        ], [
            'subject.required' => 'Bitte einen Betreff eingeben.',
            'body.required'    => 'Bitte einen Newsletter-Text eingeben.',
        ]);

        $subscribers = NewsletterSubscriber::where('active', true)->get();

        if ($subscribers->isEmpty()) {
            return back()->withErrors(['body' => 'Keine aktiven Abonnenten vorhanden.'])->withInput();
        }

        // Newsletter-Datensatz anlegen
        $newsletter = Newsletter::create([
            'subject'         => $request->subject,
            'body'            => $request->body,
            'sent_by'         => auth()->id(),
            'sent_at'         => now(),
            'recipient_count' => $subscribers->count(),
        ]);

        $failed = 0;

        foreach ($subscribers as $subscriber) {
            try {
                Mail::to($subscriber->email)
                    ->send(new NewsletterCampaignMail(
                        $request->subject,
                        $request->body,
                        $subscriber->name ?? ''
                    ));

                NewsletterSend::create([
                    'newsletter_id' => $newsletter->id,
                    'email'         => $subscriber->email,
                    'name'          => $subscriber->name,
                    'success'       => true,
                ]);
            } catch (\Exception $e) {
                $failed++;
                NewsletterSend::create([
                    'newsletter_id' => $newsletter->id,
                    'email'         => $subscriber->email,
                    'name'          => $subscriber->name,
                    'success'       => false,
                    'error'         => $e->getMessage(),
                ]);
            }
        }

        $newsletter->update(['failed_count' => $failed]);

        $successCount = $subscribers->count() - $failed;
        return redirect()->route('newsletter.campaigns.index')
            ->with('success', "Newsletter gesendet: {$successCount} erfolgreich" . ($failed > 0 ? ", {$failed} fehlgeschlagen" : '') . '.');
    }

    public function show(int $id)
    {
        $newsletter = Newsletter::with(['sentBy', 'sends'])->findOrFail($id);
        return view('newsletter.campaigns.show', compact('newsletter'));
    }
}
