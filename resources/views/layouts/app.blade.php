<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>disclosure — @yield('title', 'Übersicht')</title>
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
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; }
        h1, h2, h3, h4, .serif { font-family: 'Libre Baskerville', serif; }

        /* Buttons */
        .btn { display:inline-block; padding:.35rem .9rem; border-radius:.25rem; font-size:.8rem; font-weight:600; cursor:pointer; transition:opacity .15s; border:none; }
        .btn-gold  { background:var(--gold);    color:var(--bg);   }
        .btn-red   { background:var(--red);     color:var(--text); }
        .btn-ghost { background:var(--surface); color:var(--muted); border:1px solid var(--border); }
        .btn:hover { opacity:.82; }
        .btn-sm { padding:.2rem .6rem; font-size:.72rem; }
        .btn-xs { padding:.1rem .4rem; font-size:.67rem; }

        /* Card */
        .card { background:var(--surface); border:1px solid var(--border); border-radius:.5rem; padding:1.25rem; }

        /* Form */
        .form-group { margin-bottom:.9rem; }
        .form-label { display:block; color:var(--muted); font-size:.7rem; text-transform:uppercase; letter-spacing:.06em; margin-bottom:.3rem; }
        .form-input, .form-select, .form-textarea {
            width:100%; background:var(--bg); border:1px solid var(--border);
            color:var(--text); padding:.4rem .7rem; border-radius:.25rem; font-size:.875rem;
            font-family:'Inter',sans-serif;
        }
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline:none; border-color:var(--gold);
        }
        .form-textarea { resize:vertical; min-height:80px; }
        select option { background:var(--surface); color:var(--text); }

        /* Tables */
        .data-table { width:100%; border-collapse:collapse; font-size:.83rem; }
        .data-table thead th {
            color:var(--muted); font-size:.67rem; text-transform:uppercase;
            letter-spacing:.07em; border-bottom:1px solid var(--border);
            padding:.5rem .75rem; text-align:left; font-weight:500;
        }
        .data-table tbody td { padding:.55rem .75rem; border-bottom:1px solid rgba(46,36,24,.6); vertical-align:middle; }
        .data-table tbody tr:last-child td { border-bottom:none; }
        .data-table tbody tr:hover { background:rgba(46,36,24,.4); }

        /* Badges */
        .badge { display:inline-block; padding:.12rem .5rem; border-radius:9999px; font-size:.65rem; font-weight:600; text-transform:uppercase; letter-spacing:.05em; }
        .badge-gold   { background:rgba(212,131,42,.18); color:var(--gold);  border:1px solid rgba(212,131,42,.35); }
        .badge-red    { background:rgba(139,58,58,.2);   color:#c97070;     border:1px solid rgba(139,58,58,.4);  }
        .badge-muted  { background:rgba(46,36,24,.8);    color:var(--muted); border:1px solid var(--border);       }
        .badge-green  { background:rgba(60,110,60,.25);  color:#7ec87e;     border:1px solid rgba(60,110,60,.4);  }

        /* Section accordion */
        .section-header {
            display:flex; align-items:center; justify-content:space-between;
            padding:.75rem 1rem; background:var(--surface);
            border:1px solid var(--border); border-radius:.375rem;
            cursor:pointer; user-select:none;
        }
        .section-header:hover { border-color:var(--muted); }
        .section-body { border:1px solid var(--border); border-top:none; border-radius:0 0 .375rem .375rem; padding:1rem; background:rgba(15,13,10,.5); }

        /* Nav */
        .nav-link { color:var(--muted); font-size:.82rem; transition:color .15s; }
        .nav-link:hover, .nav-link.active { color:var(--gold); }

        /* Alerts */
        .alert-success { background:rgba(212,131,42,.12); border:1px solid rgba(212,131,42,.35); color:var(--gold); }
        .alert-error   { background:rgba(139,58,58,.15);  border:1px solid rgba(139,58,58,.4);  color:#c97070;  }
        .alert { border-radius:.25rem; padding:.5rem .9rem; font-size:.83rem; margin-bottom:.75rem; }

        /* Misc */
        .divider { border:none; border-top:1px solid var(--border); margin:1.25rem 0; }
        .text-gold   { color:var(--gold); }
        .text-muted  { color:var(--muted); }
        .text-red    { color:#c97070; }
        .text-green  { color:#7ec87e; }
        a { color:var(--muted); transition:color .15s; }
        a:hover { color:var(--gold); }
    </style>
    @stack('head')
</head>
<body>

<!-- Navigation -->
<nav style="background:var(--surface); border-bottom:1px solid var(--border);" class="px-6 py-3 flex items-center justify-between sticky top-0 z-50">
    <a href="{{ route('dashboard') }}" class="serif text-xl font-bold" style="color:var(--gold); letter-spacing:.05em; text-decoration:none;">disclosure</a>
    <div class="flex items-center gap-5">
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">Übersicht</a>
        <a href="{{ route('parties.index') }}" class="nav-link {{ request()->routeIs('parties.*') ? 'active' : '' }}">Partys</a>
        @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}">Admin</a>
        @endif
        <span style="color:var(--border);">|</span>
        <span class="text-muted" style="font-size:.75rem;">{{ auth()->user()->name }}
            <span class="badge badge-muted ml-1">{{ auth()->user()->primaryRole() }}</span>
        </span>
        <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button type="submit" class="nav-link" style="background:none; border:none; cursor:pointer;">Abmelden</button>
        </form>
    </div>
</nav>

<!-- Flash Messages -->
<div class="px-6 pt-4 max-w-6xl mx-auto">
    @if(session('success'))
        <div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,4500)" class="alert alert-success flex justify-between items-center">
            {{ session('success') }}
            <button @click="show=false" style="background:none;border:none;cursor:pointer;color:inherit;opacity:.6;font-size:1rem;">&times;</button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-error">
            <ul class="list-none m-0 p-0">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>

<!-- Main -->
<main class="px-6 py-6 max-w-6xl mx-auto">
    @yield('content')
</main>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@stack('scripts')
</body>
</html>
