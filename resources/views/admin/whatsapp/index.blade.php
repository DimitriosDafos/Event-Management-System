@extends('layouts.app')
@section('title', 'WhatsApp Bot')
@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-bold">WhatsApp Bot</h1>
        <span class="text-xs px-2 py-1 rounded-full font-semibold
            {{ $status['ready'] ? 'bg-green-900/50 text-green-400 border border-green-700' : 'bg-amber-900/40 text-amber-400 border border-amber-700' }}">
            {{ $status['ready'] ? '● Verbunden' : '○ Nicht verbunden' }}
        </span>
    </div>

    @if(session('success'))
        <div class="bg-green-900/30 border border-green-700 text-green-300 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="bg-red-900/30 border border-red-700 text-red-300 px-4 py-3 rounded-lg text-sm">
            <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    {{-- QR-Code scannen --}}
    @if(!$status['ready'] && !empty($status['qr']))
    <div class="bg-surface border border-border rounded-xl p-6 text-center">
        <h2 class="font-semibold mb-1">WhatsApp verknüpfen</h2>
        <p class="text-sm text-muted mb-4">Öffne WhatsApp → Einstellungen → Verknüpfte Geräte → Gerät hinzufügen und scanne diesen QR-Code.</p>
        <div class="inline-block bg-white p-3 rounded-xl">
            <img src="{{ $status['qr'] }}" alt="QR Code" class="w-48 h-48">
        </div>
        <p class="text-xs text-muted mt-3">Seite neu laden nach dem Scannen.</p>
        <a href="{{ route('admin.whatsapp.index') }}" class="btn-primary mt-3 inline-block">Neu laden</a>
    </div>
    @elseif(!$status['ready'])
    <div class="bg-surface border border-amber-700/50 rounded-xl p-6 text-center">
        <p class="text-amber-400 font-semibold">Bot startet …</p>
        <p class="text-sm text-muted mt-1">Bitte kurz warten und dann neu laden.</p>
        <a href="{{ route('admin.whatsapp.index') }}" class="btn-primary mt-3 inline-block">Neu laden</a>
    </div>
    @endif

    {{-- Gruppen-Konfiguration --}}
    @if($status['ready'])
    <div class="bg-surface border border-border rounded-xl p-6">
        <h2 class="font-semibold mb-4">Gruppen konfigurieren</h2>
        <form method="POST" action="{{ route('admin.whatsapp.saveGroups') }}" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-muted mb-1">Testgruppe</label>
                    <select name="wa_test_group_id" class="form-input">
                        <option value="">— keine —</option>
                        @foreach($groups as $g)
                        <option value="{{ $g['id'] }}" {{ $testGroupId === $g['id'] ? 'selected' : '' }}>{{ $g['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-muted mb-1">Community-Gruppe ({{210}} Mitglieder)</label>
                    <select name="wa_community_group_id" class="form-input">
                        <option value="">— keine —</option>
                        @foreach($groups as $g)
                        <option value="{{ $g['id'] }}" {{ $communityGroupId === $g['id'] ? 'selected' : '' }}>{{ $g['name'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <button type="submit" class="btn-primary text-sm">Speichern</button>
        </form>
    </div>
    @endif

    {{-- Nachricht erstellen --}}
    @if($status['ready'])
    <div class="bg-surface border border-border rounded-xl p-6">
        <h2 class="font-semibold mb-4">Nachricht erstellen</h2>
        <form method="POST" action="{{ route('admin.whatsapp.compose') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm text-muted mb-1">Nachricht</label>
                <textarea name="text" rows="4" class="form-input" placeholder="Text der Nachricht …">{{ old('text') }}</textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-muted mb-1">Link (optional)</label>
                    <input type="url" name="link" class="form-input" placeholder="https://…" value="{{ old('link') }}">
                </div>
                <div>
                    <label class="block text-sm text-muted mb-1">Flyer (optional)</label>
                    <select name="flyer_path" class="form-input">
                        <option value="">— kein Flyer —</option>
                        @foreach($parties as $party)
                            @if($party->flyer_path)
                            <option value="{{ $party->flyer_path }}" {{ old('flyer_path') === $party->flyer_path ? 'selected' : '' }}>
                                {{ $party->name }} ({{ $party->date }})
                            </option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm text-muted mb-1">Ziel-Gruppe</label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="group_type" value="test" {{ old('group_type','test')==='test'?'checked':'' }} class="accent-primary">
                        <span class="text-sm">Testgruppe <span class="text-xs text-muted">(1 Genehmigung)</span></span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="group_type" value="community" {{ old('group_type')==='community'?'checked':'' }} class="accent-primary">
                        <span class="text-sm">Community-Gruppe <span class="text-xs text-muted">(2 Genehmigungen)</span></span>
                    </label>
                </div>
            </div>
            <button type="submit" class="btn-primary">Nachricht erstellen &amp; senden</button>
        </form>
    </div>
    @endif

    {{-- Nachrichten-Liste --}}
    <div class="bg-surface border border-border rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-border">
            <h2 class="font-semibold">Verlauf</h2>
        </div>
        @forelse($messages as $msg)
        <div class="px-6 py-4 border-b border-border last:border-0">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1 flex-wrap">
                        <span class="text-xs px-2 py-0.5 rounded-full font-semibold
                            @if($msg->status === 'sent') bg-green-900/40 text-green-400 border border-green-700
                            @elseif($msg->status === 'pending') bg-amber-900/40 text-amber-400 border border-amber-700
                            @elseif($msg->status === 'failed') bg-red-900/40 text-red-400 border border-red-700
                            @else bg-gray-800 text-muted border border-border @endif">
                            {{ ucfirst($msg->status) }}
                        </span>
                        <span class="text-xs text-muted">{{ $msg->group_type === 'community' ? '👥 Community' : '🧪 Test' }}</span>
                        <span class="text-xs text-muted">von {{ $msg->creator->name }}</span>
                        <span class="text-xs text-muted">{{ $msg->created_at->format('d.m.Y H:i') }}</span>
                    </div>

                    @if($msg->text)
                    <p class="text-sm mb-1 break-words">{{ Str::limit($msg->text, 150) }}</p>
                    @endif
                    @if($msg->link)
                    <p class="text-xs text-primary mb-1">🔗 {{ Str::limit($msg->link, 60) }}</p>
                    @endif
                    @if($msg->flyer_path)
                    <p class="text-xs text-muted mb-1">🖼 Flyer angehängt</p>
                    @endif
                    @if($msg->error)
                    <p class="text-xs text-red-400 mt-1">{{ Str::limit($msg->error, 100) }}</p>
                    @endif

                    @if($msg->status === 'pending')
                    <div class="mt-2 flex items-center gap-3">
                        <span class="text-xs text-muted">
                            Genehmigungen: {{ $msg->approvalCount() }} / {{ $msg->requiredApprovals() }}
                            @foreach($msg->approvals as $ap)
                                <span class="ml-1 text-green-400">✓ {{ $ap->user->name }}</span>
                            @endforeach
                        </span>
                        @if(!$msg->hasApprovedBy(auth()->id()))
                        <form method="POST" action="{{ route('admin.whatsapp.approve', $msg->id) }}">
                            @csrf
                            <button class="text-xs btn-primary py-1 px-3">Genehmigen</button>
                        </form>
                        @else
                        <span class="text-xs text-green-400">Du hast genehmigt</span>
                        @endif
                    </div>
                    @endif

                    @if($msg->sent_at)
                    <p class="text-xs text-muted mt-1">Gesendet: {{ $msg->sent_at->format('d.m.Y H:i') }}</p>
                    @endif
                </div>

                @if(in_array($msg->status, ['pending','failed']))
                <form method="POST" action="{{ route('admin.whatsapp.delete', $msg->id) }}">
                    @csrf @method('DELETE')
                    <button class="text-red-400 hover:text-red-300 text-xs" title="Löschen">✕</button>
                </form>
                @endif
            </div>
        </div>
        @empty
        <div class="px-6 py-8 text-center text-muted text-sm">Noch keine Nachrichten.</div>
        @endforelse
    </div>

</div>
@endsection
