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
                <label class="form-label">Gruppen / Rollen *</label>
                <div style="background:var(--bg); border:1px solid var(--border); border-radius:.25rem; padding:.6rem .75rem; display:flex; flex-direction:column; gap:.55rem;">
                    @php
                        $currentRoles = old('roles', $user ? (array)$user->role : ['member']);
                        $roleOptions  = [
                            'admin'     => ['label' => 'Admin',     'desc' => 'Volle Kontrolle über alles'],
                            'marketing' => ['label' => 'Marketing', 'desc' => 'Flyer, Beschreibung, DJs, Einlass'],
                            'dj'        => ['label' => 'DJ',        'desc' => 'DJ-Lineup eintragen'],
                            'member'    => ['label' => 'Mitglied',  'desc' => 'Bar, Einlass, ToDos'],
                        ];
                    @endphp
                    @foreach($roleOptions as $value => $opt)
                        <label style="display:flex; align-items:center; gap:.75rem; cursor:pointer; padding:.2rem 0;">
                            <input type="checkbox" name="roles[]" value="{{ $value }}"
                                   style="width:1rem; height:1rem; accent-color:var(--gold); flex-shrink:0;"
                                   {{ in_array($value, $currentRoles) ? 'checked' : '' }}>
                            <div>
                                <span style="font-size:.85rem; color:var(--text);">{{ $opt['label'] }}</span>
                                <span class="text-muted" style="font-size:.72rem; margin-left:.4rem;">— {{ $opt['desc'] }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
                <span class="text-muted" style="font-size:.72rem; margin-top:.3rem; display:block;">Mehrere Gruppen möglich. Höchste Rechte gelten.</span>
                @error('roles') <span style="color:#c97070; font-size:.75rem;">{{ $message }}</span> @enderror
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
