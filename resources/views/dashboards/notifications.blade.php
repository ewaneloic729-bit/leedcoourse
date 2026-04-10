<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications | LEEDCOURSE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/platform-pro.css') }}">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, rgba(2, 6, 23, 0.9), rgba(22, 101, 52, 0.72)), url('{{ asset("images/image.png") }}') center/cover no-repeat fixed;
        }
        .glass { background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.14); backdrop-filter: blur(8px); }
        .panel { background: rgba(255,255,255,0.95); border: 1px solid rgba(15,23,42,0.08); }
        .celebration-card {
            background: linear-gradient(135deg, #ecfccb, #dcfce7);
            border-color: #86efac;
        }
        .confetti-layer {
            position: fixed;
            inset: 0;
            pointer-events: none;
            overflow: hidden;
            z-index: 60;
        }
        .confetti-piece {
            position: absolute;
            width: 10px;
            height: 16px;
            opacity: 0.9;
            animation: fall 2600ms linear forwards;
        }
        @keyframes fall {
            from { transform: translateY(-10vh) rotate(0deg); }
            to { transform: translateY(110vh) rotate(540deg); opacity: 0; }
        }
    </style>
</head>
<body class="min-h-screen text-slate-100">

    <div id="confettiLayer" class="confetti-layer hidden" aria-hidden="true"></div>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6">
        <header class="glass rounded-2xl p-5 sm:p-6 shadow-2xl">
            <div class="flex flex-wrap items-center justify-between gap-2">
                <div>
                    <p class="text-xs uppercase tracking-[0.24em] text-emerald-200 font-semibold">LEEDCOURSE</p>
                    <h1 class="mt-1 text-2xl sm:text-3xl font-extrabold">Notifications</h1>
                    <p class="mt-2 text-sm text-slate-200">Suivez les évènements importants de votre activité.</p>
                </div>
                <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-lg bg-white/10 border border-white/30 hover:bg-white/20 text-white text-sm font-semibold">Retour</a>
            </div>
        </header>

        <section class="panel rounded-2xl p-5 shadow-lg text-slate-800 space-y-2">
            @forelse($rows as $n)
                @php
                    $isAcceptance = !$n->is_read && str_contains(mb_strtolower($n->title), 'demande acceptee');
                @endphp
                <div class="border rounded-lg p-3 {{ $isAcceptance ? 'celebration-card' : ($n->is_read ? 'bg-slate-50' : 'bg-emerald-50 border-emerald-200') }}" @if($isAcceptance) data-celebration="1" @endif>
                    <div class="font-semibold text-sm">{{ $n->title }}</div>
                    <div class="text-sm text-slate-600">{{ $n->message }}</div>
                    <div class="text-xs text-slate-500 mt-1">{{ $n->created_at->format('d/m/Y H:i') }}</div>
                    @if(!$n->is_read)
                        <form method="POST" action="{{ route('notifications.read', $n) }}" class="mt-2">
                            @csrf
                            @method('PATCH')
                            <button class="text-xs px-2 py-1 rounded bg-slate-800 text-white">Marquer lu</button>
                        </form>
                    @endif
                </div>
            @empty
                <p class="text-sm text-slate-500">Aucune notification.</p>
            @endforelse
        </section>

        @if(method_exists($rows,'links'))
            <div class="panel rounded-xl p-3">{{ $rows->links() }}</div>
        @endif
    </div>

    <script>
        (function () {
            const hasCelebration = document.querySelector('[data-celebration="1"]');
            if (!hasCelebration) return;

            const layer = document.getElementById('confettiLayer');
            if (!layer) return;
            layer.classList.remove('hidden');

            const colors = ['#16a34a', '#22c55e', '#f59e0b', '#eab308', '#10b981', '#84cc16'];
            const total = 90;
            for (let i = 0; i < total; i++) {
                const piece = document.createElement('span');
                piece.className = 'confetti-piece';
                piece.style.left = Math.random() * 100 + 'vw';
                piece.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                piece.style.animationDelay = (Math.random() * 0.8) + 's';
                piece.style.transform = 'translateY(-10vh) rotate(' + Math.floor(Math.random() * 360) + 'deg)';
                layer.appendChild(piece);
            }

            setTimeout(() => {
                layer.classList.add('hidden');
                layer.innerHTML = '';
            }, 4000);
        })();
    </script>
    <script src="{{ asset('js/button-sounds.js') }}" defer></script>
    @include('partials.language-switcher')
</body>
</html>
