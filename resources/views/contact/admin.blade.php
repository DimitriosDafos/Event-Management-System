@extends('layouts.app')
@section('title', 'Kontaktnachrichten')

@section('content')
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.75rem; flex-wrap:wrap; gap:1rem;">
    <div style="display:flex; align-items:center; gap:.75rem;">
        <h1 class="serif" style="font-size:1.3rem; color:var(--gold);">Kontaktnachrichten</h1>
        @if($unread > 0)
            <span style="background:#c0392b; color:#fff; font-size:.72rem; font-weight:700; padding:.2rem .55rem; border-radius:99px;">{{ $unread }} neu</span>
        @endif
    </div>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-ghost btn-sm">← Admin</a>
</div>

@if(session('success'))
    <div style="background:#1a2e1a; border:1px solid #2d5a2d; border-radius:.375rem; padding:.75rem 1rem; font-size:.85rem; color:#7ec87e; margin-bottom:1.25rem;">
        {{ session('success') }}
    </div>
@endif

@if($messages->count())
    <div style="display:flex; flex-direction:column; gap:.75rem;">
        @foreach($messages as $msg)
        <div class="card" style="border-color: {{ !$msg->read ? 'rgba(212,131,42,.4)' : 'var(--border)' }};">
            <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:1rem; flex-wrap:wrap;">
                <div style="flex:1; min-width:0;">
                    <div style="display:flex; align-items:center; gap:.6rem; margin-bottom:.35rem; flex-wrap:wrap;">
                        @if(!$msg->read)
                            <span style="background:rgba(212,131,42,.2); color:var(--gold); font-size:.68rem; font-weight:700; padding:.15rem .45rem; border-radius:.25rem; letter-spacing:.04em;">NEU</span>
                        @endif
                        <span style="font-weight:600; font-size:.95rem; color:var(--text);">{{ $msg->subject }}</span>
                    </div>
                    <p style="font-size:.8rem; color:var(--muted); margin:0 0 .75rem;">
                        Von: <strong style="color:var(--text);">{{ $msg->name ?: 'Anonym' }}</strong>
                        &lt;<a href="mailto:{{ $msg->email }}" style="color:var(--gold);">{{ $msg->email }}</a>&gt;
                        · {{ $msg->created_at->format('d.m.Y · H:i') }} Uhr
                    </p>
                    <div style="background:#0f0d0a; border:1px solid var(--border); border-radius:.375rem; padding:.85rem 1rem; font-size:.875rem; color:var(--text); line-height:1.75; white-space:pre-wrap;">{{ $msg->message }}</div>
                </div>
                <div style="display:flex; flex-direction:column; gap:.4rem; flex-shrink:0;">
                    <a href="mailto:{{ $msg->email }}?subject=Re: {{ rawurlencode($msg->subject) }}" class="btn btn-gold btn-sm">
                        Antworten
                    </a>
                    @if(!$msg->read)
                        <form method="POST" action="{{ route('contact.read', $msg->id) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-ghost btn-sm" style="width:100%;">Als gelesen</button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('contact.unread', $msg->id) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-ghost btn-sm" style="width:100%; font-size:.72rem;">Als ungelesen</button>
                        </form>
                    @endif
                    <form method="POST" action="{{ route('contact.destroy', $msg->id) }}"
                          onsubmit="return confirm('Nachricht wirklich löschen?')">
                        @csrf @method('DELETE')
                        <button type="submit" style="background:none; border:none; cursor:pointer; font-size:.75rem; color:#e07070; width:100%; text-align:center; padding:.3rem 0;">
                            Löschen
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@else
    <div style="text-align:center; padding:3rem 1rem; color:var(--muted); font-size:.9rem;">
        Noch keine Kontaktnachrichten vorhanden.
    </div>
@endif
@endsection
