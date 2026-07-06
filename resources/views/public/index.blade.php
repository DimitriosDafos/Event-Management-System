@extends('layouts.guest')
@section('title', $party ? $party->title : ($lastParty ? 'Danke · '.$lastParty->title : 'disclosure'))

@section('content')
@if($party)
    {{-- ===== AKTIVE PARTY ===== --}}
    @if($party->flyer_path)
        <div style="text-align:center; margin-bottom:2.5rem;">
            <img src="{{ asset('storage/'.$party->flyer_path) }}" alt="Flyer"
                 style="max-width:340px; width:100%; border-radius:.5rem; box-shadow:0 8px 40px rgba(0,0,0,.6);">
        </div>
    @endif

    <div style="text-align:center; margin-bottom:2.5rem;">
        <div style="font-size:.75rem; text-transform:uppercase; letter-spacing:.12em; color:var(--muted); margin-bottom:.6rem;">Nächste Party</div>
        <h1 class="serif" style="font-size:2.2rem; color:var(--gold); margin-bottom:.75rem; line-height:1.2;">{{ $party->title }}</h1>
        <div style="display:inline-flex; flex-wrap:wrap; gap:1.5rem; justify-content:center; font-size:.9rem; color:var(--text); margin-bottom:1rem;">
            <span>📅 {{ $party->date->format('d.m.Y') }}</span>
            <span>🕙 {{ $party->start_time }} Uhr@if($party->end_time) – {{ $party->end_time }} Uhr@endif</span>
            @if($party->location)<span>📍 {{ $party->location }}</span>@endif
        </div>
        @if($party->address)
            <div class="text-muted" style="font-size:.82rem;">{{ $party->address }}</div>
        @endif
    </div>

    @if($party->description)
        <div style="max-width:640px; margin:0 auto 2.5rem; background:var(--surface); border:1px solid var(--border); border-radius:.5rem; padding:1.5rem;">
            <p style="font-size:.92rem; line-height:1.75; color:var(--text); white-space:pre-wrap;">{{ $party->description }}</p>
        </div>
    @endif

    @if($party->djLineup->count())
        <div style="max-width:640px; margin:0 auto 2.5rem;">
            <h2 class="serif" style="font-size:1.1rem; color:var(--gold); text-align:center; margin-bottom:1rem; letter-spacing:.04em;">Line-Up</h2>
            <div style="display:flex; flex-direction:column; gap:.5rem;">
                @foreach($party->djLineup as $dj)
                    <div style="background:var(--surface); border:1px solid var(--border); border-radius:.375rem; padding:.75rem 1.1rem; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:.5rem;">
                        <div>
                            <span style="font-weight:600; font-size:.95rem; color:var(--text);">{{ $dj->dj_name }}</span>
                            @if($dj->style)<span class="text-muted" style="font-size:.78rem; margin-left:.6rem;">{{ $dj->style }}</span>@endif
                        </div>
                        <div style="display:flex; align-items:center; gap:.75rem;">
                            <span class="text-muted" style="font-size:.82rem;">{{ $dj->from }} – {{ $dj->till }}</span>
                            @if($dj->website)<a href="{{ $dj->website }}" target="_blank" style="font-size:.75rem; color:var(--gold);">↗</a>@endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

@else
    {{-- ===== KEINE AKTIVE PARTY ===== --}}

    {{-- Letzte Party: Danksagung --}}
    @if($lastParty)
        <div style="text-align:center; margin-bottom:3rem;">
            <div style="font-size:2.5rem; margin-bottom:1rem;">🖤</div>
            <h1 class="serif" style="font-size:2rem; color:var(--gold); margin-bottom:.6rem; line-height:1.25;">Danke, {{ $lastParty->title }}</h1>
            <p style="color:var(--muted); font-size:.92rem; max-width:480px; margin:0 auto; line-height:1.7;">
                Danke an alle, die dabei waren und diese Nacht unvergesslich gemacht haben.<br>
                Ihr seid der Grund, warum wir das tun.
            </p>
            <div style="display:inline-flex; flex-wrap:wrap; gap:1.2rem; justify-content:center; font-size:.82rem; color:var(--muted); margin-top:1.25rem;">
                <span>📅 {{ $lastParty->date->format('d.m.Y') }}</span>
                @if($lastParty->location)<span>📍 {{ $lastParty->location }}</span>@endif
            </div>
        </div>

        @if($lastParty->djLineup->count())
            <div style="max-width:580px; margin:0 auto 2.5rem;">
                <p style="font-size:.72rem; text-transform:uppercase; letter-spacing:.1em; color:var(--muted); text-align:center; margin-bottom:.75rem;">Das war das Line-Up</p>
                <div style="display:flex; flex-direction:column; gap:.4rem;">
                    @foreach($lastParty->djLineup as $dj)
                        <div style="background:var(--surface); border:1px solid var(--border); border-radius:.375rem; padding:.6rem 1rem; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:.4rem; opacity:.8;">
                            <span style="font-weight:600; font-size:.9rem; color:var(--text);">{{ $dj->dj_name }}</span>
                            <span class="text-muted" style="font-size:.78rem;">{{ $dj->from }} – {{ $dj->till }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div style="text-align:center; margin-bottom:3rem;">
            <div style="width:60px; height:1px; background:var(--border); margin:0 auto;"></div>
        </div>
    @else
        {{-- Noch gar keine Party: kleines Intro --}}
        <div style="text-align:center; padding:3rem 2rem 2rem;">
            <div class="serif" style="font-size:3rem; color:var(--border); margin-bottom:1.25rem;">◈</div>
            <h1 class="serif" style="font-size:2rem; color:var(--gold); margin-bottom:.75rem;">disclosure</h1>
        </div>
    @endif

    {{-- Ankündigungen --}}
    @if($announcements->count())
        <div style="max-width:640px; margin:0 auto;">
            <p style="font-size:.72rem; text-transform:uppercase; letter-spacing:.1em; color:var(--muted); text-align:center; margin-bottom:1.25rem;">Neuigkeiten</p>
            <div style="display:flex; flex-direction:column; gap:1.25rem;">
                @foreach($announcements as $a)
                    <div style="background:var(--surface); border:1px solid var(--border); border-radius:.5rem; padding:1.4rem 1.6rem;">
                        <h3 class="serif" style="font-size:1.05rem; color:var(--gold); margin-bottom:.6rem;">{{ $a->title }}</h3>
                        <p style="font-size:.9rem; line-height:1.75; color:var(--text); white-space:pre-wrap;">{{ $a->body }}</p>
                        <p style="font-size:.72rem; color:var(--muted); margin-top:.9rem;">{{ $a->created_at->format('d.m.Y') }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        {{-- Nichts: Platzhalter --}}
        <div style="text-align:center; padding:1rem 2rem 3rem;">
            <p style="color:var(--muted); font-size:.88rem; letter-spacing:.06em; text-transform:uppercase;">Nächste Party wird in Kürze angekündigt.</p>
        </div>
    @endif

@endif
@endsection
