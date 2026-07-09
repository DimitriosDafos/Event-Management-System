@extends('layouts.guest')
@section('title', 'Kontaktformular')

@section('content')
<div style="max-width:560px; margin:0 auto;">

    <div style="text-align:center; margin-bottom:2rem;">
        <div style="font-size:2rem; margin-bottom:.6rem;">✉️</div>
        <h1 class="serif" style="font-size:1.9rem; color:var(--gold); margin-bottom:.6rem;">Kontakt</h1>
        <p style="color:var(--muted); font-size:.92rem; line-height:1.7;">
            Du hast eine Frage oder möchtest uns etwas mitteilen?<br>Wir freuen uns über deine Nachricht.
        </p>
    </div>

    @if($errors->any())
        <div style="background:#2e1a1a; border:1px solid #5a2d2d; border-radius:.5rem; padding:1rem 1.25rem; font-size:.88rem; color:#e07070; margin-bottom:1.25rem;">
            @foreach($errors->all() as $e)<p style="margin:.2rem 0;">{{ $e }}</p>@endforeach
        </div>
    @endif

    <div style="background:var(--surface); border:1px solid var(--border); border-radius:.75rem; padding:2rem 2.25rem;">
        <form method="POST" action="{{ route('contact.store') }}"
              style="display:flex; flex-direction:column; gap:1.1rem;">
            @csrf

            {{-- Name (optional) --}}
            <div>
                <label style="display:block; font-size:.78rem; text-transform:uppercase; letter-spacing:.07em; color:var(--muted); margin-bottom:.4rem;">
                    Name <span style="font-weight:400; text-transform:none; letter-spacing:0;">(optional)</span>
                </label>
                <input style="width:100%; background:var(--bg); border:1px solid var(--border); color:var(--text); border-radius:.375rem; padding:.6rem .9rem; font-size:.92rem; box-sizing:border-box; transition:border .2s;"
                       onfocus="this.style.borderColor='var(--gold)'" onblur="this.style.borderColor='var(--border)'"
                       type="text" name="name" value="{{ old('name') }}" placeholder="Dein Name" maxlength="120">
            </div>

            {{-- E-Mail (Pflicht) --}}
            <div>
                <label style="display:block; font-size:.78rem; text-transform:uppercase; letter-spacing:.07em; color:var(--muted); margin-bottom:.4rem;">
                    E-Mail <span style="color:var(--gold);">*</span>
                </label>
                <input style="width:100%; background:var(--bg); border:1px solid {{ $errors->has('email') ? '#e07070' : 'var(--border)' }}; color:var(--text); border-radius:.375rem; padding:.6rem .9rem; font-size:.92rem; box-sizing:border-box; transition:border .2s;"
                       onfocus="this.style.borderColor='var(--gold)'" onblur="this.style.borderColor='var(--border)'"
                       type="email" name="email" value="{{ old('email') }}" placeholder="deine@email.de" required maxlength="255">
                @error('email')<p style="color:#e07070; font-size:.78rem; margin-top:.3rem;">{{ $message }}</p>@enderror
            </div>

            {{-- Betreff (Pflicht) --}}
            <div>
                <label style="display:block; font-size:.78rem; text-transform:uppercase; letter-spacing:.07em; color:var(--muted); margin-bottom:.4rem;">
                    Betreff <span style="color:var(--gold);">*</span>
                </label>
                <input style="width:100%; background:var(--bg); border:1px solid {{ $errors->has('subject') ? '#e07070' : 'var(--border)' }}; color:var(--text); border-radius:.375rem; padding:.6rem .9rem; font-size:.92rem; box-sizing:border-box; transition:border .2s;"
                       onfocus="this.style.borderColor='var(--gold)'" onblur="this.style.borderColor='var(--border)'"
                       type="text" name="subject" value="{{ old('subject') }}" placeholder="Worum geht es?" required maxlength="200">
                @error('subject')<p style="color:#e07070; font-size:.78rem; margin-top:.3rem;">{{ $message }}</p>@enderror
            </div>

            {{-- Nachricht (Pflicht) --}}
            <div>
                <label style="display:block; font-size:.78rem; text-transform:uppercase; letter-spacing:.07em; color:var(--muted); margin-bottom:.4rem;">
                    Nachricht <span style="color:var(--gold);">*</span>
                </label>
                <textarea style="width:100%; background:var(--bg); border:1px solid {{ $errors->has('message') ? '#e07070' : 'var(--border)' }}; color:var(--text); border-radius:.375rem; padding:.6rem .9rem; font-size:.92rem; box-sizing:border-box; resize:vertical; transition:border .2s; font-family:inherit;"
                          onfocus="this.style.borderColor='var(--gold)'" onblur="this.style.borderColor='var(--border)'"
                          name="message" rows="7" placeholder="Deine Nachricht…" required maxlength="5000"
                          id="msg-field">{{ old('message') }}</textarea>
                <div style="display:flex; justify-content:space-between; margin-top:.3rem;">
                    @error('message')<p style="color:#e07070; font-size:.78rem;">{{ $message }}</p>@enderror
                    <span style="font-size:.72rem; color:var(--muted); margin-left:auto;" id="msg-count">0 / 5000</span>
                </div>
            </div>

            {{-- Buttons --}}
            <div style="display:flex; gap:.75rem; margin-top:.25rem;">
                <button type="submit"
                        style="flex:1; background:var(--gold); color:var(--bg); font-weight:700; font-size:.95rem; padding:.75rem 1.5rem; border-radius:.5rem; border:none; cursor:pointer; transition:opacity .2s;"
                        onmouseover="this.style.opacity='.85'" onmouseout="this.style.opacity='1'">
                    Senden
                </button>
                <a href="{{ url('/') }}"
                   style="flex:1; background:var(--surface); border:1px solid var(--border); color:var(--muted); font-weight:600; font-size:.95rem; padding:.75rem 1.5rem; border-radius:.5rem; text-align:center; text-decoration:none; transition:border .2s;"
                   onmouseover="this.style.borderColor='var(--gold)'; this.style.color='var(--gold)'"
                   onmouseout="this.style.borderColor='var(--border)'; this.style.color='var(--muted)'">
                    Abbrechen
                </a>
            </div>

            <p style="font-size:.72rem; color:var(--muted); text-align:center;">
                Mit dem Absenden stimmst du zu, dass wir deine Anfrage per E-Mail beantworten dürfen.
            </p>
        </form>
    </div>
</div>

<script>
var f = document.getElementById('msg-field');
var c = document.getElementById('msg-count');
function updateCount() { c.textContent = f.value.length + ' / 5000'; }
f.addEventListener('input', updateCount);
updateCount();
</script>
@endsection
