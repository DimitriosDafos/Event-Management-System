@extends('layouts.app')
@section('title', 'Übersicht')

@section('content')
<div style="margin-bottom:1.5rem; display:flex; align-items:center; justify-content:space-between;">
    <div>
        <h1 class="serif" style="font-size:1.4rem; color:var(--gold); margin-bottom:.2rem;">Hallo, {{ auth()->user()->name }}</h1>
        <p class="text-muted" style="font-size:.82rem;">Willkommen im disclosure Organisations-Bereich.</p>
    </div>
    @if(auth()->user()->isAdmin())
        <a href="{{ route('parties.create') }}" class="btn btn-gold">+ Neue Party</a>
    @endif
</div>

<!-- Kommende Partys -->
<div style="margin-bottom:2rem;">
    <h2 class="serif" style="font-size:1rem; color:var(--text); margin-bottom:.9rem; letter-spacing:.03em;">Kommende Partys</h2>

    @forelse($upcoming as $party)
        <a href="{{ route('parties.show', $party->id) }}" style="text-decoration:none; display:block; margin-bottom:.6rem;">
            <div class="card" style="display:flex; align-items:center; justify-content:space-between; transition:border-color .15s;"
                 onmouseover="this.style.borderColor='var(--gold)'" onmouseout="this.style.borderColor='var(--border)'">
                <div style="display:flex; align-items:center; gap:1.25rem;">
                    <div style="background:var(--border); border-radius:.375rem; padding:.5rem .7rem; text-align:center; min-width:52px;">
                        <div style="color:var(--gold); font-size:.65rem; text-transform:uppercase; letter-spacing:.05em;">{{ $party->date->translatedFormat('M') }}</div>
                        <div class="serif" style="font-size:1.2rem; font-weight:700; color:var(--text);">{{ $party->date->format('d') }}</div>
                    </div>
                    <div>
                        <div style="font-weight:600; color:var(--text); margin-bottom:.1rem;">{{ $party->title }}</div>
                        <div class="text-muted" style="font-size:.77rem;">
                            {{ $party->date->translatedFormat('l') }}, {{ $party->start_time }}
                            @if($party->location) &middot; {{ $party->location }} @endif
                            @if($party->is_special) <span class="badge badge-gold ml-1">Special</span> @endif
                        </div>
                    </div>
                </div>
                <div>
                    @if($party->isDraft())
                        <span class="badge badge-muted">Entwurf</span>
                    @elseif($party->isPublished())
                        <span class="badge badge-gold">Veröffentlicht</span>
                    @endif
                </div>
            </div>
        </a>
    @empty
        <div class="card text-muted" style="font-size:.85rem;">
            Keine kommenden Partys geplant.
            @if(auth()->user()->isAdmin())
                <a href="{{ route('parties.create') }}" class="text-gold" style="margin-left:.4rem;">Jetzt erstellen &rarr;</a>
            @endif
        </div>
    @endforelse
</div>

<!-- Vergangene Partys -->
@if($past->count())
<div>
    <h2 class="serif" style="font-size:.95rem; color:var(--muted); margin-bottom:.75rem; letter-spacing:.03em;">Zuletzt vergangen</h2>
    @foreach($past as $party)
        <a href="{{ route('parties.show', $party->id) }}" style="text-decoration:none; display:block; margin-bottom:.4rem;">
            <div style="display:flex; align-items:center; justify-content:space-between; padding:.5rem .75rem; border:1px solid var(--border); border-radius:.25rem; transition:border-color .15s;"
                 onmouseover="this.style.borderColor='var(--muted)'" onmouseout="this.style.borderColor='var(--border)'">
                <span class="text-muted" style="font-size:.82rem;">{{ $party->title }}</span>
                <span class="text-muted" style="font-size:.75rem;">{{ $party->date->format('d.m.Y') }}</span>
            </div>
        </a>
    @endforeach
</div>
@endif
@endsection
