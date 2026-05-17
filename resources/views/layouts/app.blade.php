{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'QCM Platform')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Sora', sans-serif; }
        .mono { font-family: 'JetBrains Mono', monospace; }
        :root {
            --primary: #4F46E5;
            --primary-dark: #3730A3;
            --accent: #06B6D4;
            --success: #10B981;
            --danger: #EF4444;
            --warn: #F59E0B;
            --bg: #F8FAFC;
            --card: #FFFFFF;
            --border: #E2E8F0;
            --text: #0F172A;
            --muted: #64748B;
        }
        body { background: var(--bg); color: var(--text); }
        .nav-link { position: relative; }
        .nav-link::after {
            content: ''; position: absolute; bottom: -2px; left: 0;
            width: 0; height: 2px; background: var(--primary);
            transition: width 0.2s ease;
        }
        .nav-link:hover::after { width: 100%; }
        .btn-primary {
            background: var(--primary); color: white;
            padding: 0.5rem 1.25rem; border-radius: 0.5rem;
            font-weight: 600; font-size: 0.875rem;
            transition: all 0.2s; border: none; cursor: pointer;
            display: inline-flex; align-items: center; gap: 0.4rem;
        }
        .btn-primary:hover { background: var(--primary-dark); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(79,70,229,0.3); }
        .card { background: var(--card); border: 1px solid var(--border); border-radius: 1rem; }
        .badge {
            display: inline-flex; align-items: center;
            padding: 0.2rem 0.6rem; border-radius: 9999px;
            font-size: 0.7rem; font-weight: 600; letter-spacing: 0.05em; text-transform: uppercase;
        }
        .badge-easy { background: #D1FAE5; color: #065F46; }
        .badge-medium { background: #FEF3C7; color: #92400E; }
        .badge-hard { background: #FEE2E2; color: #991B1B; }
        .badge-completed { background: #D1FAE5; color: #065F46; }
        .badge-in_progress { background: #FEF3C7; color: #92400E; }
        .badge-abandoned { background: #FEE2E2; color: #991B1B; }
        .fade-in { animation: fadeIn 0.4s ease forwards; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>

<nav style="background:white; border-bottom: 1px solid var(--border);" class="sticky top-0 z-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">

        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 font-bold text-lg" style="color: var(--primary);">
            <span style="background:var(--primary); color:white; width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:1rem;">Q</span>
            QCM
        </a>

        <div class="hidden md:flex items-center gap-6 text-sm font-medium" style="color: var(--muted)">
            <a href="{{ route('quizzes.index') }}" class="nav-link hover:text-indigo-600 transition-colors">Quiz</a>
            @if(auth()->user()->isTeacher() || auth()->user()->isAdmin())
            <a href="{{ route('questions.index') }}" class="nav-link hover:text-indigo-600 transition-colors">Questions</a>
            @endif
            <a href="{{ route('attempts.index') }}" class="nav-link hover:text-indigo-600 transition-colors">Mes tentatives</a>
        </div>

        <div class="flex items-center gap-3">
            <span class="hidden sm:block text-sm font-medium" style="color:var(--muted)">{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="text-sm font-medium px-3 py-1.5 rounded-lg border transition-all hover:bg-red-50 hover:text-red-600 hover:border-red-200" style="border-color:var(--border); color:var(--muted)">
                    Déconnexion
                </button>
            </form>
        </div>
    </div>
</nav>

<main class="max-w-6xl mx-auto px-4 sm:px-6 py-8">
    @if (session('success'))
        <div class="mb-6 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2 fade-in"
             style="background:#D1FAE5; color:#065F46; border: 1px solid #A7F3D0;">
            ✓ {{ session('success') }}
        </div>
    @endif

    @yield('content')
</main>

</body>
</html>