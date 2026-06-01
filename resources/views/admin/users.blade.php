@extends('layouts.app')
@section('title', 'Mitglieder')

@section('content')
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem;">
    <div>
        <a href="{{ route('admin.dashboard') }}" class="text-muted" style="font-size:.78rem; text-decoration:none;">← Admin</a>
        <h1 class="serif" style="font-size:1.3rem; color:var(--gold); margin:.2rem 0 0;">Mitglieder</h1>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-gold">+ Neues Mitglied</a>
</div>

<div class="card" style="padding:0; overflow:hidden;">
    <table class="data-table">
        <thead>
            <tr>
                <th style="padding:.75rem 1rem;">Name</th>
                <th>Benutzername</th>
                <th>Rolle</th>
                <th>Status</th>
                <th style="text-align:right; padding:.75rem 1rem;">Aktionen</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td style="padding:.65rem 1rem; font-weight:500;">{{ $user->name }}</td>
                    <td style="color:var(--muted);">@{{ $user->username }}</td>
                    <td>
                        @if($user->role === 'admin')
                            <span class="badge badge-gold">Admin</span>
                        @elseif($user->role === 'marketing')
                            <span class="badge badge-red">Marketing</span>
                        @else
                            <span class="badge badge-muted">Mitglied</span>
                        @endif
                    </td>
                    <td>
                        @if($user->active)
                            <span class="badge badge-green">Aktiv</span>
                        @else
                            <span class="badge badge-muted">Inaktiv</span>
                        @endif
                    </td>
                    <td style="text-align:right; padding:.65rem 1rem;">
                        <div style="display:flex; justify-content:flex-end; gap:.5rem;">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-ghost btn-xs">Bearbeiten</a>
                            @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.delete', $user->id) }}" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-ghost btn-xs" style="color:#c97070;"
                                            onclick="return confirm('Mitglied {{ $user->name }} wirklich löschen?')">
                                        Löschen
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-muted" style="text-align:center; padding:2rem;">
                        Keine Mitglieder angelegt.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
