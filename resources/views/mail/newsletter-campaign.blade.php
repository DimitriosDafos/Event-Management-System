<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $subject }}</title>
</head>
<body style="margin:0; padding:0; background:#f4f0f8; font-family:'Segoe UI',Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f0f8; padding:40px 20px;">
    <tr>
        <td align="center">
            <table width="580" cellpadding="0" cellspacing="0" style="max-width:580px; width:100%;">

                {{-- Header --}}
                <tr>
                    <td style="background:#0f0d0a; border-radius:10px 10px 0 0; padding:28px 40px; text-align:center;">
                        <p style="margin:0; font-size:24px; font-weight:700; color:#d4832a; letter-spacing:.04em; font-family:Georgia,serif;">
                            {{ \App\Models\Setting::get('brand_name','disclosure') }}
                        </p>
                        <p style="margin:6px 0 0; font-size:12px; color:#6b5f4a; letter-spacing:.08em; text-transform:uppercase;">Newsletter</p>
                    </td>
                </tr>

                {{-- Body --}}
                <tr>
                    <td style="background:#ffffff; padding:40px 40px 32px;">
                        @if($subscriberName)
                            <p style="margin:0 0 20px; font-size:16px; color:#444;">Hallo {{ $subscriberName }},</p>
                        @else
                            <p style="margin:0 0 20px; font-size:16px; color:#444;">Hallo,</p>
                        @endif

                        <div style="font-size:15px; color:#333; line-height:1.8; white-space:pre-wrap;">{{ $body }}</div>

                        <table cellpadding="0" cellspacing="0" style="margin:32px 0 8px;">
                            <tr>
                                <td style="background:#d4832a; border-radius:6px;">
                                    <a href="{{ url('/') }}"
                                       style="display:inline-block; padding:13px 28px; font-size:14px; font-weight:700; color:#0f0d0a; text-decoration:none; letter-spacing:.03em;">
                                        Zur Homepage →
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                {{-- Footer --}}
                <tr>
                    <td style="background:#f0ece8; border-radius:0 0 10px 10px; padding:20px 40px; text-align:center;">
                        <p style="margin:0 0 6px; font-size:12px; color:#999; line-height:1.6;">
                            {{ \App\Models\Setting::get('footer_text','event-team · gemeinnützig') }} ·
                            <a href="{{ url('/') }}" style="color:#d4832a; text-decoration:none;">{{ url('/') }}</a>
                        </p>
                        <p style="margin:0; font-size:11px; color:#bbb;">
                            Du erhältst diese E-Mail, weil du dich für unseren Newsletter angemeldet hast.<br>
                            Zum Abmelden antworte auf diese E-Mail mit dem Betreff „Abmelden".
                        </p>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>
</body>
</html>
