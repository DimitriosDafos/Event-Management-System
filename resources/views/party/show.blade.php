@extends('layouts.app')
@section('title', $party->title)

@section('content')
{{-- ===== PARTY HEADER ===== --}}
<div style="margin-bottom:1.5rem;">
    <div style="display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
        <div>
            <a href="{{ route('parties.index') }}" class="text-muted" style="font-size:.75rem; text-decoration:none;">← Alle Partys</a>
            <h1 class="serif" style="font-size:1.5rem; color:var(--gold); margin:.3rem 0 .2rem;">{{ $party->title }}</h1>
            <div class="text-muted" style="font-size:.85rem; display:flex; gap:1rem; flex-wrap:wrap;">
                <span>📅 {{ $party->date->format('d.m.Y') }}, {{ $party->start_time }}
                    @if($party->end_time) – {{ $party->end_time }} Uhr @endif
                </span>
                @if($party->location)
                    <span>📍 {{ $party->location }}@if($party->address), {{ $party->address }}@endif</span>
                @endif
                @if($party->is_special)
                    <span class="badge badge-red">Special Event</span>
                @endif
            </div>
        </div>

        {{-- Status Badge + Actions --}}
        <div style="display:flex; align-items:center; gap:.6rem; flex-wrap:wrap;">
            @if($party->isDraft())
                <span class="badge badge-muted" style="font-size:.75rem; padding:.2rem .7rem;">Entwurf</span>
            @elseif($party->isPublished())
                <span class="badge badge-gold" style="font-size:.75rem; padding:.2rem .7rem;">Veröffentlicht</span>
            @elseif($party->isPast())
                <span class="badge badge-muted" style="font-size:.75rem; padding:.2rem .7rem;">Vergangen</span>
            @endif

            @if(auth()->user()->isAdmin())
                {{-- Admin: Status ändern --}}
                <div x-data="{open:false}" style="position:relative;">
                    <button @click="open=!open" class="btn btn-ghost btn-sm">Status &darr;</button>
                    <div x-show="open" @click.away="open=false"
                         style="position:absolute; right:0; top:110%; background:var(--surface); border:1px solid var(--border); border-radius:.35rem; min-width:160px; z-index:100; padding:.4rem 0;">
                        @if(!$party->isDraft())
                            <form method="POST" action="{{ route('parties.status', $party->id) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="draft">
                                <button type="submit" class="btn btn-ghost" style="width:100%; text-align:left; border-radius:0; border:none; display:block; padding:.4rem .8rem;">Entwurf</button>
                            </form>
                        @endif
                        @if(!$party->isPublished())
                            <form method="POST" action="{{ route('parties.status', $party->id) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="published">
                                <button type="submit" class="btn btn-ghost" style="width:100%; text-align:left; border-radius:0; border:none; display:block; padding:.4rem .8rem;">Veröffentlichen</button>
                            </form>
                        @endif
                        @if(!$party->isPast())
                            <form method="POST" action="{{ route('parties.status', $party->id) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="past">
                                <button type="submit" class="btn btn-ghost" style="width:100%; text-align:left; border-radius:0; border:none; display:block; padding:.4rem .8rem;">Als vergangen markieren</button>
                            </form>
                        @endif
                    </div>
                </div>
                <a href="{{ route('parties.edit', $party->id) }}" class="btn btn-ghost btn-sm">Bearbeiten</a>
            @endif

            {{-- WhatsApp Teilen (wenn veröffentlicht) --}}
            @if($party->isPublished())
                @php
                    $msg = "🎉 *{$party->title}*\n📅 {$party->date->format('d.m.Y')}, {$party->start_time} Uhr\n";
                    if($party->location) $msg .= "📍 {$party->location}\n";
                    $msg .= "\nJetzt ansehen: ".url('/disclosure');
                @endphp
                <a href="https://wa.me/?text={{ urlencode($msg) }}" target="_blank"
                   class="btn btn-sm" style="background:#25D366; color:#fff;">
                    WhatsApp teilen
                </a>
            @endif
        </div>
    </div>
</div>

{{-- ===== BESCHREIBUNG + FLYER ===== --}}
<div style="display:grid; grid-template-columns:1fr auto; gap:1.5rem; margin-bottom:1.5rem; align-items:start;">
    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:.75rem;">
            <h3 class="serif" style="font-size:.95rem; color:var(--text);">Beschreibung / Motto</h3>
            @if(auth()->user()->isMarketing())
                <button onclick="this.closest('.card').querySelector('.desc-form').style.display='block'; this.style.display='none';"
                        class="btn btn-ghost btn-xs">Bearbeiten</button>
            @endif
        </div>
        @if($party->description)
            <p style="font-size:.85rem; line-height:1.6; color:var(--text); white-space:pre-wrap;">{{ $party->description }}</p>
        @else
            <p class="text-muted" style="font-size:.82rem; font-style:italic;">Noch kein Beschreibungstext.</p>
        @endif

        @if(auth()->user()->isMarketing())
            <form method="POST" action="{{ route('parties.description', $party->id) }}"
                  class="desc-form" style="display:none; margin-top:1rem;">
                @csrf @method('PATCH')
                <textarea name="description" rows="5"
                          style="width:100%; background:var(--bg); border:1px solid var(--border); color:var(--text); padding:.5rem; border-radius:.25rem; font-size:.85rem; font-family:'Inter',sans-serif; resize:vertical;"
                          onfocus="this.style.borderColor='var(--gold)'" onblur="this.style.borderColor='var(--border)'"
                >{{ $party->description }}</textarea>
                <div style="display:flex; gap:.5rem; margin-top:.5rem;">
                    <button type="submit" class="btn btn-gold btn-sm">Speichern</button>
                    <button type="button" class="btn btn-ghost btn-sm"
                            onclick="this.closest('.desc-form').style.display='none'; this.closest('.card').querySelector('button').style.display='inline-block';">Abbrechen</button>
                </div>
            </form>
        @endif
    </div>

    {{-- Flyer --}}
    <div class="card" style="min-width:160px; text-align:center;">
        @if($party->flyer_path)
            <img src="{{ asset('storage/'.$party->flyer_path) }}" alt="Flyer" style="max-width:160px; max-height:220px; border-radius:.25rem; margin-bottom:.6rem;">
        @else
            <div style="width:140px; height:180px; background:var(--border); border-radius:.25rem; display:flex; align-items:center; justify-content:center; margin:0 auto .6rem;">
                <span class="text-muted" style="font-size:.75rem;">Kein Flyer</span>
            </div>
        @endif
        @if(auth()->user()->isMarketing())
            <form method="POST" action="{{ route('parties.flyer', $party->id) }}" enctype="multipart/form-data" style="margin-top:.5rem;">
                @csrf
                <input type="file" name="flyer" accept="image/*" id="flyerInput"
                       style="display:none;" onchange="this.form.submit()">
                <label for="flyerInput" class="btn btn-ghost btn-xs" style="cursor:pointer;">
                    {{ $party->flyer_path ? 'Flyer ändern' : 'Flyer hochladen' }}
                </label>
            </form>
        @endif
    </div>
</div>

{{-- ===== 5 SECTIONS ===== --}}

{{-- 1. BAR-DIENSTPLAN --}}
<div x-data="{open:true}" style="margin-bottom:1rem;">
    <div class="section-header" @click="open=!open">
        <div style="display:flex; align-items:center; gap:.75rem;">
            <span style="color:var(--gold); font-size:.9rem;">🍺</span>
            <span class="serif" style="font-size:.95rem; color:var(--text);">Bar-Dienstplan</span>
            <span class="badge badge-muted">{{ $party->barShifts->count() }} Schichten</span>
        </div>
        <span class="text-muted" x-text="open ? '▲' : '▼'" style="font-size:.75rem;"></span>
    </div>
    <div class="section-body" x-show="open" x-transition>
        @if($party->barShifts->count())
            <table class="data-table" style="margin-bottom:1rem;">
                <thead>
                    <tr>
                        <th style="width:70px;">Schicht</th><th>Bereich</th><th>Von</th><th>Bis</th><th>Wer</th><th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($party->barShifts as $shift)
                        <tr>
                            <td>
                                <span class="badge badge-muted" style="font-size:.7rem;">{{ $shift->sort_order }}</span>
                            </td>
                            <td style="font-weight:500;">{{ $shift->area }}</td>
                            <td>{{ $shift->from }}</td>
                            <td>{{ $shift->till }}</td>
                            <td>{{ $shift->user->name ?? '—' }}</td>
                            <td style="text-align:right;">
                                @if($shift->user_id === auth()->id() || auth()->user()->isAdmin())
                                    <form method="POST" action="{{ route('bar.destroy', [$party->id, $shift->id]) }}" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-ghost btn-xs"
                                                onclick="return confirm('Schicht entfernen?')"
                                                style="color:#c97070;">Entfernen</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-muted" style="font-size:.82rem; margin-bottom:.9rem;">Noch keine Schichten eingetragen.</p>
        @endif

        @if(!$party->isPast())
            <div style="border-top:1px solid var(--border); padding-top:.9rem;">
                <p style="font-size:.72rem; text-transform:uppercase; letter-spacing:.06em; color:var(--muted); margin-bottom:.6rem;">
                    {{ auth()->user()->isAdmin() ? 'Schicht zuweisen' : 'Schicht eintragen' }}
                </p>
                <form method="POST" action="{{ route('bar.store', $party->id) }}" style="display:flex; gap:.6rem; flex-wrap:wrap; align-items:flex-end;">
                    @csrf
                    <div>
                        <label class="form-label">Schicht-Nr.</label>
                        <input type="number" name="sort_order" min="1" max="99" value="1" required
                               style="background:var(--bg); border:1px solid var(--border); color:var(--text); padding:.4rem .65rem; border-radius:.25rem; font-size:.82rem; width:70px;"
                               onfocus="this.style.borderColor='var(--gold)'" onblur="this.style.borderColor='var(--border)'"
                               title="Schicht 1 = früh, Schicht 2 = spät, Schicht 3 = nach Mitternacht...">
                    </div>
                    @if(auth()->user()->isAdmin())
                        <div>
                            <label class="form-label">Mitglied</label>
                            <select name="user_id" required style="background:var(--bg); border:1px solid var(--border); color:var(--text); padding:.4rem .65rem; border-radius:.25rem; font-size:.82rem;">
                                <option value="">— wählen —</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div>
                        <label class="form-label">Bereich</label>
                        <input type="text" name="area" placeholder="z.B. Theke, Ausgabe..." required
                               style="background:var(--bg); border:1px solid var(--border); color:var(--text); padding:.4rem .65rem; border-radius:.25rem; font-size:.82rem; width:140px;"
                               onfocus="this.style.borderColor='var(--gold)'" onblur="this.style.borderColor='var(--border)'">
                    </div>
                    <div>
                        <label class="form-label">Von</label>
                        <input type="time" name="from" required
                               style="background:var(--bg); border:1px solid var(--border); color:var(--text); padding:.4rem .65rem; border-radius:.25rem; font-size:.82rem;">
                    </div>
                    <div>
                        <label class="form-label">Bis</label>
                        <input type="time" name="till" required
                               style="background:var(--bg); border:1px solid var(--border); color:var(--text); padding:.4rem .65rem; border-radius:.25rem; font-size:.82rem;">
                    </div>
                    <button type="submit" class="btn btn-gold btn-sm">Eintragen</button>
                </form>
            </div>
        @endif
    </div>
</div>

{{-- 2. EINLASS / KASSE --}}
<div x-data="{open:true}" style="margin-bottom:1rem;">
    <div class="section-header" @click="open=!open">
        <div style="display:flex; align-items:center; gap:.75rem;">
            <span style="color:var(--gold); font-size:.9rem;">🚪</span>
            <span class="serif" style="font-size:.95rem; color:var(--text);">Einlass / Kasse</span>
            <span class="badge badge-muted">{{ $party->doorAssignments->count() }} Einteilung(en)</span>
        </div>
        <span class="text-muted" x-text="open ? '▲' : '▼'" style="font-size:.75rem;"></span>
    </div>
    <div class="section-body" x-show="open" x-transition>
        @if($party->doorAssignments->count())
            <table class="data-table" style="margin-bottom:1rem;">
                <thead>
                    <tr><th style="width:70px;">Schicht</th><th>Von</th><th>Bis</th><th>Wer</th><th></th></tr>
                </thead>
                <tbody>
                    @foreach($party->doorAssignments as $door)
                        <tr>
                            <td><span class="badge badge-muted" style="font-size:.7rem;">{{ $door->sort_order }}</span></td>
                            <td>{{ $door->from }}</td>
                            <td>{{ $door->till }}</td>
                            <td>{{ $door->user->name ?? '—' }}</td>
                            <td style="text-align:right;">
                                @if(auth()->user()->isAdmin())
                                    <form method="POST" action="{{ route('door.destroy', [$party->id, $door->id]) }}" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-ghost btn-xs"
                                                onclick="return confirm('Entfernen?')"
                                                style="color:#c97070;">Entfernen</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-muted" style="font-size:.82rem; margin-bottom:.9rem;">Noch keine Einteilung.</p>
        @endif

        @if(!$party->isPast())
            <div style="border-top:1px solid var(--border); padding-top:.9rem;">
                <p style="font-size:.72rem; text-transform:uppercase; letter-spacing:.06em; color:var(--muted); margin-bottom:.6rem;">Einlass eintragen</p>
                <form method="POST" action="{{ route('door.store', $party->id) }}" style="display:flex; gap:.6rem; flex-wrap:wrap; align-items:flex-end;">
                    @csrf
                    @if(auth()->user()->isAdmin())
                        <div>
                            <label class="form-label">Schicht-Nr.</label>
                            <input type="number" name="sort_order" min="1" max="99" value="1" required
                                   style="background:var(--bg); border:1px solid var(--border); color:var(--text); padding:.4rem .65rem; border-radius:.25rem; font-size:.82rem; width:70px;"
                                   onfocus="this.style.borderColor='var(--gold)'" onblur="this.style.borderColor='var(--border)'">
                        </div>
                    @else
                        <input type="hidden" name="sort_order" value="1">
                    @endif
                    <div>
                        <label class="form-label">Mitglied</label>
                        <select name="user_id" required style="background:var(--bg); border:1px solid var(--border); color:var(--text); padding:.4rem .65rem; border-radius:.25rem; font-size:.82rem;">
                            <option value="">— wählen —</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Von</label>
                        <input type="time" name="from" required
                               style="background:var(--bg); border:1px solid var(--border); color:var(--text); padding:.4rem .65rem; border-radius:.25rem; font-size:.82rem;">
                    </div>
                    <div>
                        <label class="form-label">Bis</label>
                        <input type="time" name="till" required
                               style="background:var(--bg); border:1px solid var(--border); color:var(--text); padding:.4rem .65rem; border-radius:.25rem; font-size:.82rem;">
                    </div>
                    <button type="submit" class="btn btn-gold btn-sm">Eintragen</button>
                </form>
            </div>
        @endif
    </div>
</div>

{{-- 3. DJ-LINEUP --}}
<div x-data="{open:true}" style="margin-bottom:1rem;">
    <div class="section-header" @click="open=!open">
        <div style="display:flex; align-items:center; gap:.75rem;">
            <span style="color:var(--gold); font-size:.9rem;">🎧</span>
            <span class="serif" style="font-size:.95rem; color:var(--text);">DJ-Lineup</span>
            <span class="badge badge-muted">{{ $party->djLineup->count() }} DJ(s)</span>
        </div>
        <span class="text-muted" x-text="open ? '▲' : '▼'" style="font-size:.75rem;"></span>
    </div>
    <div class="section-body" x-show="open" x-transition>
        @if($party->djLineup->count())
            <table class="data-table" style="margin-bottom:1rem;">
                <thead>
                    <tr><th style="width:70px;">Slot</th><th>DJ Name</th><th>Von</th><th>Bis</th><th>Stil</th><th>Website</th><th></th></tr>
                </thead>
                <tbody>
                    @foreach($party->djLineup as $dj)
                        <tr>
                            <td><span class="badge badge-muted" style="font-size:.7rem;">{{ $dj->sort_order }}</span></td>
                            <td style="font-weight:500; color:var(--gold);">{{ $dj->dj_name }}</td>
                            <td>{{ $dj->from }}</td>
                            <td>{{ $dj->till }}</td>
                            <td>{{ $dj->style ?? '—' }}</td>
                            <td>
                                @if($dj->website)
                                    <a href="{{ $dj->website }}" target="_blank" style="color:var(--muted); font-size:.78rem;">Link</a>
                                @else
                                    —
                                @endif
                            </td>
                            <td style="text-align:right;">
                                @if(auth()->user()->isDj())
                                    <form method="POST" action="{{ route('dj.destroy', [$party->id, $dj->id]) }}" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-ghost btn-xs"
                                                onclick="return confirm('DJ entfernen?')"
                                                style="color:#c97070;">Entfernen</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-muted" style="font-size:.82rem; margin-bottom:.9rem;">Noch kein DJ-Lineup eingetragen.</p>
        @endif

        @if(auth()->user()->isDj() && !$party->isPast())
            <div style="border-top:1px solid var(--border); padding-top:.9rem;">
                <p style="font-size:.72rem; text-transform:uppercase; letter-spacing:.06em; color:var(--muted); margin-bottom:.6rem;">DJ hinzufügen</p>
                <form method="POST" action="{{ route('dj.store', $party->id) }}" style="display:flex; gap:.6rem; flex-wrap:wrap; align-items:flex-end;">
                    @csrf
                    <div>
                        <label class="form-label">Slot-Nr.</label>
                        <input type="number" name="sort_order" min="1" max="99" value="1" required
                               style="background:var(--bg); border:1px solid var(--border); color:var(--text); padding:.4rem .65rem; border-radius:.25rem; font-size:.82rem; width:70px;"
                               onfocus="this.style.borderColor='var(--gold)'" onblur="this.style.borderColor='var(--border)'"
                               title="1 = erster DJ, 2 = zweiter DJ usw. — bestimmt die Reihenfolge">
                    </div>
                    <div>
                        <label class="form-label">DJ-Name</label>
                        <input type="text" name="dj_name" required
                               style="background:var(--bg); border:1px solid var(--border); color:var(--text); padding:.4rem .65rem; border-radius:.25rem; font-size:.82rem; width:140px;"
                               onfocus="this.style.borderColor='var(--gold)'" onblur="this.style.borderColor='var(--border)'">
                    </div>
                    <div>
                        <label class="form-label">Von</label>
                        <input type="time" name="from" required
                               style="background:var(--bg); border:1px solid var(--border); color:var(--text); padding:.4rem .65rem; border-radius:.25rem; font-size:.82rem;">
                    </div>
                    <div>
                        <label class="form-label">Bis</label>
                        <input type="time" name="till" required
                               style="background:var(--bg); border:1px solid var(--border); color:var(--text); padding:.4rem .65rem; border-radius:.25rem; font-size:.82rem;">
                    </div>
                    <div>
                        <label class="form-label">Stil</label>
                        <input type="text" name="style" placeholder="Techno, House..."
                               style="background:var(--bg); border:1px solid var(--border); color:var(--text); padding:.4rem .65rem; border-radius:.25rem; font-size:.82rem; width:120px;"
                               onfocus="this.style.borderColor='var(--gold)'" onblur="this.style.borderColor='var(--border)'">
                    </div>
                    <div>
                        <label class="form-label">Website</label>
                        <input type="url" name="website" placeholder="https://..."
                               style="background:var(--bg); border:1px solid var(--border); color:var(--text); padding:.4rem .65rem; border-radius:.25rem; font-size:.82rem; width:150px;"
                               onfocus="this.style.borderColor='var(--gold)'" onblur="this.style.borderColor='var(--border)'">
                    </div>
                    <button type="submit" class="btn btn-gold btn-sm">Hinzufügen</button>
                </form>
            </div>
        @endif
    </div>
</div>

{{-- 4. TODO-LISTE --}}
<div x-data="{open:true}" style="margin-bottom:1rem;">
    <div class="section-header" @click="open=!open">
        <div style="display:flex; align-items:center; gap:.75rem;">
            <span style="color:var(--gold); font-size:.9rem;">✅</span>
            <span class="serif" style="font-size:.95rem; color:var(--text);">ToDo-Liste</span>
            @php
                $doneTodos = $party->todos->where('done', true)->count();
                $totalTodos = $party->todos->count();
            @endphp
            <span class="badge badge-muted">{{ $doneTodos }}/{{ $totalTodos }} erledigt</span>
        </div>
        <span class="text-muted" x-text="open ? '▲' : '▼'" style="font-size:.75rem;"></span>
    </div>
    <div class="section-body" x-show="open" x-transition>
        @if($party->todos->count())
            <table class="data-table" style="margin-bottom:1rem;">
                <thead>
                    <tr>
                        <th>Datum</th><th>Uhrzeit</th><th>Was</th><th>Wer</th>
                        <th>Kosten</th><th>Erledigt</th><th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($party->todos as $todo)
                        <tr style="{{ $todo->done ? 'opacity:.55;' : '' }}">
                            <td style="font-size:.78rem;">{{ $todo->due_date ? $todo->due_date->format('d.m.') : '—' }}</td>
                            <td style="font-size:.78rem;">{{ $todo->due_time ?? '—' }}</td>
                            <td style="{{ $todo->done ? 'text-decoration:line-through;' : '' }}">{{ $todo->what }}</td>
                            <td>{{ $todo->users->pluck('name')->join(', ') ?: '—' }}</td>
                            <td>
                                @if($todo->costs > 0)
                                    <span class="text-gold">{{ number_format($todo->costs, 2, ',', '.') }} €</span>
                                @else
                                    {{-- Kosten eintragen (eigene todos oder admin) --}}
                                    @if($todo->isAssigned(auth()->id()) || auth()->user()->isAdmin())
                                        <form method="POST" action="{{ route('todos.costs', [$party->id, $todo->id]) }}" style="display:flex; gap:.3rem;">
                                            @csrf @method('PATCH')
                                            <input type="number" name="costs" step="0.01" min="0" placeholder="0,00"
                                                   style="background:var(--bg); border:1px solid var(--border); color:var(--text); padding:.2rem .4rem; border-radius:.2rem; font-size:.75rem; width:65px;"
                                                   onfocus="this.style.borderColor='var(--gold)'" onblur="this.style.borderColor='var(--border)'">
                                            <button type="submit" class="btn btn-ghost btn-xs">€</button>
                                        </form>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                @endif
                            </td>
                            <td>
                                @if($todo->isAssigned(auth()->id()) || auth()->user()->isAdmin())
                                    <form method="POST" action="{{ route('todos.done', [$party->id, $todo->id]) }}" style="display:inline;">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-sm"
                                                style="background:{{ $todo->done ? 'rgba(60,110,60,.25)' : 'var(--border)' }}; color:{{ $todo->done ? '#7ec87e' : 'var(--muted)' }};">
                                            {{ $todo->done ? '✓ Erledigt' : 'Erledigen' }}
                                        </button>
                                    </form>
                                @else
                                    @if($todo->done)
                                        <span class="badge badge-green">✓</span>
                                    @else
                                        <span class="badge badge-muted">Offen</span>
                                    @endif
                                @endif
                            </td>
                            <td style="text-align:right;">
                                @if(auth()->user()->isAdmin())
                                    <div x-data="{editOpen:false}" style="display:inline-block; position:relative;">
                                        <button @click="editOpen=!editOpen" class="btn btn-ghost btn-xs">Bearbeiten</button>
                                        <div x-show="editOpen" @click.away="editOpen=false"
                                             style="position:absolute; right:0; top:110%; background:var(--surface); border:1px solid var(--border); border-radius:.35rem; min-width:280px; z-index:50; padding:.75rem;">
                                            <form method="POST" action="{{ route('todos.admin_update', [$party->id, $todo->id]) }}">
                                                @csrf @method('PATCH')
                                                <div style="margin-bottom:.5rem;">
                                                    <label class="form-label">Was</label>
                                                    <input type="text" name="what" value="{{ $todo->what }}" required
                                                           style="background:var(--bg); border:1px solid var(--border); color:var(--text); padding:.35rem .6rem; border-radius:.2rem; font-size:.8rem; width:100%;">
                                                </div>
                                                <div style="display:grid; grid-template-columns:1fr 1fr; gap:.4rem; margin-bottom:.5rem;">
                                                    <div>
                                                        <label class="form-label">Datum</label>
                                                        <input type="date" name="due_date" value="{{ $todo->due_date?->format('Y-m-d') }}"
                                                               style="background:var(--bg); border:1px solid var(--border); color:var(--text); padding:.35rem .5rem; border-radius:.2rem; font-size:.78rem; width:100%;">
                                                    </div>
                                                    <div>
                                                        <label class="form-label">Uhrzeit</label>
                                                        <input type="time" name="due_time" value="{{ $todo->due_time }}"
                                                               style="background:var(--bg); border:1px solid var(--border); color:var(--text); padding:.35rem .5rem; border-radius:.2rem; font-size:.78rem; width:100%;">
                                                    </div>
                                                </div>
                                                <div style="margin-bottom:.5rem;">
                                                    <label class="form-label">Wer (mehrere möglich)</label>
                                                    <div style="display:flex; flex-direction:column; gap:.2rem; max-height:100px; overflow-y:auto; padding:.3rem; background:var(--bg); border:1px solid var(--border); border-radius:.2rem;">
                                                        @foreach($users as $u)
                                                            <label style="display:flex; align-items:center; gap:.4rem; font-size:.78rem; color:var(--text); cursor:pointer;">
                                                                <input type="checkbox" name="user_ids[]" value="{{ $u->id }}"
                                                                       {{ $todo->users->contains($u->id) ? 'checked' : '' }}
                                                                       style="accent-color:var(--gold);">
                                                                {{ $u->name }}
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <div style="margin-bottom:.5rem;">
                                                    <label class="form-label">Kosten (€)</label>
                                                    <input type="number" name="costs" step="0.01" min="0" value="{{ $todo->costs }}"
                                                           style="background:var(--bg); border:1px solid var(--border); color:var(--text); padding:.35rem .5rem; border-radius:.2rem; font-size:.78rem; width:100%;">
                                                </div>
                                                <div style="display:flex; gap:.4rem;">
                                                    <button type="submit" class="btn btn-gold btn-xs">Speichern</button>
                                                    <button type="button" @click="editOpen=false" class="btn btn-ghost btn-xs">Abbrechen</button>
                                                </div>
                                            </form>
                                            <hr style="border-color:var(--border); margin:.6rem 0;">
                                            <form method="POST" action="{{ route('todos.destroy', [$party->id, $todo->id]) }}">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-ghost btn-xs" style="color:#c97070;" onclick="return confirm('ToDo löschen?')">Löschen</button>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-muted" style="font-size:.82rem; margin-bottom:.9rem;">Noch keine ToDos.</p>
        @endif

        @if(true)
            <div style="border-top:1px solid var(--border); padding-top:.9rem;">
                <p style="font-size:.72rem; text-transform:uppercase; letter-spacing:.06em; color:var(--muted); margin-bottom:.6rem;">ToDo hinzufügen</p>
                <form method="POST" action="{{ route('todos.store', $party->id) }}" style="display:flex; gap:.6rem; flex-wrap:wrap; align-items:flex-end;">
                    @csrf
                    <div>
                        <label class="form-label">Was</label>
                        <input type="text" name="what" required placeholder="Aufgabe..."
                               style="background:var(--bg); border:1px solid var(--border); color:var(--text); padding:.4rem .65rem; border-radius:.25rem; font-size:.82rem; width:200px;"
                               onfocus="this.style.borderColor='var(--gold)'" onblur="this.style.borderColor='var(--border)'">
                    </div>
                    <div>
                        <label class="form-label">Datum</label>
                        <input type="date" name="due_date"
                               style="background:var(--bg); border:1px solid var(--border); color:var(--text); padding:.4rem .65rem; border-radius:.25rem; font-size:.82rem;">
                    </div>
                    <div>
                        <label class="form-label">Uhrzeit</label>
                        <input type="time" name="due_time"
                               style="background:var(--bg); border:1px solid var(--border); color:var(--text); padding:.4rem .65rem; border-radius:.25rem; font-size:.82rem;">
                    </div>
                    <div>
                        <label class="form-label">Wer (mehrere möglich)</label>
                        <div style="display:flex; flex-direction:column; gap:.25rem; padding:.35rem .5rem; background:var(--bg); border:1px solid var(--border); border-radius:.25rem; max-height:110px; overflow-y:auto; min-width:140px;">
                            @foreach($users as $u)
                                <label style="display:flex; align-items:center; gap:.4rem; font-size:.82rem; color:var(--text); cursor:pointer; white-space:nowrap;">
                                    <input type="checkbox" name="user_ids[]" value="{{ $u->id }}" style="accent-color:var(--gold);">
                                    {{ $u->name }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <button type="submit" class="btn btn-gold btn-sm">Hinzufügen</button>
                </form>
            </div>
        @endif
    </div>
</div>

{{-- 5. FINANZEN --}}
<div x-data="{open:true}" style="margin-bottom:1rem;">
    <div class="section-header" @click="open=!open">
        <div style="display:flex; align-items:center; gap:.75rem;">
            <span style="color:var(--gold); font-size:.9rem;">💶</span>
            <span class="serif" style="font-size:.95rem; color:var(--text);">Finanzen</span>
            @php
                $saldo = $party->balance();
            @endphp
            <span class="badge {{ $saldo >= 0 ? 'badge-green' : 'badge-red' }}">
                Saldo: {{ number_format($saldo, 2, ',', '.') }} €
            </span>
        </div>
        <span class="text-muted" x-text="open ? '▲' : '▼'" style="font-size:.75rem;"></span>
    </div>
    <div class="section-body" x-show="open" x-transition>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">

            {{-- Ausgaben (aus Todos) --}}
            <div>
                <h4 style="font-size:.72rem; text-transform:uppercase; letter-spacing:.06em; color:var(--muted); margin-bottom:.75rem;">Ausgaben (aus ToDos)</h4>
                @if($party->todos->where('costs', '>', 0)->count())
                    <table class="data-table" style="margin-bottom:.5rem;">
                        <thead>
                            <tr><th>Was</th><th>Wer</th><th>Betrag</th></tr>
                        </thead>
                        <tbody>
                            @foreach($party->todos->where('costs', '>', 0) as $todo)
                                <tr>
                                    <td style="font-size:.78rem;">{{ $todo->what }}</td>
                                    <td style="font-size:.78rem;">{{ $todo->users->pluck('name')->join(', ') ?: '—' }}</td>
                                    <td style="color:var(--gold); font-size:.82rem;">{{ number_format($todo->costs, 2, ',', '.') }} €</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-muted" style="font-size:.8rem;">Keine Ausgaben eingetragen.</p>
                @endif
                <div style="border-top:1px solid var(--border); padding:.5rem 0; display:flex; justify-content:space-between;">
                    <span style="font-size:.8rem; font-weight:600;">Gesamt Ausgaben</span>
                    <span style="color:#c97070; font-weight:600;">{{ number_format($party->totalExpenses(), 2, ',', '.') }} €</span>
                </div>
            </div>

            {{-- Einnahmen --}}
            <div>
                <h4 style="font-size:.72rem; text-transform:uppercase; letter-spacing:.06em; color:var(--muted); margin-bottom:.75rem;">Einnahmen</h4>
                @if($party->income->count())
                    <table class="data-table" style="margin-bottom:.5rem;">
                        <thead>
                            <tr><th>Beschreibung</th><th>Datum</th><th>Betrag</th><th></th></tr>
                        </thead>
                        <tbody>
                            @foreach($party->income as $inc)
                                <tr>
                                    <td style="font-size:.78rem;">{{ $inc->description }}</td>
                                    <td style="font-size:.78rem;">{{ $inc->date->format('d.m.') }}</td>
                                    <td style="color:#7ec87e; font-size:.82rem;">{{ number_format($inc->amount, 2, ',', '.') }} €</td>
                                    <td>
                                        @if(auth()->user()->isAdmin())
                                            <form method="POST" action="{{ route('income.destroy', [$party->id, $inc->id]) }}" style="display:inline;">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-ghost btn-xs"
                                                        onclick="return confirm('Löschen?')"
                                                        style="color:#c97070;">&times;</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-muted" style="font-size:.8rem;">Noch keine Einnahmen eingetragen.</p>
                @endif
                <div style="border-top:1px solid var(--border); padding:.5rem 0; display:flex; justify-content:space-between;">
                    <span style="font-size:.8rem; font-weight:600;">Gesamt Einnahmen</span>
                    <span style="color:#7ec87e; font-weight:600;">{{ number_format($party->totalIncome(), 2, ',', '.') }} €</span>
                </div>

                @if(auth()->user()->isAdmin() && $party->isPast())
                    <div style="border-top:1px solid var(--border); padding-top:.75rem; margin-top:.5rem;">
                        <p style="font-size:.72rem; text-transform:uppercase; letter-spacing:.06em; color:var(--muted); margin-bottom:.6rem;">Einnahme eintragen</p>
                        <form method="POST" action="{{ route('income.store', $party->id) }}" style="display:flex; flex-direction:column; gap:.4rem;">
                            @csrf
                            <input type="text" name="description" required placeholder="z.B. Spenden-Eintritte"
                                   style="background:var(--bg); border:1px solid var(--border); color:var(--text); padding:.4rem .65rem; border-radius:.25rem; font-size:.82rem;"
                                   onfocus="this.style.borderColor='var(--gold)'" onblur="this.style.borderColor='var(--border)'">
                            <div style="display:flex; gap:.4rem;">
                                <input type="number" name="amount" step="0.01" min="0" required placeholder="0,00"
                                       style="background:var(--bg); border:1px solid var(--border); color:var(--text); padding:.4rem .65rem; border-radius:.25rem; font-size:.82rem; width:100px;">
                                <input type="date" name="date" required value="{{ $party->date->format('Y-m-d') }}"
                                       style="background:var(--bg); border:1px solid var(--border); color:var(--text); padding:.4rem .65rem; border-radius:.25rem; font-size:.82rem;">
                                <button type="submit" class="btn btn-gold btn-sm">Eintragen</button>
                            </div>
                        </form>
                    </div>
                @elseif(auth()->user()->isAdmin() && !$party->isPast())
                    <p class="text-muted" style="font-size:.75rem; margin-top:.6rem; font-style:italic;">Einnahmen können erst nach der Party eingetragen werden (Status: Vergangen).</p>
                @endif
            </div>
        </div>

        {{-- Saldo Box --}}
        <div style="margin-top:1rem; border-top:1px solid var(--border); padding-top:.75rem; display:flex; justify-content:flex-end;">
            <div style="background:var(--border); border-radius:.375rem; padding:.6rem 1.25rem; text-align:right;">
                <span style="color:var(--muted); font-size:.72rem; text-transform:uppercase; letter-spacing:.05em; display:block;">Saldo</span>
                <span class="serif" style="font-size:1.3rem; font-weight:700; color:{{ $saldo >= 0 ? '#7ec87e' : '#c97070' }};">
                    {{ $saldo >= 0 ? '+' : '' }}{{ number_format($saldo, 2, ',', '.') }} €
                </span>
            </div>
        </div>
    </div>
</div>
@endsection
