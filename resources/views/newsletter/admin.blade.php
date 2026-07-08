@extends('layouts.app')
@section('title', 'Newsletter-Abonnenten')

@section('content')
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.75rem; flex-wrap:wrap; gap:1rem;">
    <h1 class="serif" style="font-size:1.3rem; color:var(--gold);">Newsletter-Abonnenten</h1>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-ghost btn-sm">← Admin</a>
</div>

@if(session('success'))
    <div style="background:#1a2e1a; border:1px solid #2d5a2d; border-radius:.375rem; padding:.75rem 1rem; font-size:.85rem; color:#7ec87e; margin-bottom:1.25rem;">
        {{ session('success') }}
    </div>
@endif

{{-- Stats --}}
<div style="display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; margin-bottom:1.75rem;">
    <div class="card" style="text-align:center;">
        <div class="serif" style="font-size:2rem; font-weight:700; color:var(--gold);">{{ $total }}</div>
        <div class="text-muted" style="font-size:.78rem; text-transform:uppercase; letter-spacing:.06em;">Gesamt</div>
    </div>
    <div class="card" style="text-align:center;">
        <div class="serif" style="font-size:2rem; font-weight:700; color:#7ec87e;">{{ $active }}</div>
        <div class="text-muted" style="font-size:.78rem; text-transform:uppercase; letter-spacing:.06em;">Aktiv</div>
    </div>
    <div class="card" style="text-align:center;">
        <div class="serif" style="font-size:2rem; font-weight:700; color:var(--muted);">{{ $total - $active }}</div>
        <div class="text-muted" style="font-size:.78rem; text-transform:uppercase; letter-spacing:.06em;">Abgemeldet</div>
    </div>
</div>

{{-- Filter & Export --}}
<div style="display:flex; align-items:center; gap:.75rem; flex-wrap:wrap; margin-bottom:1.25rem;">
    <form method="GET" action="{{ route('newsletter.admin') }}" style="display:flex; gap:.5rem; flex:1; min-width:200px;">
        <input class="form-input" type="text" name="search" value="{{ request('search') }}"
               placeholder="E-Mail oder Name suchen…" style="flex:1;">
        <select class="form-input" name="filter" style="width:140px;" onchange="this.form.submit()">
            <option value="">Alle</option>
            <option value="active"   {{ request('filter')==='active'   ? 'selected' : '' }}>Nur aktive</option>
            <option value="inactive" {{ request('filter')==='inactive' ? 'selected' : '' }}>Nur abgem.</option>
        </select>
        <button type="submit" class="btn btn-ghost btn-sm">Suchen</button>
    </form>

    <div style="display:flex; gap:.5rem;">
        <a href="{{ route('newsletter.export') }}" class="btn btn-gold btn-sm">⬇ Alle aktiven als CSV</a>
    </div>
</div>

{{-- Tabelle --}}
@if($subscribers->count())
<div style="overflow-x:auto;">
    <table class="data-table" style="width:100%;">
        <thead>
            <tr>
                <th style="padding:.5rem .75rem; text-align:left;">
                    <input type="checkbox" id="select-all" style="accent-color:var(--gold);">
                </th>
                <th style="padding:.5rem .75rem; text-align:left;">E-Mail</th>
                <th style="padding:.5rem .75rem; text-align:left;">Name</th>
                <th style="padding:.5rem .75rem; text-align:left;">Angemeldet</th>
                <th style="padding:.5rem .75rem; text-align:left;">Status</th>
                <th style="padding:.5rem .75rem; text-align:left;">Aktionen</th>
            </tr>
        </thead>
        <tbody id="subscriber-table">
            @foreach($subscribers as $s)
            <tr style="{{ !$s->active ? 'opacity:.5;' : '' }}">
                <td style="padding:.5rem .75rem;">
                    <input type="checkbox" class="row-check" value="{{ $s->id }}" style="accent-color:var(--gold);">
                </td>
                <td style="padding:.5rem .75rem; font-size:.88rem;">{{ $s->email }}</td>
                <td style="padding:.5rem .75rem; font-size:.85rem; color:var(--muted);">{{ $s->name ?: '—' }}</td>
                <td style="padding:.5rem .75rem; font-size:.8rem; color:var(--muted); white-space:nowrap;">
                    {{ $s->created_at->format('d.m.Y H:i') }}
                </td>
                <td style="padding:.5rem .75rem;">
                    @if($s->active)
                        <span class="badge-green" style="font-size:.72rem; padding:.15rem .5rem; border-radius:.25rem;">aktiv</span>
                    @else
                        <span class="badge-muted" style="font-size:.72rem; padding:.15rem .5rem; border-radius:.25rem;">inaktiv</span>
                    @endif
                </td>
                <td style="padding:.5rem .75rem; display:flex; gap:.5rem; align-items:center;">
                    <form method="POST" action="{{ route('newsletter.toggle', $s->id) }}">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-ghost btn-sm" style="font-size:.72rem; padding:.2rem .5rem;">
                            {{ $s->active ? 'Abmelden' : 'Aktivieren' }}
                        </button>
                    </form>
                    <form method="POST" action="{{ route('newsletter.destroy', $s->id) }}"
                          onsubmit="return confirm('Eintrag wirklich löschen?')">
                        @csrf @method('DELETE')
                        <button type="submit" style="background:none; border:none; cursor:pointer; font-size:.72rem; color:#e07070;">
                            Löschen
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- Auswahl-Export --}}
<div style="margin-top:1rem; display:flex; align-items:center; gap:.75rem; flex-wrap:wrap;">
    <span style="font-size:.78rem; color:var(--muted);" id="selected-count">0 ausgewählt</span>
    <button onclick="exportSelected()" class="btn btn-ghost btn-sm" id="export-selected-btn" disabled>
        ⬇ Auswahl als CSV exportieren
    </button>
</div>

@else
    <div style="text-align:center; padding:3rem 1rem; color:var(--muted); font-size:.9rem;">
        Noch keine Abonnenten gefunden.
    </div>
@endif

<script>
const allBoxes  = () => document.querySelectorAll('.row-check');
const countEl   = document.getElementById('selected-count');
const exportBtn = document.getElementById('export-selected-btn');

document.getElementById('select-all')?.addEventListener('change', function() {
    allBoxes().forEach(b => b.checked = this.checked);
    updateCount();
});

document.querySelectorAll('.row-check').forEach(b => b.addEventListener('change', updateCount));

function updateCount() {
    const checked = [...allBoxes()].filter(b => b.checked).length;
    countEl.textContent = checked + ' ausgewählt';
    exportBtn.disabled = checked === 0;
}

function exportSelected() {
    const ids = [...allBoxes()].filter(b => b.checked).map(b => b.value).join(',');
    window.location.href = '{{ route("newsletter.export") }}?ids=' + ids;
}
</script>
@endsection
