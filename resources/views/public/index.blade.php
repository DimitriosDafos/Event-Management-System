@extends('layouts.guest')
@section('title', $party ? $party->title : 'Nächste Party')

@section('content')
@if($party)
    {{-- Flyer --}}
    @if($party->flyer_path)
        <div style="text-align:center; margin-bottom:2.5rem;">
            <img src="{{ asset('storage/'.$party->flyer_path) }}" alt="Flyer"
                 style="max-width:340px; width:100%; border-radius:.5rem; box-shadow:0 8px 40px rgba(0,0,0,.6);">
        </div>
    @endif

    {{-- Party Info --}}
    <div style="text-align:center; margin-bottom:2.5rem;">
        <div style="font-size:.75rem; text-transform:uppercase; letter-spacing:.12em; color:var(--muted); margin-bottom:.6rem;">Nächste Party</div>
        <h1 class="serif" style="font-size:2.2rem; color:var(--gold); margin-bottom:.75rem; line-height:1.2;">{{ $party->title }}</h1>

        <div style="display:inline-flex; flex-wrap:wrap; gap:1.5rem; justify-content:center; font-size:.9rem; color:var(--text); margin-bottom:1rem;">
            <span>📅 {{ $party->date->format('d.m.Y') }}</span>
            <span>🕙 {{ $party->start_time }} Uhr@if($party->end_time) – {{ $party->end_time }} Uhr@endif</span>
            @if($party->location)
                <span>📍 {{ $party->location }}</span>
            @endif
        </div>

        @if($party->address)
            <div class="text-muted" style="font-size:.82rem;">{{ $party->address }}</div>
        @endif
    </div>

    {{-- Beschreibung --}}
    @if($party->description)
        <div style="max-width:640px; margin:0 auto 2.5rem; background:var(--surface); border:1px solid var(--border); border-radius:.5rem; padding:1.5rem;">
            <p style="font-size:.92rem; line-height:1.75; color:var(--text); white-space:pre-wrap;">{{ $party->description }}</p>
        </div>
    @endif

    {{-- DJ Lineup --}}
    @if($party->djLineup->count())
        <div style="max-width:640px; margin:0 auto 2.5rem;">
            <h2 class="serif" style="font-size:1.1rem; color:var(--gold); text-align:center; margin-bottom:1rem; letter-spacing:.04em;">Line-Up</h2>
            <div style="display:flex; flex-direction:column; gap:.5rem;">
                @foreach($party->djLineup as $dj)
                    <div style="background:var(--surface); border:1px solid var(--border); border-radius:.375rem; padding:.75rem 1.1rem; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:.5rem;">
                        <div>
                            <span style="font-weight:600; font-size:.95rem; color:var(--text);">{{ $dj->dj_name }}</span>
                            @if($dj->style)
                                <span class="text-muted" style="font-size:.78rem; margin-left:.6rem;">{{ $dj->style }}</span>
                            @endif
                        </div>
                        <div style="display:flex; align-items:center; gap:.75rem;">
                            <span class="text-muted" style="font-size:.82rem;">{{ $dj->from }} – {{ $dj->till }}</span>
                            @if($dj->website)
                                <a href="{{ $dj->website }}" target="_blank" style="font-size:.75rem; color:var(--gold);">↗</a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

@else
    {{-- No party planned --}}
    <div style="text-align:center; padding:5rem 2rem;">
        <div class="serif" style="font-size:3rem; color:var(--border); margin-bottom:1.5rem;">◈</div>
        <h1 class="serif" style="font-size:2rem; color:var(--gold); margin-bottom:1rem;">disclosure</h1>
        <p style="color:var(--muted); font-size:1rem; letter-spacing:.08em; text-transform:uppercase;">Coming soon ...</p>
        <p style="color:var(--muted); font-size:.8rem; margin-top:1.5rem; max-width:360px; margin-left:auto; margin-right:auto; line-height:1.6;">
            Die nächste Party wird in Kürze angekündigt.<br>
            Bleib dran.
        </p>
    </div>
@endif
@endsection
