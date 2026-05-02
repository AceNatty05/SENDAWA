{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Platform review anonim – bagikan pendapatmu tanpa perlu mendaftar.">
    <title>{{ $title ?? 'SENDAWA – Review Anonim' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        * { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="min-h-full bg-slate-950 text-slate-100">

    {{-- Navbar --}}
    <nav class="sticky top-0 z-40 border-b border-slate-800 bg-slate-950/90 backdrop-blur-md">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <a href="/" class="flex items-center gap-2 group">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-violet-500 to-fuchsia-600 text-white font-bold text-lg shadow-lg group-hover:shadow-violet-500/40 transition-all duration-300">S</span>
                    <span class="text-xl font-bold tracking-tight bg-gradient-to-r from-violet-400 to-fuchsia-400 bg-clip-text text-transparent">SENDAWA</span>
                </a>
                <div class="flex items-center gap-1">
                    <span class="rounded-full bg-violet-500/10 border border-violet-500/30 px-3 py-1 text-xs font-medium text-violet-400">
                        100% Anonim
                    </span>
                </div>
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <footer class="mt-16 border-t border-slate-800 py-8 text-center text-sm text-slate-500">
        <p>SENDAWA &copy; {{ date('Y') }} — Tugas Komputasi Awan · IaaS &amp; Virtualisasi</p>
    </footer>

    @livewireScripts
</body>
</html>
