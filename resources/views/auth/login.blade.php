@extends('layouts.guest')
@section('title', 'Anmelden')

@section('content')
<div style="max-width:400px; margin:4rem auto;">
    <div style="text-align:center; margin-bottom:2rem;">
        <h1 class="serif" style="color:var(--gold); font-size:1.6rem; margin-bottom:.4rem;">disclosure</h1>
        <p class="text-muted" style="font-size:.82rem;">Mitglieder-Login</p>
    </div>

    @if($errors->any())
        <div style="background:rgba(139,58,58,.15); border:1px solid rgba(139,58,58,.4); color:#c97070; border-radius:.25rem; padding:.6rem .9rem; font-size:.83rem; margin-bottom:1rem;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login.post') }}" style="background:var(--surface); border:1px solid var(--border); border-radius:.5rem; padding:2rem;">
        @csrf
        <div style="margin-bottom:1.1rem;">
            <label style="display:block; color:var(--muted); font-size:.7rem; text-transform:uppercase; letter-spacing:.06em; margin-bottom:.35rem;">Benutzername</label>
            <input type="text" name="username" value="{{ old('username') }}" required autofocus
                   style="width:100%; background:var(--bg); border:1px solid var(--border); color:var(--text); padding:.5rem .75rem; border-radius:.25rem; font-size:.9rem; font-family:'Inter',sans-serif;"
                   onfocus="this.style.borderColor='var(--gold)'" onblur="this.style.borderColor='var(--border)'">
        </div>
        <div style="margin-bottom:1.5rem;">
            <label style="display:block; color:var(--muted); font-size:.7rem; text-transform:uppercase; letter-spacing:.06em; margin-bottom:.35rem;">Passwort</label>
            <input type="password" name="password" required
                   style="width:100%; background:var(--bg); border:1px solid var(--border); color:var(--text); padding:.5rem .75rem; border-radius:.25rem; font-size:.9rem; font-family:'Inter',sans-serif;"
                   onfocus="this.style.borderColor='var(--gold)'" onblur="this.style.borderColor='var(--border)'">
        </div>
        <button type="submit"
                style="width:100%; background:var(--gold); color:var(--bg); font-weight:700; padding:.6rem 1rem; border-radius:.25rem; border:none; cursor:pointer; font-size:.9rem; letter-spacing:.03em; font-family:'Inter',sans-serif; transition:opacity .15s;"
                onmouseover="this.style.opacity='.85'" onmouseout="this.style.opacity='1'">
            Anmelden
        </button>
    </form>

    <p class="text-muted" style="text-align:center; font-size:.72rem; margin-top:1.5rem;">
        Kein Account? Wende dich an einen Admin.
    </p>
</div>
@endsection
