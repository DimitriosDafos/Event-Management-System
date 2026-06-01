@extends('layouts.app')
@section('title', $party ? 'Party bearbeiten' : 'Neue Party')

@section('content')
<div style="max-width:600px;">
    <div style="margin-bottom:1.5rem;">
        <a href="{{ $party ? route('parties.show', $party->id) : route('parties.index') }}" class="text-muted" style="font-size:.78rem; text-decoration:none;">← Zurück</a>
        <h1 class="serif" style="font-size:1.3rem; color:var(--gold); margin:.3rem 0 0;">
            {{ $party ? 'Party bearbeiten' : 'Neue Party erstellen' }}
        </h1>
    </div>

    <div class="card">
        <form method="POST" action="{{ $party ? route('parties.update', $party->id) : route('parties.store') }}">
            @csrf
            @if($party) @method('PATCH') @endif

            <div class="form-group">
                <label class="form-label">Titel / Name der Party *</label>
                <input type="text" name="title" class="form-input"
                       value="{{ old('title', $party->title ?? '') }}" required
                       placeholder="z.B. disclosure #12">
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:.75rem;">
                <div class="form-group">
                    <label class="form-label">Datum *</label>
                    <input type="date" name="date" class="form-input"
                           value="{{ old('date', isset($party) ? $party->date->format('Y-m-d') : '') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Beginn *</label>
                    <input type="time" name="start_time" class="form-input"
                           value="{{ old('start_time', $party->start_time ?? '22:00') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Ende</label>
                    <input type="time" name="end_time" class="form-input"
                           value="{{ old('end_time', $party->end_time ?? '') }}">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Location</label>
                <input type="text" name="location" class="form-input"
                       value="{{ old('location', $party->location ?? '') }}"
                       placeholder="z.B. Kloster Aachen">
            </div>

            <div class="form-group">
                <label class="form-label">Adresse</label>
                <input type="text" name="address" class="form-input"
                       value="{{ old('address', $party->address ?? '') }}"
                       placeholder="z.B. Pontstraße 143, 52062 Aachen">
            </div>

            <div class="form-group" style="display:flex; align-items:center; gap:.75rem;">
                <input type="hidden" name="is_special" value="0">
                <input type="checkbox" name="is_special" value="1" id="is_special"
                       style="width:1rem; height:1rem; accent-color:var(--gold);"
                       {{ old('is_special', $party->is_special ?? false) ? 'checked' : '' }}>
                <label for="is_special" style="color:var(--text); font-size:.85rem; cursor:pointer;">
                    Special Event <span class="text-muted" style="font-size:.75rem;">(kein regulärer 1. Freitag)</span>
                </label>
            </div>

            <hr class="divider">

            <div style="display:flex; gap:.75rem; align-items:center;">
                <button type="submit" class="btn btn-gold">
                    {{ $party ? 'Änderungen speichern' : 'Party erstellen' }}
                </button>
                <a href="{{ $party ? route('parties.show', $party->id) : route('parties.index') }}" class="btn btn-ghost">Abbrechen</a>
            </div>
        </form>
    </div>
</div>
@endsection
