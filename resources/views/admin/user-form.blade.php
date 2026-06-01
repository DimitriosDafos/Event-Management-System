@extends('layouts.app')
@section('title', $user ? 'Mitglied bearbeiten' : 'Neues Mitglied')

@section('content')
<div style="max-width:480px;">
    <div style="margin-bottom:1.5rem;">
        <a href="{{ route('admin.users') }}" class="text-muted" style="font-size:.78rem; text-decoration:none;">← Mitglieder</a>
        <h1 class="serif" style="font-size:1.3rem; color:var(--gold); margin:.3rem 0 0;">
            {{ $user ? 'Mitglied bearbeiten' : 'Neues Mitglied anlegen' }}
        </h1>
    </div>

    <div class="card">
        <form method="POST" action="{{ $user ? route('admin.users.update', $user->id) : route('admin.users.store') }}">
            @csrf
            @if($user) @method('PATCH') @endif

            <div class="form-group">
                <label class="form-label">Voller Name *</label>
                <input type="text" name="name" class="form-input"
                       value="{{ old('name', $user->name ?? '') }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Benutzername *</label>
                <input type="text" name="username" class="form-input"
                       value="{{ old('username', $user->username ?? '') }}"
                       required pattern="[a-zA-Z0-9_\-]+" placeholder="nur Buchstaben, Zahlen, _ und -">
                <span class="text-muted" style="font-size:.72rem;">Wird zum Anmelden verwendet.</span>
            </div>

            <div class="form-group">
                <label class="form-label">Rolle *</label>
                <select name="role" class="form-select" required>
                    <option value="member"    {{ old('role', $user->role ?? '') === 'member'    ? 'selected' : '' }}>Mitglied</option>
                    <option value="marketing" {{ old('role', $user->role ?? '') === 'marketing' ? 'selected' : '' }}>Marketing</option>
                    <option value="admin"     {{ old('role', $user->role ?? '') === 'admin'     ? 'selected' : '' }}>Admin</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">
                    Passwort {{ $user ? '(leer lassen = nicht ändern)' : '*' }}
                </label>
                <input type="password" name="password" class="form-input"
                       {{ $user ? '' : 'required' }} minlength="6"
                       placeholder="{{ $user ? 'Neues Passwort eingeben...' : 'Mind. 6 Zeichen' }}">
            </div>

            @if($user)
                <div class="form-group" style="display:flex; align-items:center; gap:.75rem;">
                    <input type="hidden" name="active" value="0">
                    <input type="checkbox" name="active" value="1" id="active"
                           style="width:1rem; height:1rem; accent-color:var(--gold);"
                           {{ old('active', $user->active) ? 'checked' : '' }}>
                    <label for="active" style="color:var(--text); font-size:.85rem; cursor:pointer;">
                        Konto aktiv
                    </label>
                </div>
            @endif

            <hr class="divider">

            <div style="display:flex; gap:.75rem; align-items:center;">
                <button type="submit" class="btn btn-gold">
                    {{ $user ? 'Änderungen speichern' : 'Mitglied anlegen' }}
                </button>
                <a href="{{ route('admin.users') }}" class="btn btn-ghost">Abbrechen</a>
            </div>
        </form>
    </div>
</div>
@endsection
