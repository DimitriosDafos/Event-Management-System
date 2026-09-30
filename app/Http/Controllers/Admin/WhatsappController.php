<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Party;
use App\Models\WhatsappApproval;
use App\Models\WhatsappMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WhatsappController extends Controller
{
    private function bot(string $method, string $endpoint, array $data = [])
    {
        $req = Http::withHeaders(['x-bot-token' => config('services.whatsapp_bot.token')])
                   ->timeout(10);
        return $method === 'GET'
            ? $req->get(config('services.whatsapp_bot.url') . $endpoint)
            : $req->post(config('services.whatsapp_bot.url') . $endpoint, $data);
    }

    public function index()
    {
        $status = rescue(fn() => $this->bot('GET', '/status')->json(), ['ready' => false, 'qr' => null]);
        $groups = rescue(fn() => $this->bot('GET', '/groups')->json()['groups'] ?? [], []);

        $messages = WhatsappMessage::with(['creator','approvals.user'])
            ->orderByDesc('created_at')->get();

        $testGroupId      = \App\Models\Setting::get('wa_test_group_id');
        $communityGroupId = \App\Models\Setting::get('wa_community_group_id');

        $parties = Party::orderByDesc('date')->limit(10)->get();

        return view('admin.whatsapp.index', compact(
            'status','groups','messages','testGroupId','communityGroupId','parties'
        ));
    }

    public function saveGroups(Request $request)
    {
        $request->validate([
            'wa_test_group_id'      => 'nullable|string',
            'wa_community_group_id' => 'nullable|string',
        ]);
        \App\Models\Setting::set('wa_test_group_id',      $request->wa_test_group_id);
        \App\Models\Setting::set('wa_community_group_id', $request->wa_community_group_id);
        return back()->with('success', 'Gruppen gespeichert.');
    }

    public function compose(Request $request)
    {
        $request->validate([
            'text'       => 'nullable|string|max:3000',
            'link'       => 'nullable|url|max:500',
            'flyer_path' => 'nullable|string',
            'video_url'  => 'nullable|url|max:500',
            'group_type' => 'required|in:test,community',
        ], [], ['text' => 'Nachricht', 'link' => 'Link', 'group_type' => 'Gruppe']);

        if (empty($request->text) && empty($request->link) && empty($request->flyer_path) && empty($request->video_url)) {
            return back()->withErrors(['text' => 'Mindestens Text, Link, Flyer oder Video angeben.']);
        }

        $groupId = $request->group_type === 'community'
            ? \App\Models\Setting::get('wa_community_group_id')
            : \App\Models\Setting::get('wa_test_group_id');

        if (!$groupId) {
            return back()->withErrors(['text' => 'Gruppe noch nicht konfiguriert. Bitte zuerst Gruppe auswählen.']);
        }

        $msg = WhatsappMessage::create([
            'created_by' => auth()->id(),
            'text'       => $request->text,
            'link'       => $request->link,
            'flyer_path' => $request->flyer_path,
            'video_url'  => $request->video_url,
            'group_type' => $request->group_type,
            'group_id'   => $groupId,
            'status'     => 'pending',
        ]);

        // Creator auto-approves
        WhatsappApproval::create([
            'message_id'  => $msg->id,
            'user_id'     => auth()->id(),
            'approved_at' => now(),
        ]);

        // If test group (needs 1 approval) OR community but fully approved → send
        if ($msg->isFullyApproved()) {
            $this->sendNow($msg);
        }

        return redirect()->route('admin.whatsapp.index')
            ->with('success', $msg->status === 'sent'
                ? 'Nachricht gesendet!'
                : 'Nachricht erstellt — warte auf zweite Genehmigung.');
    }

    public function approve(int $id)
    {
        $msg = WhatsappMessage::findOrFail($id);

        if ($msg->status !== 'pending') {
            return back()->withErrors(['whatsapp' => 'Nachricht ist nicht mehr ausstehend.']);
        }

        if ($msg->hasApprovedBy(auth()->id())) {
            return back()->with('success', 'Du hast diese Nachricht bereits genehmigt.');
        }

        WhatsappApproval::create([
            'message_id'  => $msg->id,
            'user_id'     => auth()->id(),
            'approved_at' => now(),
        ]);

        $msg->refresh();
        if ($msg->isFullyApproved()) {
            $this->sendNow($msg);
        }

        return redirect()->route('admin.whatsapp.index')
            ->with('success', $msg->status === 'sent' ? 'Nachricht gesendet!' : 'Genehmigung gespeichert.');
    }

    public function delete(int $id)
    {
        $msg = WhatsappMessage::findOrFail($id);
        if (!in_array($msg->status, ['pending','failed'])) {
            return back()->withErrors(['whatsapp' => 'Gesendete Nachrichten können nicht gelöscht werden.']);
        }
        $msg->delete();
        return back()->with('success', 'Nachricht gelöscht.');
    }

    private function sendNow(WhatsappMessage $msg): void
    {
        try {
            $payload = ['groupId' => $msg->group_id];

            $text = $msg->text ?? '';
            if ($msg->link) {
                $text .= ($text ? "\n\n" : '') . $msg->link;
            }
            if ($text) $payload['text'] = $text;

            if ($msg->flyer_path) {
                $abs = public_path('storage/' . ltrim($msg->flyer_path, '/'));
                if (file_exists($abs)) $payload['mediaPath'] = $abs;
            }

            $resp = $this->bot('POST', '/send', $payload);

            if ($resp->successful() && ($resp->json()['success'] ?? false)) {
                $msg->update(['status' => 'sent', 'sent_at' => now(), 'error' => null]);
            } else {
                $msg->update(['status' => 'failed', 'error' => $resp->body()]);
            }
        } catch (\Throwable $e) {
            $msg->update(['status' => 'failed', 'error' => $e->getMessage()]);
        }
    }
}
