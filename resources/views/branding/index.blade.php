@extends('layouts.app')
@section('title', 'Branding')

@section('content')
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.75rem; flex-wrap:wrap; gap:1rem;">
    <h1 class="serif" style="font-size:1.3rem; color:var(--gold);">Branding & Erscheinungsbild</h1>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-ghost btn-sm">← Admin</a>
</div>

@if(session('success'))
    <div style="background:#1a2e1a; border:1px solid #2d5a2d; border-radius:.375rem; padding:.75rem 1rem; font-size:.85rem; color:#7ec87e; margin-bottom:1.25rem;">
        {{ session('success') }}
    </div>
@endif

<form method="POST" action="{{ route('branding.update') }}" enctype="multipart/form-data">
    @csrf @method('PATCH')

    {{-- Logo --}}
    <div class="card" style="margin-bottom:1.25rem;">
        <h2 class="serif" style="font-size:1rem; color:var(--text); margin-bottom:1.1rem;">Logo</h2>

        @if($settings['brand_logo'])
            <div style="margin-bottom:1rem; display:flex; align-items:center; gap:1.25rem;">
                <img src="{{ Storage::url($settings['brand_logo']) }}" alt="Logo"
                     style="max-height:64px; max-width:200px; object-fit:contain; border-radius:.25rem; background:#1a1a2e; padding:.4rem .75rem;">
                <label style="display:flex; align-items:center; gap:.4rem; cursor:pointer; font-size:.82rem; color:#e07070;">
                    <input type="checkbox" name="remove_logo" value="1"> Logo entfernen
                </label>
            </div>
        @endif

        <div>
            <label class="form-label">Neues Logo hochladen (PNG, JPG, SVG, WebP · max. 2 MB)</label>
            <input class="form-input" type="file" name="logo" accept="image/png,image/jpeg,image/svg+xml,image/webp"
                   style="padding:.35rem .7rem;">
            @error('logo')<p style="color:#e07070; font-size:.78rem; margin-top:.3rem;">{{ $message }}</p>@enderror
        </div>
        <p style="font-size:.72rem; color:var(--muted); margin-top:.5rem; line-height:1.5;">
            Empfohlen: transparenter Hintergrund (PNG/SVG), Höhe mind. 64 px.<br>
            Ohne Logo wird der Markenname als Text angezeigt.
        </p>
    </div>

    {{-- Name & Tagline --}}
    <div class="card" style="margin-bottom:1.25rem;">
        <h2 class="serif" style="font-size:1rem; color:var(--text); margin-bottom:1.1rem;">Name & Slogan</h2>
        <div style="display:flex; flex-direction:column; gap:.85rem;">
            <div>
                <label class="form-label">Markenname <span style="color:var(--gold);">*</span></label>
                <input class="form-input" type="text" name="brand_name"
                       value="{{ old('brand_name', $settings['brand_name']) }}"
                       placeholder="z.B. disclosure" required maxlength="80">
                <p style="font-size:.72rem; color:var(--muted); margin-top:.3rem;">Erscheint im Header und Browser-Tab.</p>
                @error('brand_name')<p style="color:#e07070; font-size:.78rem; margin-top:.3rem;">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label">Slogan / Untertitel</label>
                <input class="form-input" type="text" name="brand_tagline"
                       value="{{ old('brand_tagline', $settings['brand_tagline']) }}"
                       placeholder="z.B. Dein Event-Tool" maxlength="160">
                <p style="font-size:.72rem; color:var(--muted); margin-top:.3rem;">Optional — erscheint klein unter dem Logo auf der Homepage.</p>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="card" style="margin-bottom:1.75rem;">
        <h2 class="serif" style="font-size:1rem; color:var(--text); margin-bottom:1.1rem;">Footer-Text</h2>
        <input class="form-input" type="text" name="footer_text"
               value="{{ old('footer_text', $settings['footer_text']) }}"
               placeholder="z.B. Mein Verein · gemeinnützig" maxlength="200">
        <p style="font-size:.72rem; color:var(--muted); margin-top:.5rem;">Erscheint ganz unten auf der öffentlichen Seite.</p>
    </div>

    <button type="submit" class="btn btn-gold">Branding speichern</button>
</form>

{{-- Vorschau --}}
<div style="margin-top:2rem; border-top:1px solid var(--border); padding-top:1.5rem;">
    <p style="font-size:.72rem; text-transform:uppercase; letter-spacing:.08em; color:var(--muted); margin-bottom:.75rem;">Vorschau</p>
    <div style="background:#0f0d0a; border:1px solid var(--border); border-radius:.5rem; padding:1.25rem 1.5rem; display:flex; align-items:center; gap:.75rem;">
        @if($settings['brand_logo'])
            <img src="{{ Storage::url($settings['brand_logo']) }}" alt="Logo"
                 style="max-height:36px; object-fit:contain;">
        @else
            <span class="serif" style="font-size:1.3rem; font-weight:700; color:var(--gold);">
                {{ $settings['brand_name'] ?: 'disclosure' }}
            </span>
        @endif
        @if($settings['brand_tagline'])
            <span style="font-size:.72rem; color:var(--muted); border-left:1px solid var(--border); padding-left:.75rem;">
                {{ $settings['brand_tagline'] }}
            </span>
        @endif
    </div>
</div>
@endsection
