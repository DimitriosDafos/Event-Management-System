@extends('layouts.app')
@section('title', 'Newsletter-Versand')

@section('content')
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.75rem; flex-wrap:wrap; gap:1rem;">
    <h1 class="serif" style="font-size:1.3rem; color:var(--gold);">Newsletter-Versand</h1>
    <div style="display:flex; gap:.6rem;">
        <a href="{{ route('newsletter.admin') }}" class="btn btn-ghost btn-sm">Abonnenten</a>
        <a href="{{ route('newsletter.campaigns.create') }}" class="btn btn-gold btn-sm">+ Neuer Newsletter</a>
    </div>
</div>

@if(session('success'))
    <div style="background:#1a2e1a; border:1px solid #2d5a2d; border-radius:.375rem; padding:.75rem 1rem; font-size:.85rem; color:#7ec87e; margin-bottom:1.25rem;">
        {{ session('success') }}
    </div>
@endif

@if($newsletters->count())
    <div style="display:flex; flex-direction:column; gap:.75rem;">
        @foreach($newsletters as $nl)
        <div class="card" style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:.75rem;">
            <div style="flex:1; min-width:0;">
                <p style="font-weight:600; font-size:.95rem; color:var(--text); margin:0 0 .25rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                    {{ $nl->subject }}
                </p>
                <p style="font-size:.78rem; color:var(--muted); margin:0;">
                    {{ $nl->sent_at->format('d.m.Y · H:i') }} Uhr ·
                    gesendet von {{ $nl->sentBy->name ?? '—' }}
                </p>
            </div>
            <div style="display:flex; align-items:center; gap:1.25rem; flex-shrink:0;">
                <div style="text-align:center;">
                    <div style="font-size:1.1rem; font-weight:700; color:#7ec87e;">
                        {{ $nl->recipient_count - $nl->failed_count }}
                    </div>
                    <div style="font-size:.68rem; color:var(--muted); text-transform:uppercase; letter-spacing:.05em;">Erfolgreich</div>
                </div>
                @if($nl->failed_count > 0)
                <div style="text-align:center;">
                    <div style="font-size:1.1rem; font-weight:700; color:#e07070;">{{ $nl->failed_count }}</div>
                    <div style="font-size:.68rem; color:var(--muted); text-transform:uppercase; letter-spacing:.05em;">Fehlschläge</div>
                </div>
                @endif
                <a href="{{ route('newsletter.campaigns.show', $nl->id) }}" class="btn btn-ghost btn-sm">Details</a>
            </div>
        </div>
        @endforeach
    </div>
@else
    <div style="text-align:center; padding:3rem 1rem; color:var(--muted); font-size:.9rem;">
        Noch kein Newsletter versendet.<br>
        <a href="{{ route('newsletter.campaigns.create') }}" style="color:var(--gold); margin-top:.5rem; display:inline-block;">Jetzt ersten Newsletter erstellen →</a>
    </div>
@endif
@endsection
