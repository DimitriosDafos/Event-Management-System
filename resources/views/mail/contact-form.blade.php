<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <title>[Kontakt] {{ $contact->subject }}</title>
</head>
<body style="margin:0; padding:0; background:#f4f0f8; font-family:'Segoe UI',Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f0f8; padding:40px 20px;">
    <tr><td align="center">
        <table width="580" cellpadding="0" cellspacing="0" style="max-width:580px; width:100%;">

            <tr>
                <td style="background:#0f0d0a; border-radius:10px 10px 0 0; padding:24px 40px;">
                    <p style="margin:0; font-size:22px; font-weight:700; color:#d4832a; font-family:Georgia,serif;">
                        {{ \App\Models\Setting::get('brand_name','disclosure') }}
                    </p>
                    <p style="margin:4px 0 0; font-size:11px; color:#6b5f4a; text-transform:uppercase; letter-spacing:.08em;">Neue Kontaktnachricht</p>
                </td>
            </tr>

            <tr>
                <td style="background:#ffffff; padding:36px 40px;">
                    <table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e8e0f0; border-radius:6px; margin-bottom:24px;">
                        <tr>
                            <td style="padding:10px 16px; background:#f8f4fc; border-bottom:1px solid #e8e0f0; width:110px;">
                                <span style="font-size:11px; text-transform:uppercase; letter-spacing:.06em; color:#888; font-weight:600;">Von</span>
                            </td>
                            <td style="padding:10px 16px; border-bottom:1px solid #e8e0f0; font-size:14px; color:#333;">
                                {{ $contact->name ?: 'Anonym' }} &lt;{{ $contact->email }}&gt;
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:10px 16px; background:#f8f4fc; border-bottom:1px solid #e8e0f0;">
                                <span style="font-size:11px; text-transform:uppercase; letter-spacing:.06em; color:#888; font-weight:600;">Betreff</span>
                            </td>
                            <td style="padding:10px 16px; border-bottom:1px solid #e8e0f0; font-size:14px; color:#333; font-weight:600;">
                                {{ $contact->subject }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:10px 16px; background:#f8f4fc;">
                                <span style="font-size:11px; text-transform:uppercase; letter-spacing:.06em; color:#888; font-weight:600;">Datum</span>
                            </td>
                            <td style="padding:10px 16px; font-size:14px; color:#333;">
                                {{ $contact->created_at->format('d.m.Y · H:i') }} Uhr
                            </td>
                        </tr>
                    </table>

                    <p style="font-size:12px; text-transform:uppercase; letter-spacing:.06em; color:#aaa; margin:0 0 10px; font-weight:600;">Nachricht</p>
                    <div style="background:#faf8fc; border:1px solid #e8e0f0; border-radius:6px; padding:20px 22px; font-size:15px; color:#333; line-height:1.8; white-space:pre-wrap;">{{ $contact->message }}</div>

                    <table cellpadding="0" cellspacing="0" style="margin-top:28px;">
                        <tr>
                            <td style="background:#d4832a; border-radius:6px;">
                                <a href="mailto:{{ $contact->email }}?subject=Re: {{ rawurlencode('[Kontakt] '.$contact->subject) }}"
                                   style="display:inline-block; padding:12px 24px; font-size:14px; font-weight:700; color:#0f0d0a; text-decoration:none;">
                                    Direkt antworten →
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td style="background:#f0ece8; border-radius:0 0 10px 10px; padding:16px 40px; text-align:center;">
                    <p style="margin:0; font-size:11px; color:#aaa;">
                        Diese E-Mail wurde automatisch durch das Kontaktformular auf
                        <a href="{{ url('/') }}" style="color:#d4832a; text-decoration:none;">{{ url('/') }}</a> generiert.
                    </p>
                </td>
            </tr>

        </table>
    </td></tr>
</table>
</body>
</html>
