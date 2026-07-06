<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>disclosure — @yield('title', 'Event-Tea')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg:      #0f0d0a;
            --surface: #1a1510;
            --border:  #2e2418;
            --gold:    #d4832a;
            --red:     #8b3a3a;
            --text:    #f0e8d8;
            --muted:   #7a6a54;
        }
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; display:flex; flex-direction:column; }
        h1, h2, h3, h4, .serif { font-family: 'Libre Baskerville', serif; }
        .badge { display:inline-block; padding:.12rem .5rem; border-radius:9999px; font-size:.65rem; font-weight:600; text-transform:uppercase; letter-spacing:.05em; }
        .badge-gold { background:rgba(212,131,42,.18); color:var(--gold); border:1px solid rgba(212,131,42,.35); }
        .badge-muted { background:rgba(46,36,24,.8); color:var(--muted); border:1px solid var(--border); }
        .text-gold  { color:var(--gold); }
        .text-muted { color:var(--muted); }
        main { flex: 1; }
        a { color:var(--muted); transition:color .15s; }
        a:hover { color:var(--gold); }
    </style>
</head>
<body>

<!-- Header -->
<header style="background:var(--surface); border-bottom:1px solid var(--border);" class="px-6 py-4">
    <div class="max-w-4xl mx-auto flex items-center justify-between">
        <div>
            <span class="serif font-bold" style="color:var(--gold); font-size:1.4rem; letter-spacing:.05em;">disclosure</span>
            
        </div>
        <a href="{{ route('login') }}" style="font-size:.78rem; color:var(--muted); text-decoration:none;" class="hover:text-gold">Mitglieder-Login &rarr;</a>
    </div>
</header>

<!-- Content -->
<main class="max-w-4xl mx-auto px-6 py-10 w-full">
    @yield('content')
</main>

<!-- Footer -->
<footer style="border-top:1px solid var(--border); margin-top:auto;" class="py-6 text-center">
    <p style="color:var(--muted); font-size:.72rem; letter-spacing:.03em;">event-team &middot; gemeinnützig</p>
</footer>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
