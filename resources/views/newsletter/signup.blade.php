@extends('layouts.guest')
@section('title', 'Newsletter')

@section('content')
<div style="max-width:560px; margin:0 auto;">

    <div style="text-align:center; margin-bottom:2.5rem;">
        <div style="font-size:2rem; margin-bottom:.75rem;">✉️</div>
        <h1 class="serif" style="font-size:1.9rem; color:var(--gold); margin-bottom:.75rem;">Newsletter</h1>
        <p style="color:var(--muted); font-size:.95rem; line-height:1.75; max-width:420px; margin:0 auto;">
            Bleib auf dem Laufenden — wir informieren dich über kommende Events, Line-Ups und besondere Ankündigungen.<br>
            <span style="font-size:.82rem;">Kein Spam. Nur das Wesentliche.</span>
        </p>
    </div>

    @if(session('success'))
        <div id="success-msg" style="background:#1a2e1a; border:1px solid #2d5a2d; border-radius:.5rem; padding:1.1rem 1.4rem; font-size:.9rem; color:#7ec87e; margin-bottom:1.5rem; text-align:center;">
            ✓ {{ session('success') }}
            <p style="margin:.5rem 0 0; font-size:.78rem; color:#5a9a5a;">Du wirst in <span id="countdown">5</span> Sekunden zur Homepage weitergeleitet…</p>
        </div>
        <script>
            var sec = 5;
            var el = document.getElementById('countdown');
            var timer = setInterval(function() {
                sec--;
                if (el) el.textContent = sec;
                if (sec <= 0) { clearInterval(timer); window.location.href = '{{ url('/') }}'; }
            }, 1000);
        </script>
    @endif

    @if(session('info'))
        <div style="background:#1a1f2e; border:1px solid #2d3a5a; border-radius:.5rem; padding:1.1rem 1.4rem; font-size:.9rem; color:#7090e0; margin-bottom:1.5rem; text-align:center;">
            ℹ {{ session('info') }}
        </div>
    @endif

    @if($errors->any())
        <div style="background:#2e1a1a; border:1px solid #5a2d2d; border-radius:.5rem; padding:1.1rem 1.4rem; font-size:.88rem; color:#e07070; margin-bottom:1.5rem;">
            @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
        </div>
    @endif

    <div style="background:var(--surface); border:1px solid var(--border); border-radius:.75rem; padding:2rem 2.25rem;">
        <form method="POST" action="{{ route('newsletter.store') }}" style="display:flex; flex-direction:column; gap:1.1rem;">
            @csrf

            <div>
                <label style="display:block; font-size:.78rem; text-transform:uppercase; letter-spacing:.07em; color:var(--muted); margin-bottom:.4rem;">
                    Name <span style="font-size:.72rem; color:var(--muted); font-weight:400;">(optional)</span>
                </label>
                <input style="width:100%; background:var(--bg); border:1px solid var(--border); color:var(--text); border-radius:.375rem; padding:.6rem .9rem; font-size:.92rem; box-sizing:border-box; transition:border .2s;"
                       onfocus="this.style.borderColor='var(--gold)'" onblur="this.style.borderColor='var(--border)'"
                       type="text" name="name" value="{{ old('name') }}" placeholder="Wie dürfen wir dich nennen?" maxlength="120">
            </div>

            <div>
                <label style="display:block; font-size:.78rem; text-transform:uppercase; letter-spacing:.07em; color:var(--muted); margin-bottom:.4rem;">
                    E-Mail-Adresse <span style="color:var(--gold);">*</span>
                </label>
                <input style="width:100%; background:var(--bg); border:1px solid var(--border); color:var(--text); border-radius:.375rem; padding:.6rem .9rem; font-size:.92rem; box-sizing:border-box; transition:border .2s;"
                       onfocus="this.style.borderColor='var(--gold)'" onblur="this.style.borderColor='var(--border)'"
                       type="email" name="email" value="{{ old('email') }}" placeholder="deine@email.de" required maxlength="255" autofocus>
                @error('email')<p style="color:#e07070; font-size:.78rem; margin-top:.35rem;">{{ $message }}</p>@enderror
            </div>

            <button type="submit"
                    style="width:100%; background:var(--gold); color:var(--bg); font-weight:700; font-size:.95rem; padding:.75rem 1.5rem; border-radius:.5rem; border:none; cursor:pointer; letter-spacing:.03em; transition:opacity .2s; margin-top:.25rem;"
                    onmouseover="this.style.opacity='.85'" onmouseout="this.style.opacity='1'">
                Jetzt zum Newsletter anmelden
            </button>

            <p style="font-size:.72rem; color:var(--muted); text-align:center; line-height:1.6;">
                Mit der Anmeldung stimmst du zu, gelegentliche E-Mails von uns zu erhalten.<br>
                Eine Abmeldung ist jederzeit möglich.
            </p>
        </form>
    </div>

    <div style="text-align:center; margin-top:1.75rem;">
        <a href="{{ url('/') }}" style="font-size:.82rem; color:var(--muted); text-decoration:none;">← Zurück zur Homepage</a>
    </div>
</div>
@endsection
