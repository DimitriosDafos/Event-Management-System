@extends('layouts.app')
@section('title', 'Ankündigungen')

@section('content')
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.75rem; flex-wrap:wrap; gap:1rem;">
    <h1 class="serif" style="font-size:1.3rem; color:var(--gold);">Ankündigungen</h1>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-ghost btn-sm">← Admin</a>
</div>

@if(session('success'))
    <div style="background:#1a2e1a; border:1px solid #2d5a2d; border-radius:.375rem; padding:.75rem 1rem; font-size:.85rem; color:#7ec87e; margin-bottom:1.25rem;">
        {{ session('success') }}
    </div>
@endif

{{-- Neue Ankündigung --}}
<div class="card" style="margin-bottom:2rem;">
    <h2 class="serif" style="font-size:1rem; color:var(--text); margin-bottom:1.1rem;">Neue Ankündigung</h2>
    <form method="POST" action="{{ route('announcements.store') }}" style="display:flex; flex-direction:column; gap:.85rem;">
        @csrf
        <div>
            <label style="font-size:.78rem; text-transform:uppercase; letter-spacing:.07em; color:var(--muted); margin-bottom:.3rem; display:block;">Titel</label>
            <input class="input" type="text" name="title" value="{{ old('title') }}" placeholder="z.B. Danke für eure Unterstützung!" required maxlength="200">
            @error('title')<p style="color:#e07070; font-size:.78rem; margin-top:.3rem;">{{ $message }}</p>@enderror
        </div>
        <div>
            <label style="font-size:.78rem; text-transform:uppercase; letter-spacing:.07em; color:var(--muted); margin-bottom:.3rem; display:block;">Text</label>
            <textarea class="input" name="body" rows="5" placeholder="Deine Nachricht an die Community..." required maxlength="5000" style="resize:vertical;">{{ old('body') }}</textarea>
            @error('body')<p style="color:#e07070; font-size:.78rem; margin-top:.3rem;">{{ $message }}</p>@enderror
        </div>
        <div style="display:flex; align-items:center; gap:1.5rem; flex-wrap:wrap;">
            <div>
                <label style="font-size:.78rem; text-transform:uppercase; letter-spacing:.07em; color:var(--muted); margin-bottom:.3rem; display:block;">Reihenfolge</label>
                <input class="input" type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" style="width:90px;">
                <span style="font-size:.72rem; color:var(--muted); display:block; margin-top:.2rem;">0 = ganz oben</span>
            </div>
            <div style="margin-top:1.2rem;">
                <button type="submit" class="btn btn-gold">Veröffentlichen</button>
            </div>
        </div>
    </form>
</div>

{{-- Vorhandene Ankündigungen --}}
@if($announcements->count())
    <h2 class="serif" style="font-size:1rem; color:var(--muted); margin-bottom:1rem;">Vorhandene Ankündigungen</h2>
    <div style="display:flex; flex-direction:column; gap:1rem;">
        @foreach($announcements as $a)
        <div class="card" style="border-color: {{ $a->active ? 'var(--border)' : '#3a2a1a' }}; opacity: {{ $a->active ? '1' : '0.6' }};">
            <form method="POST" action="{{ route('announcements.update', $a->id) }}" style="display:flex; flex-direction:column; gap:.75rem;">
                @csrf @method('PATCH')
                <div style="display:flex; align-items:center; gap:.75rem; flex-wrap:wrap;">
                    <input class="input" type="text" name="title" value="{{ $a->title }}" required maxlength="200" style="flex:1; min-width:200px;">
                    <div style="display:flex; align-items:center; gap:.5rem;">
                        <label style="font-size:.78rem; color:var(--muted); margin:0;">Nr.</label>
                        <input class="input" type="number" name="sort_order" value="{{ $a->sort_order }}" min="0" style="width:70px; padding:.4rem .6rem;">
                    </div>
                    <label style="display:flex; align-items:center; gap:.4rem; cursor:pointer; font-size:.82rem; color:var(--muted);">
                        <input type="hidden" name="active" value="0">
                        <input type="checkbox" name="active" value="1" {{ $a->active ? 'checked' : '' }} style="accent-color:var(--gold);">
                        Aktiv
                    </label>
                </div>
                <textarea class="input" name="body" rows="4" required maxlength="5000" style="resize:vertical;">{{ $a->body }}</textarea>
                <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:.5rem;">
                    <span style="font-size:.72rem; color:var(--muted);">
                        Erstellt {{ $a->created_at->format('d.m.Y') }} · {{ $a->createdBy->name ?? '—' }}
                    </span>
                    <div style="display:flex; gap:.5rem;">
                        <button type="submit" class="btn btn-ghost btn-sm">Speichern</button>
                    </div>
                </div>
            </form>
            <form method="POST" action="{{ route('announcements.destroy', $a->id) }}" style="margin-top:.5rem;"
                  onsubmit="return confirm('Ankündigung wirklich löschen?')">
                @csrf @method('DELETE')
                <button type="submit" style="font-size:.75rem; color:#e07070; background:none; border:none; cursor:pointer; padding:0;">Löschen</button>
            </form>
        </div>
        @endforeach
    </div>
@else
    <p style="color:var(--muted); font-size:.88rem; text-align:center; padding:2rem 0;">Noch keine Ankündigungen vorhanden.</p>
@endif
@endsection
