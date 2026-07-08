@extends('layouts.app')
@section('title', 'Newsletter erstellen')

@section('content')
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.75rem; flex-wrap:wrap; gap:1rem;">
    <h1 class="serif" style="font-size:1.3rem; color:var(--gold);">Neuer Newsletter</h1>
    <a href="{{ route('newsletter.campaigns.index') }}" class="btn btn-ghost btn-sm">← Zurück</a>
</div>

{{-- Hinweis Empfänger --}}
<div style="background:#1a1f2a; border:1px solid #2a3040; border-radius:.5rem; padding:.85rem 1.1rem; font-size:.85rem; color:#7090c0; margin-bottom:1.5rem; display:flex; align-items:center; gap:.6rem;">
    <span style="font-size:1rem;">📬</span>
    Dieser Newsletter wird an <strong style="color:var(--text);">{{ $subscriberCount }} aktive Abonnenten</strong> gesendet.
    @if($subscriberCount === 0)
        <span style="color:#e07070;"> — Keine aktiven Abonnenten vorhanden!</span>
    @endif
</div>

@if($errors->any())
    <div style="background:#2e1a1a; border:1px solid #5a2d2d; border-radius:.5rem; padding:1rem 1.25rem; font-size:.88rem; color:#e07070; margin-bottom:1.25rem;">
        @foreach($errors->all() as $e)<p style="margin:.2rem 0;">{{ $e }}</p>@endforeach
    </div>
@endif

<form method="POST" action="{{ route('newsletter.campaigns.send') }}"
      onsubmit="return confirmSend({{ $subscriberCount }})">
    @csrf

    <div class="card" style="margin-bottom:1.25rem; display:flex; flex-direction:column; gap:1rem;">
        {{-- Betreff --}}
        <div>
            <label class="form-label">Betreff <span style="color:var(--gold);">*</span></label>
            <input class="form-input" type="text" name="subject"
                   value="{{ old('subject') }}"
                   placeholder="z.B. Unsere nächste Party – alle Infos!" required maxlength="200">
        </div>

        {{-- Text --}}
        <div>
            <label class="form-label">Newsletter-Text <span style="color:var(--gold);">*</span></label>
            <textarea class="form-input" name="body" rows="16" required maxlength="20000"
                      placeholder="Schreib hier deinen Newsletter-Text. Zeilenumbrüche werden übernommen."
                      style="resize:vertical;" id="body-field">{{ old('body') }}</textarea>
            <div style="display:flex; justify-content:space-between; margin-top:.3rem;">
                <span style="font-size:.72rem; color:var(--muted);">Zeilenumbrüche werden in der E-Mail beibehalten.</span>
                <span style="font-size:.72rem; color:var(--muted);" id="char-count">0 / 20.000</span>
            </div>
        </div>
    </div>

    {{-- Vorschau --}}
    <div style="margin-bottom:1.5rem;">
        <button type="button" onclick="togglePreview()"
                style="background:none; border:1px solid var(--border); color:var(--muted); font-size:.82rem; padding:.4rem .9rem; border-radius:.375rem; cursor:pointer;">
            👁 Vorschau anzeigen / ausblenden
        </button>
        <div id="preview-box" style="display:none; margin-top:.75rem; border:1px solid var(--border); border-radius:.5rem; background:#fff; padding:2rem; color:#222;">
            <p style="font-size:16px; color:#444; margin:0 0 1rem;">Hallo [Name],</p>
            <div id="preview-body" style="font-size:15px; color:#333; line-height:1.8; white-space:pre-wrap;"></div>
        </div>
    </div>

    <div style="display:flex; align-items:center; gap:.75rem; flex-wrap:wrap;">
        <button type="submit" class="btn btn-gold" {{ $subscriberCount === 0 ? 'disabled' : '' }}>
            📨 Newsletter jetzt senden
        </button>
        <span style="font-size:.78rem; color:var(--muted);">
            Dieser Vorgang kann nicht rückgängig gemacht werden.
        </span>
    </div>
</form>

<script>
document.getElementById('body-field').addEventListener('input', function() {
    document.getElementById('char-count').textContent = this.value.length.toLocaleString('de') + ' / 20.000';
    document.getElementById('preview-body').textContent = this.value;
});

function togglePreview() {
    var box = document.getElementById('preview-box');
    document.getElementById('preview-body').textContent = document.getElementById('body-field').value;
    box.style.display = box.style.display === 'none' ? 'block' : 'none';
}

function confirmSend(count) {
    return confirm('Newsletter wirklich an ' + count + ' Abonnenten senden? Diese Aktion kann nicht rückgängig gemacht werden.');
}
</script>
@endsection
