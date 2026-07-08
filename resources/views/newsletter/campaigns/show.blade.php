@extends('layouts.app')
@section('title', 'Newsletter Details')

@section('content')
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.75rem; flex-wrap:wrap; gap:1rem;">
    <h1 class="serif" style="font-size:1.3rem; color:var(--gold);">Newsletter Details</h1>
    <a href="{{ route('newsletter.campaigns.index') }}" class="btn btn-ghost btn-sm">← Übersicht</a>
</div>

{{-- Meta --}}
<div class="card" style="margin-bottom:1.25rem;">
    <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(160px,1fr)); gap:1rem; margin-bottom:1.25rem;">
        <div>
            <p style="font-size:.72rem; text-transform:uppercase; letter-spacing:.06em; color:var(--muted); margin:0 0 .25rem;">Betreff</p>
            <p style="font-weight:600; color:var(--text); margin:0;">{{ $newsletter->subject }}</p>
        </div>
        <div>
            <p style="font-size:.72rem; text-transform:uppercase; letter-spacing:.06em; color:var(--muted); margin:0 0 .25rem;">Gesendet am</p>
            <p style="color:var(--text); margin:0;">{{ $newsletter->sent_at->format('d.m.Y · H:i') }} Uhr</p>
        </div>
        <div>
            <p style="font-size:.72rem; text-transform:uppercase; letter-spacing:.06em; color:var(--muted); margin:0 0 .25rem;">Gesendet von</p>
            <p style="color:var(--text); margin:0;">{{ $newsletter->sentBy->name ?? '—' }}</p>
        </div>
        <div>
            <p style="font-size:.72rem; text-transform:uppercase; letter-spacing:.06em; color:var(--muted); margin:0 0 .25rem;">Empfänger</p>
            <p style="margin:0;">
                <span style="color:#7ec87e; font-weight:600;">{{ $newsletter->recipient_count - $newsletter->failed_count }} ✓</span>
                @if($newsletter->failed_count > 0)
                    <span style="color:#e07070; margin-left:.6rem;">{{ $newsletter->failed_count }} ✗</span>
                @endif
            </p>
        </div>
    </div>

    {{-- Text --}}
    <div style="border-top:1px solid var(--border); padding-top:1rem;">
        <p style="font-size:.72rem; text-transform:uppercase; letter-spacing:.06em; color:var(--muted); margin:0 0 .6rem;">Newsletter-Text</p>
        <div style="background:#0f0d0a; border:1px solid var(--border); border-radius:.375rem; padding:1rem 1.25rem; font-size:.88rem; color:var(--text); line-height:1.75; white-space:pre-wrap;">{{ $newsletter->body }}</div>
    </div>
</div>

{{-- Empfängerliste --}}
<h2 class="serif" style="font-size:1rem; color:var(--muted); margin-bottom:.75rem;">Empfängerliste ({{ $newsletter->sends->count() }})</h2>

<div style="overflow-x:auto;">
    <table class="data-table" style="width:100%;">
        <thead>
            <tr>
                <th style="padding:.5rem .75rem; text-align:left;">E-Mail</th>
                <th style="padding:.5rem .75rem; text-align:left;">Name</th>
                <th style="padding:.5rem .75rem; text-align:left;">Gesendet um</th>
                <th style="padding:.5rem .75rem; text-align:left;">Status</th>
                <th style="padding:.5rem .75rem; text-align:left;">Fehler</th>
            </tr>
        </thead>
        <tbody>
            @foreach($newsletter->sends->sortBy('email') as $send)
            <tr style="{{ !$send->success ? 'opacity:.6;' : '' }}">
                <td style="padding:.45rem .75rem; font-size:.85rem;">{{ $send->email }}</td>
                <td style="padding:.45rem .75rem; font-size:.82rem; color:var(--muted);">{{ $send->name ?: '—' }}</td>
                <td style="padding:.45rem .75rem; font-size:.78rem; color:var(--muted); white-space:nowrap;">
                    {{ $send->sent_at->format('H:i:s') }}
                </td>
                <td style="padding:.45rem .75rem;">
                    @if($send->success)
                        <span class="badge-green" style="font-size:.7rem; padding:.15rem .5rem; border-radius:.25rem;">✓ OK</span>
                    @else
                        <span class="badge-red" style="font-size:.7rem; padding:.15rem .5rem; border-radius:.25rem;">✗ Fehler</span>
                    @endif
                </td>
                <td style="padding:.45rem .75rem; font-size:.75rem; color:#e07070; max-width:260px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                    {{ $send->error ?: '—' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
