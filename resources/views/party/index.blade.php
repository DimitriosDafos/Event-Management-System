@extends('layouts.app')
@section('title', 'Partys')

@section('content')
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem;">
    <h1 class="serif" style="font-size:1.3rem; color:var(--gold);">Partys</h1>
    @if(auth()->user()->isAdmin())
        <a href="{{ route('parties.create') }}" class="btn btn-gold">+ Neue Party</a>
    @endif
</div>

<!-- Kommend / Entwürfe -->
<div style="margin-bottom:2.5rem;">
    <h2 style="font-size:.72rem; text-transform:uppercase; letter-spacing:.08em; color:var(--muted); margin-bottom:.9rem;">Aktuell &amp; Geplant</h2>

    @forelse($upcoming as $party)
        <div class="card" style="margin-bottom:.7rem; display:flex; align-items:center; justify-content:space-between;">
            <div style="display:flex; align-items:center; gap:1.25rem;">
                <div style="background:var(--border); border-radius:.375rem; padding:.45rem .65rem; text-align:center; min-width:50px;">
                    <div style="color:var(--gold); font-size:.6rem; text-transform:uppercase;">{{ $party->date->format('M') }}</div>
                    <div class="serif" style="font-size:1.15rem; font-weight:700;">{{ $party->date->format('d') }}</div>
                    <div style="color:var(--muted); font-size:.6rem;">{{ $party->date->format('Y') }}</div>
                </div>
                <div>
                    <div style="font-weight:600; margin-bottom:.15rem;">{{ $party->title }}</div>
                    <div class="text-muted" style="font-size:.77rem;">
                        {{ $party->date->format('D') }}, {{ $party->start_time }}
                        @if($party->end_time) &ndash; {{ $party->end_time }} @endif
                        @if($party->location) &middot; {{ $party->location }} @endif
                        @if($party->is_special) <span class="badge badge-red ml-1">Special Event</span> @endif
                    </div>
                </div>
            </div>
            <div style="display:flex; align-items:center; gap:.75rem;">
                @if($party->isDraft())
                    <span class="badge badge-muted">Entwurf</span>
                @elseif($party->isPublished())
                    <span class="badge badge-gold">Veröffentlicht</span>
                @endif
                <a href="{{ route('parties.show', $party->id) }}" class="btn btn-ghost btn-sm">Öffnen</a>
            </div>
        </div>
    @empty
        <div class="card text-muted" style="font-size:.85rem; text-align:center; padding:2rem;">
            Keine Partys geplant.
        </div>
    @endforelse
</div>

<!-- Vergangen -->
@if($past->count())
<div>
    <h2 style="font-size:.72rem; text-transform:uppercase; letter-spacing:.08em; color:var(--muted); margin-bottom:.9rem;">Archiv — Vergangene Partys</h2>
    @foreach($past as $party)
        <div style="display:flex; align-items:center; justify-content:space-between; padding:.55rem .75rem; border:1px solid var(--border); border-radius:.25rem; margin-bottom:.4rem; opacity:.7;">
            <div style="display:flex; align-items:center; gap:1rem;">
                <span class="text-muted" style="font-size:.78rem; min-width:70px;">{{ $party->date->format('d.m.Y') }}</span>
                <span style="font-size:.85rem;">{{ $party->title }}</span>
            </div>
            <a href="{{ route('parties.show', $party->id) }}" class="btn btn-ghost btn-xs">Ansehen</a>
        </div>
    @endforeach
</div>
@endif
@endsection
