@extends('layouts.app')
@section('title', 'Admin')

@section('content')
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.75rem;">
    <h1 class="serif" style="font-size:1.3rem; color:var(--gold);">Admin-Bereich</h1>
</div>

<!-- Stats -->
<div style="display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; margin-bottom:2rem;">
    <div class="card" style="text-align:center;">
        <div class="serif" style="font-size:2rem; font-weight:700; color:var(--gold);">{{ $userCount }}</div>
        <div class="text-muted" style="font-size:.78rem; text-transform:uppercase; letter-spacing:.06em;">Mitglieder</div>
    </div>
    <div class="card" style="text-align:center;">
        <div class="serif" style="font-size:2rem; font-weight:700; color:var(--gold);">{{ $partyCount }}</div>
        <div class="text-muted" style="font-size:.78rem; text-transform:uppercase; letter-spacing:.06em;">Partys gesamt</div>
    </div>
    <div class="card" style="text-align:center;">
        @if($nextParty)
            <div class="serif" style="font-size:1.1rem; font-weight:700; color:var(--text);">{{ $nextParty->date->format('d.m.Y') }}</div>
            <div class="text-muted" style="font-size:.78rem; text-transform:uppercase; letter-spacing:.06em;">Nächste Party</div>
        @else
            <div class="serif" style="font-size:1.1rem; color:var(--muted);">—</div>
            <div class="text-muted" style="font-size:.78rem; text-transform:uppercase; letter-spacing:.06em;">Nächste Party</div>
        @endif
    </div>
</div>

<!-- Quick Links -->
<div style="display:grid; grid-template-columns:repeat(2,1fr); gap:1rem;">
    <div class="card">
        <h3 class="serif" style="font-size:1rem; color:var(--text); margin-bottom:.75rem;">Mitglieder</h3>
        <p class="text-muted" style="font-size:.82rem; margin-bottom:.9rem;">Benutzerkonten anlegen, bearbeiten und verwalten.</p>
        <div style="display:flex; gap:.6rem;">
            <a href="{{ route('admin.users') }}" class="btn btn-ghost btn-sm">Alle Mitglieder</a>
            <a href="{{ route('admin.users.create') }}" class="btn btn-gold btn-sm">+ Neues Mitglied</a>
        </div>
    </div>

    <div class="card">
        <h3 class="serif" style="font-size:1rem; color:var(--text); margin-bottom:.75rem;">Partys</h3>
        <p class="text-muted" style="font-size:.82rem; margin-bottom:.9rem;">Partys erstellen, bearbeiten und den Status verwalten.</p>
        <div style="display:flex; gap:.6rem;">
            <a href="{{ route('parties.index') }}" class="btn btn-ghost btn-sm">Alle Partys</a>
            <a href="{{ route('parties.create') }}" class="btn btn-gold btn-sm">+ Neue Party</a>
        </div>
    </div>


    <div class="card" style="margin-top:1rem; grid-column:1/-1;">
        <h3 class="serif" style="font-size:1rem; color:var(--text); margin-bottom:.75rem;">Ankündigungen &amp; Neuigkeiten</h3>
        <p class="text-muted" style="font-size:.82rem; margin-bottom:.9rem;">Texte und Danksagungen für die öffentliche Homepage verwalten.</p>
        <div style="display:flex; gap:.6rem;">
            <a href="{{ route('announcements.index') }}" class="btn btn-gold btn-sm">Ankündigungen verwalten</a>
        </div>
    </div>

    <div class="card" style="margin-top:1rem; grid-column:1/-1;">
        <h3 class="serif" style="font-size:1rem; color:var(--text); margin-bottom:.75rem;">Branding &amp; Erscheinungsbild</h3>
        <p class="text-muted" style="font-size:.82rem; margin-bottom:.9rem;">Logo, Markenname, Slogan und Footer-Text der öffentlichen Seite anpassen.</p>
        <div style="display:flex; gap:.6rem;">
            <a href="{{ route('branding.index') }}" class="btn btn-gold btn-sm">Branding bearbeiten</a>
        </div>
    </div>
</div>
@endsection
