{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Platform postingan anonim – bagikan pendapatmu tanpa perlu mendaftar.">
    <title>{{ $title ?? 'SENDAWA – Postingan Anonim' }}</title>
    <link rel="icon" type="image/png" href="/favicon.png">
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="apple-touch-icon" href="/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        /* ── CSS Variables (Light — Biru Laut) ── */
        :root {
            --bg:               #b8e4f2;
            --surface:          #f0fafd;
            --surface-2:        #e0f5fb;
            --border:           #7ecde6;
            --border-input:     #5bbdd8;
            --text-primary:     #012a4a;
            --text-body:        #1a4a65;
            --text-muted:       #3d8aaa;
            --accent:           #0077b6;
            --accent-dark:      #023e8a;
            --card-border:      #48cae4;
            --card-shadow:      rgba(0,119,182,0.12);
            --card-hover-shadow:rgba(0,119,182,0.28);
            --flash-bg:         #d1fae5;
            --flash-border:     #6ee7b7;
            --flash-text:       #065f46;
            --delete-hover-bg:  #fee2e2;
            --delete-hover-txt: #dc2626;
            --navbar-grad:      linear-gradient(135deg, #012a4a 0%, #013a63 30%, #0077b6 65%, #00b4d8 100%);
            --navbar-shadow:    0 2px 20px rgba(0,20,60,0.4);
            --modal-bg:         rgba(0,30,60,0.55);
            --theme-btn-bg:     rgba(255,255,255,0.15);
            --theme-btn-hover:  rgba(255,255,255,0.28);
        }

        /* ── CSS Variables (Dark — Laut Dalam) ── */
        [data-theme="dark"] {
            --bg:               #011627;
            --surface:          #012a4a;
            --surface-2:        #013a63;
            --border:           #0a4f6e;
            --border-input:     #1a6a8a;
            --text-primary:     #caf0f8;
            --text-body:        #90e0ef;
            --text-muted:       #4da8c4;
            --accent:           #00b4d8;
            --accent-dark:      #0096c7;
            --card-border:      #0a5f7a;
            --card-shadow:      rgba(0,0,0,0.35);
            --card-hover-shadow:rgba(0,0,0,0.55);
            --flash-bg:         #064e3b;
            --flash-border:     #065f46;
            --flash-text:       #a7f3d0;
            --delete-hover-bg:  #450a0a;
            --delete-hover-txt: #f87171;
            --navbar-grad:      linear-gradient(135deg, #020c1a 0%, #011627 35%, #012a4a 70%, #0077b6 100%);
            --navbar-shadow:    0 2px 24px rgba(0,0,0,0.6);
            --modal-bg:         rgba(0,10,25,0.75);
            --theme-btn-bg:     rgba(255,255,255,0.1);
            --theme-btn-hover:  rgba(255,255,255,0.2);
        }

        *, *::before, *::after {
            font-family: 'Inter', sans-serif;
            box-sizing: border-box;
        }

        html { transition: background 0.3s; }

        body {
            margin: 0;
            background:
                linear-gradient(160deg, rgba(0,119,182,0.45) 0%, rgba(0,180,216,0.35) 100%),
                url('/Bubble.jpg') center center / cover no-repeat fixed;
            color: var(--text-body);
            min-height: 100vh;
            transition: background 0.4s, color 0.3s;
        }

        [data-theme="dark"] body {
            background:
                linear-gradient(160deg, rgba(0,20,50,0.72) 0%, rgba(0,60,100,0.65) 100%),
                url('/Bubble.jpg') center center / cover no-repeat fixed;
        }

        /* ── Masonry Grid ── */
        .review-masonry { columns: 3; column-gap: 18px; }
        @media (max-width: 960px) { .review-masonry { columns: 2; } }
        @media (max-width: 580px) { .review-masonry { columns: 1; } }

        /* ── Card ── */
        .review-card {
            break-inside: avoid;
            margin-bottom: 18px;
            border-radius: 18px;
            border: 1.5px solid var(--card-border);
            background: var(--surface);
            overflow: hidden;
            box-shadow: 0 2px 10px var(--card-shadow);
            transition: box-shadow 0.25s, transform 0.25s, background-color 0.3s, border-color 0.3s;
        }

        .review-card:hover {
            box-shadow: 0 8px 28px var(--card-hover-shadow);
            transform: translateY(-3px);
        }

        .review-card-header {
            padding: 12px 16px 10px;
            border-bottom: 1.5px solid var(--border);
            font-weight: 600;
            font-size: 0.93rem;
            color: var(--text-primary);
            transition: border-color 0.3s, color 0.3s;
        }

        .review-card-photo {
            width: 100%;
            border-bottom: 1.5px solid var(--border);
            background: var(--surface-2);
            overflow: hidden;
            transition: border-color 0.3s, background 0.3s;
        }

        .review-card-photo img {
            width: 100%;
            display: block;
            object-fit: cover;
            max-height: 240px;
            transition: transform 0.4s;
        }

        .review-card:hover .review-card-photo img { transform: scale(1.04); }

        .review-card-body {
            padding: 12px 16px;
            color: var(--text-body);
            font-size: 0.875rem;
            line-height: 1.65;
            transition: color 0.3s;
        }

        .review-card-footer {
            padding: 6px 16px 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .review-time {
            font-size: 0.75rem;
            color: var(--text-muted);
            transition: color 0.3s;
        }

        .btn-delete {
            font-size: 0.72rem;
            color: var(--text-muted);
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 5px;
            transition: background 0.2s, color 0.2s;
        }

        .btn-delete:hover {
            background: var(--delete-hover-bg);
            color: var(--delete-hover-txt);
        }

        /* ── Flash ── */
        .flash-success {
            background: var(--flash-bg);
            border: 1.5px solid var(--flash-border);
            color: var(--flash-text);
            border-radius: 10px;
            padding: 10px 18px;
            margin-bottom: 18px;
            font-size: 0.88rem;
            transition: background 0.3s, color 0.3s;
        }

        /* ── Empty ── */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: var(--text-muted);
        }

        .empty-box {
            display: inline-block;
            background: rgba(255,255,255,0.22);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1.5px solid rgba(255,255,255,0.4);
            border-radius: 20px;
            padding: 36px 48px;
            box-shadow: 0 8px 32px rgba(0,80,160,0.2);
        }

        /* ── Modal ── */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: var(--modal-bg);
            backdrop-filter: blur(4px);
            z-index: 200;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            animation: fadeIn 0.2s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }

        .modal-box {
            background: var(--surface);
            border-radius: 18px;
            width: 100%;
            max-width: 540px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
            animation: slideUp 0.25s ease;
            transition: background 0.3s;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .modal-header {
            background: var(--navbar-grad);
            padding: 16px 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-header h2 {
            color: #fff;
            font-size: 1rem;
            font-weight: 700;
            margin: 0;
        }

        .modal-close {
            background: rgba(255,255,255,0.15);
            border: none;
            color: #fff;
            border-radius: 8px;
            width: 32px;
            height: 32px;
            font-size: 1.1rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
            line-height: 1;
        }

        .modal-close:hover { background: rgba(255,255,255,0.3); }

        .modal-body {
            padding: 22px;
            max-height: 80vh;
            overflow-y: auto;
            background: var(--surface);
            transition: background 0.3s;
        }

        .form-group { margin-bottom: 18px; }

        .form-group label {
            display: block;
            font-size: 0.83rem;
            font-weight: 600;
            color: var(--accent);
            margin-bottom: 6px;
            transition: color 0.3s;
        }

        .form-input, .form-textarea {
            width: 100%;
            border: 1.5px solid var(--border-input);
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.9rem;
            color: var(--text-primary);
            background: var(--surface-2);
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.3s, color 0.3s;
        }

        .form-input:focus, .form-textarea:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(37,99,235,0.12);
            background: var(--surface);
        }

        .form-textarea { resize: none; min-height: 130px; }

        .upload-area {
            display: block;
            border: 2px dashed var(--border-input);
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            background: var(--surface-2);
            transition: border-color 0.2s, background 0.2s;
        }

        .upload-area:hover {
            border-color: var(--accent);
            background: var(--surface);
        }

        .upload-area .upload-icon { font-size: 2rem; margin-bottom: 6px; }

        .upload-area p {
            margin: 0;
            font-size: 0.83rem;
            color: var(--text-muted);
        }

        .upload-area input[type=file] { display: none; }

        .form-error {
            color: #dc2626;
            font-size: 0.78rem;
            margin-top: 5px;
        }

        .btn-submit {
            width: 100%;
            background: var(--navbar-grad);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-size: 0.92rem;
            font-weight: 700;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.15s;
        }

        .btn-submit:hover { opacity: 0.88; transform: translateY(-1px); }
        .btn-submit:disabled { opacity: 0.55; cursor: not-allowed; transform: none; }

        /* ── Navbar ── */
        .sendawa-navbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            background: var(--navbar-grad);
            padding: 10px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
            z-index: 100;
            box-shadow: var(--navbar-shadow);
        }

        .sendawa-brand {
            color: #fff;
            font-weight: 800;
            font-size: 1.15rem;
            text-decoration: none;
            letter-spacing: 1.5px;
            white-space: nowrap;
            text-shadow: 0 1px 4px rgba(0,0,0,0.2);
        }

        .sendawa-search {
            flex: 1;
            max-width: 360px;
            position: relative;
        }

        .sendawa-search input {
            width: 100%;
            border-radius: 8px;
            border: none;
            padding: 9px 36px 9px 14px;
            font-size: 0.9rem;
            outline: none;
            background: rgba(255,255,255,0.15);
            color: #fff;
            backdrop-filter: blur(4px);
            transition: background 0.2s, box-shadow 0.2s;
        }

        .sendawa-search input::placeholder { color: rgba(255,255,255,0.65); }

        .sendawa-search input:focus {
            background: rgba(255,255,255,0.25);
            box-shadow: 0 0 0 2px rgba(255,255,255,0.4);
        }

        .sendawa-search .clear-btn {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: rgba(255,255,255,0.8);
            font-size: 0.9rem;
            line-height: 1;
            padding: 0;
        }

        /* Spacer pushes right-side buttons to the far right */
        .sendawa-spacer { flex: 1; }

        /* ── Theme Toggle ── */
        .theme-toggle {
            background: var(--theme-btn-bg);
            border: 1px solid rgba(255,255,255,0.2);
            color: #fff;
            border-radius: 8px;
            width: 36px;
            height: 36px;
            font-size: 1rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s, transform 0.15s;
            flex-shrink: 0;
        }

        .theme-toggle:hover {
            background: var(--theme-btn-hover);
            transform: rotate(20deg) scale(1.1);
        }

        /* ── New Post Button ── */
        .sendawa-new-post {
            background: rgba(255,255,255,0.18);
            color: #fff;
            border: 1.5px solid rgba(255,255,255,0.35);
            border-radius: 8px;
            padding: 8px 20px;
            font-weight: 700;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            cursor: pointer;
            white-space: nowrap;
            backdrop-filter: blur(4px);
            transition: background 0.2s, border-color 0.2s, transform 0.15s;
            flex-shrink: 0;
        }

        .sendawa-new-post:hover {
            background: rgba(255,255,255,0.3);
            border-color: rgba(255,255,255,0.6);
            transform: translateY(-1px);
        }

        /* ── Main ── */
        .sendawa-main {
            padding: 24px 20px;
            max-width: 1280px;
            margin: 0 auto;
        }

        /* ── Post Count Badge (in navbar) ── */
        .post-count-badge {
            display: inline-flex;
            align-items: center;
            background: rgba(255,255,255,0.18);
            border: 1px solid rgba(255,255,255,0.3);
            color: #fff;
            font-size: 0.72rem;
            font-weight: 700;
            border-radius: 20px;
            padding: 3px 10px;
            letter-spacing: 0.3px;
            white-space: nowrap;
            backdrop-filter: blur(4px);
        }

        /* ── Search Result Info ── */
        .search-result-info {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 20px;
            padding: 6px 16px;
            font-size: 0.82rem;
            color: #fff;
            margin-bottom: 16px;
            text-shadow: 0 1px 3px rgba(0,30,80,0.3);
        }

        .search-result-info strong { color: #fff; font-weight: 800; }
        .search-result-info em     { font-style: normal; opacity: 0.9; }

        .pagination-wrap {
            margin-top: 28px;
            display: flex;
            justify-content: center;
        }

        /* ── Footer ── */
        footer {
            text-align: center;
            padding: 20px 24px;
            font-size: 0.8rem;
            font-weight: 500;
            color: rgba(255,255,255,0.85);
            background: rgba(0,30,70,0.55);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-top: 1px solid rgba(255,255,255,0.15);
            margin-top: 60px;
            letter-spacing: 0.3px;
            transition: color 0.3s;
        }

        [data-theme="dark"] footer {
            background: rgba(0,10,30,0.7);
            color: rgba(180,230,255,0.75);
        }
    </style>
</head>
<body>
    {{-- Navbar rendered inside Livewire component so wire: directives work --}}

    {{-- Main Content (with top padding for fixed nav) --}}
    <div style="padding-top: 56px;">
        <main class="sendawa-main">
            {{ $slot }}
        </main>
    </div>

    {{-- Footer --}}
    <footer>
        SENDAWA &copy; {{ date('Y') }} — AceNatty
    </footer>

    @livewireScripts

    {{-- Dark mode toggle script --}}
    <script>
        (function () {
            // Persist preference
            const saved = localStorage.getItem('sendawa-theme');
            if (saved === 'dark') {
                document.documentElement.setAttribute('data-theme', 'dark');
            }

            window.toggleTheme = function () {
                const html = document.documentElement;
                const isDark = html.getAttribute('data-theme') === 'dark';
                if (isDark) {
                    html.removeAttribute('data-theme');
                    localStorage.setItem('sendawa-theme', 'light');
                } else {
                    html.setAttribute('data-theme', 'dark');
                    localStorage.setItem('sendawa-theme', 'dark');
                }
                // Update icon
                const btn = document.getElementById('theme-toggle-btn');
                if (btn) btn.textContent = isDark ? '🌙' : '☀️';
            };

            // Set initial icon after DOM ready
            document.addEventListener('DOMContentLoaded', function () {
                const btn = document.getElementById('theme-toggle-btn');
                if (btn) {
                    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
                    btn.textContent = isDark ? '☀️' : '🌙';
                }
            });
        })();
    </script>
</body>
</html>
