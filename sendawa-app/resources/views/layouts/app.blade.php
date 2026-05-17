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
        /* ── Reset & Base ── */
        *, *::before, *::after {
            font-family: 'Inter', sans-serif;
            box-sizing: border-box;
        }

        html, body { margin: 0; padding: 0; min-height: 100vh; }

        body {
            background: #0d0d1a;
            color: #c4c4e8;
        }

        /* ── Navbar ── */
        .sendawa-navbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            background: rgba(13,13,26,0.95);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(139,92,246,0.25);
            padding: 0 24px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 100;
            box-shadow: 0 2px 24px rgba(0,0,0,0.5);
        }

        .navbar-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .brand-avatar {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #7c3aed, #a855f7);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1rem;
            color: #fff;
            box-shadow: 0 0 12px rgba(139,92,246,0.5);
            flex-shrink: 0;
        }

        .sendawa-brand {
            color: #fff;
            font-weight: 800;
            font-size: 1.1rem;
            text-decoration: none;
            letter-spacing: 2px;
            text-shadow: 0 0 20px rgba(168,85,247,0.4);
        }

        .anonim-badge {
            background: linear-gradient(135deg, #7c3aed, #a855f7);
            color: #fff;
            font-size: 0.72rem;
            font-weight: 700;
            border-radius: 20px;
            padding: 5px 14px;
            letter-spacing: 0.5px;
            box-shadow: 0 0 12px rgba(139,92,246,0.4);
            white-space: nowrap;
        }

        /* ── Hero Section ── */
        .hero {
            padding: 100px 20px 48px;
            text-align: center;
        }

        .hero-title {
            font-size: clamp(2.2rem, 5vw, 3.5rem);
            font-weight: 800;
            background: linear-gradient(135deg, #a855f7 0%, #ec4899 60%, #f472b6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 0 0 14px;
            line-height: 1.15;
            text-shadow: none;
        }

        .hero-subtitle {
            font-size: 1rem;
            color: #9090c0;
            margin: 0 0 36px;
            font-weight: 400;
        }

        /* ── Hero Action Row ── */
        .hero-actions {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
            flex-wrap: wrap;
            margin-bottom: 24px;
        }

        .btn-new-post {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #7c3aed, #a855f7);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 12px 28px;
            font-weight: 700;
            font-size: 0.92rem;
            cursor: pointer;
            letter-spacing: 0.3px;
            transition: transform 0.18s, box-shadow 0.18s, opacity 0.18s;
            box-shadow: 0 4px 20px rgba(139,92,246,0.45);
            white-space: nowrap;
        }

        .btn-new-post:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(139,92,246,0.6);
        }

        .hero-search-wrap {
            position: relative;
            width: 100%;
            max-width: 440px;
        }

        .hero-search-wrap .search-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #6060a0;
            font-size: 0.9rem;
            pointer-events: none;
        }

        .hero-search-wrap input {
            width: 100%;
            background: rgba(255,255,255,0.06);
            border: 1.5px solid rgba(139,92,246,0.3);
            border-radius: 12px;
            padding: 12px 40px 12px 40px;
            font-size: 0.9rem;
            color: #d0d0f0;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }

        .hero-search-wrap input::placeholder { color: #5a5a90; }

        .hero-search-wrap input:focus {
            border-color: #a855f7;
            background: rgba(168,85,247,0.08);
            box-shadow: 0 0 0 3px rgba(168,85,247,0.15);
        }

        .hero-search-wrap .clear-btn {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #6060a0;
            font-size: 0.85rem;
            padding: 0;
            line-height: 1;
            transition: color 0.2s;
        }

        .hero-search-wrap .clear-btn:hover { color: #d0d0f0; }

        /* ── Post Count ── */
        .post-count-text {
            font-size: 0.82rem;
            color: #6060a0;
            font-weight: 500;
        }

        .post-count-text strong { color: #a855f7; font-weight: 700; }

        /* ── Search Result Info ── */
        .search-result-info {
            display: inline-block;
            background: rgba(139,92,246,0.12);
            border: 1px solid rgba(139,92,246,0.3);
            border-radius: 20px;
            padding: 6px 16px;
            font-size: 0.82rem;
            color: #c4a8ff;
            margin-bottom: 16px;
        }

        .search-result-info strong { color: #d8b4fe; font-weight: 800; }
        .search-result-info em     { font-style: normal; opacity: 0.9; }

        /* ── Masonry Grid ── */
        .postingan-masonry { columns: 3; column-gap: 18px; }
        @media (max-width: 960px) { .postingan-masonry { columns: 2; } }
        @media (max-width: 580px) { .postingan-masonry { columns: 1; } }

        /* ── Card ── */
        .postingan-card {
            break-inside: avoid;
            margin-bottom: 18px;
            border-radius: 16px;
            border: 1.5px solid rgba(139,92,246,0.2);
            background: rgba(255,255,255,0.04);
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0,0,0,0.3);
            transition: box-shadow 0.25s, transform 0.25s, border-color 0.25s;
        }

        .postingan-card:hover {
            box-shadow: 0 8px 32px rgba(139,92,246,0.25);
            border-color: rgba(168,85,247,0.5);
            transform: translateY(-3px);
        }

        .postingan-card-header {
            padding: 14px 16px 12px;
            border-bottom: 1px solid rgba(139,92,246,0.15);
            font-weight: 700;
            font-size: 0.95rem;
            color: #e0d0ff;
            line-height: 1.4;
        }

        .postingan-card-photo {
            width: 100%;
            border-bottom: 1px solid rgba(139,92,246,0.15);
            background: rgba(139,92,246,0.05);
            overflow: hidden;
        }

        .postingan-card-photo img {
            width: 100%;
            display: block;
            object-fit: cover;
            max-height: 240px;
            transition: transform 0.4s;
        }

        .postingan-card:hover .postingan-card-photo img { transform: scale(1.04); }

        .postingan-card-body {
            padding: 12px 16px;
            color: #a0a0c8;
            font-size: 0.875rem;
            line-height: 1.65;
        }

        .postingan-card-footer {
            padding: 6px 16px 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .postingan-time {
            font-size: 0.74rem;
            color: #5a5a90;
        }

        .btn-delete {
            font-size: 0.72rem;
            color: #5a5a90;
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 5px;
            transition: background 0.2s, color 0.2s;
        }

        .btn-delete:hover {
            background: rgba(239,68,68,0.15);
            color: #f87171;
        }

        /* ── Flash ── */
        .flash-success {
            background: rgba(34,197,94,0.1);
            border: 1.5px solid rgba(34,197,94,0.3);
            color: #86efac;
            border-radius: 12px;
            padding: 10px 18px;
            margin-bottom: 18px;
            font-size: 0.88rem;
            text-align: center;
        }

        /* ── Empty State ── */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-box {
            display: inline-block;
            background: rgba(255,255,255,0.04);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1.5px solid rgba(139,92,246,0.2);
            border-radius: 24px;
            padding: 48px 60px;
            box-shadow: 0 8px 40px rgba(0,0,0,0.3);
        }

        /* ── Modal ── */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,10,0.75);
            backdrop-filter: blur(6px);
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
            background: #12112a;
            border: 1.5px solid rgba(139,92,246,0.3);
            border-radius: 20px;
            width: 100%;
            max-width: 540px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.6);
            overflow: hidden;
            animation: slideUp 0.25s ease;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .modal-header {
            background: linear-gradient(135deg, #1a0a2e 0%, #2d1b4e 100%);
            border-bottom: 1px solid rgba(139,92,246,0.3);
            padding: 18px 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-header h2 {
            color: #e0d0ff;
            font-size: 1rem;
            font-weight: 700;
            margin: 0;
        }

        .modal-close {
            background: rgba(139,92,246,0.2);
            border: 1px solid rgba(139,92,246,0.3);
            color: #c4a8ff;
            border-radius: 8px;
            width: 32px;
            height: 32px;
            font-size: 1rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
            line-height: 1;
        }

        .modal-close:hover { background: rgba(139,92,246,0.4); color: #fff; }

        .modal-body {
            padding: 24px;
            max-height: 80vh;
            overflow-y: auto;
            background: #12112a;
        }

        .form-group { margin-bottom: 18px; }

        .form-group label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: #a855f7;
            margin-bottom: 6px;
        }

        .form-input, .form-textarea {
            width: 100%;
            border: 1.5px solid rgba(139,92,246,0.3);
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.9rem;
            color: #e0d0ff;
            background: rgba(255,255,255,0.05);
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }

        .form-input::placeholder, .form-textarea::placeholder { color: #4a4a70; }

        .form-input:focus, .form-textarea:focus {
            border-color: #a855f7;
            background: rgba(168,85,247,0.07);
            box-shadow: 0 0 0 3px rgba(168,85,247,0.15);
        }

        .form-textarea { resize: none; min-height: 130px; }

        .upload-area {
            display: block;
            border: 2px dashed rgba(139,92,246,0.35);
            border-radius: 10px;
            padding: 24px;
            text-align: center;
            cursor: pointer;
            background: rgba(255,255,255,0.03);
            transition: border-color 0.2s, background 0.2s;
        }

        .upload-area:hover {
            border-color: #a855f7;
            background: rgba(168,85,247,0.07);
        }

        .upload-area .upload-icon { font-size: 2rem; margin-bottom: 6px; }

        .upload-area p {
            margin: 0;
            font-size: 0.82rem;
            color: #5a5a90;
        }

        .upload-area input[type=file] { display: none; }

        .form-error {
            color: #f87171;
            font-size: 0.78rem;
            margin-top: 5px;
        }

        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, #7c3aed, #a855f7);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-size: 0.92rem;
            font-weight: 700;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.15s, box-shadow 0.2s;
            box-shadow: 0 4px 16px rgba(139,92,246,0.4);
        }

        .btn-submit:hover { opacity: 0.9; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(139,92,246,0.55); }
        .btn-submit:disabled { opacity: 0.45; cursor: not-allowed; transform: none; box-shadow: none; }

        /* ── Main ── */
        .sendawa-main {
            padding: 0 20px 40px;
            max-width: 1280px;
            margin: 0 auto;
        }

        /* ── Pagination ── */
        .pagination-wrap {
            margin-top: 32px;
            display: flex;
            justify-content: center;
        }

        /* ── Footer ── */
        footer {
            text-align: center;
            padding: 20px 24px;
            font-size: 0.8rem;
            font-weight: 500;
            color: rgba(168,85,247,0.5);
            border-top: 1px solid rgba(139,92,246,0.15);
            margin-top: 40px;
            letter-spacing: 0.3px;
        }
    </style>
</head>
<body>
    {{-- Navbar rendered inside Livewire component so wire: directives work --}}

    {{-- Main Content --}}
    <main class="sendawa-main">
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <footer>
        SENDAWA &copy; {{ date('Y') }} — AceNatty
    </footer>

    @livewireScripts
</body>
</html>
