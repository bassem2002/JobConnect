<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script>if(localStorage.getItem('theme')==='dark')document.documentElement.setAttribute('data-theme','dark');</script>

        <title>{{ config('app.name', 'JobConnect') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                /* Default (Candidate) Theme: Indigo */
                --primary:       #4F46E5;
                --primary-dark:  #3730A3;
                --primary-light: #EEF2FF;
                --accent:        #F59E0B;
                --surface:       #F8F9FC;
                --surface-2:     #FFFFFF;
                --border:        #E5E7EB;
                --text-main:     #111827;
                --text-muted:    #6B7280;
                --radius:        14px;
                --shadow-sm:     0 1px 3px rgba(0,0,0,.07), 0 1px 2px rgba(0,0,0,.04);
                --shadow-md:     0 4px 16px rgba(79,70,229,.10);
                --transition:    .2s cubic-bezier(.4,0,.2,1);
            }

            @auth
                @if(Auth::user()->isAdmin())
                :root {
                    /* Admin Theme: Slate/Indigo */
                    --primary:       #334155;
                    --primary-dark:  #1e293b;
                    --primary-light: #f1f5f9;
                    --accent:        #6366f1;
                    --shadow-md:     0 4px 16px rgba(51, 65, 85, .15);
                }
                @elseif(Auth::user()->isCompany())
                :root {
                    /* Company Theme: Emerald/Teal */
                    --primary:       #059669;
                    --primary-dark:  #065f46;
                    --primary-light: #ecfdf5;
                    --accent:        #fbbf24;
                    --shadow-md:     0 4px 16px rgba(5, 150, 105, .15);
                }
                @endif
            @endauth

            *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
                background: var(--surface);
                color: var(--text-main);
                min-height: 100vh;
            }

            /* ── Page header ──────────────────────────────────────── */
            .page-header {
                background: var(--surface-2);
                border-bottom: 1px solid var(--border);
                padding: 1.25rem 2rem;
                box-shadow: var(--shadow-sm);
            }

            .page-header-inner {
                max-width: 80rem;
                margin: 0 auto;
            }

            .page-header h1 {
                font-size: 1.25rem;
                font-weight: 700;
                color: var(--text-main);
            }

            /* ── Page Header Component (x-page-header) ─────────── */
            .ph-wrap { display: flex; flex-direction: column; gap: .2rem; }
            .ph-bc {
                display: flex; align-items: center; gap: .3rem; flex-wrap: wrap;
                margin-bottom: .3rem;
            }
            .ph-bc-link {
                font-size: .75rem; font-weight: 600; color: var(--text-muted);
                text-decoration: none; transition: color var(--transition);
                display: inline-flex; align-items: center; gap: .2rem;
            }
            .ph-bc-link:hover { color: var(--primary); }
            .ph-bc-sep { color: #D1D5DB; flex-shrink: 0; }
            .ph-bc-current {
                font-size: .75rem; font-weight: 600; color: var(--text-main);
                max-width: 36ch; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
            }
            .ph-row {
                display: flex; align-items: center; justify-content: space-between;
                flex-wrap: wrap; gap: .75rem;
            }
            .ph-left { display: flex; align-items: center; gap: .7rem; }
            .ph-icon-wrap {
                width: 38px; height: 38px;
                border-radius: 10px;
                background: #EEF2FF;
                color: #4F46E5;
                display: flex; align-items: center; justify-content: center;
                flex-shrink: 0;
            }
            .ph-title {
                font-size: 1.2rem; font-weight: 800; color: #111827;
                margin: 0; letter-spacing: -.025em; line-height: 1.25;
            }
            .ph-sub { font-size: .8rem; color: #6B7280; margin: .2rem 0 0; line-height: 1.45; }
            .ph-actions { display: flex; align-items: center; gap: .5rem; flex-wrap: wrap; }

            /* ── Main content ─────────────────────────────────────── */
            .main-content {
                max-width: 80rem;
                margin: 2rem auto;
                padding: 0 1.5rem;
            }

            /* Scrollbar */
            ::-webkit-scrollbar { width: 6px; }
            ::-webkit-scrollbar-track { background: var(--surface); }
            ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 99px; }
            ::-webkit-scrollbar-thumb:hover { background: var(--primary); }

            /* Selection */
            ::selection { background: var(--primary); color: #fff; }

            /* ── Toast / Flash messages ───────────────────────────── */
            .flash-container {
                position: fixed;
                bottom: 1.25rem;
                right: 1.25rem;
                z-index: 9999;
                display: flex;
                flex-direction: column;
                gap: .5rem;
            }
            .flash {
                padding: .75rem 1.25rem;
                border-radius: var(--radius);
                font-size: .875rem;
                font-weight: 600;
                box-shadow: var(--shadow-md);
                animation: slideIn .3s ease forwards;
            }
            .flash-success { background: #ECFDF5; color: #065F46; border-left: 4px solid #10B981; }
            .flash-error   { background: #FEF2F2; color: #991B1B; border-left: 4px solid #EF4444; }
            .flash-info    { background: var(--primary-light); color: var(--primary-dark); border-left: 4px solid var(--primary); }

            @keyframes slideIn {
                from { opacity: 0; transform: translateY(1rem); }
                to   { opacity: 1; transform: translateY(0); }
            }

            /* ── Main content wrapper ─────────────────────────── */
            .te-main-content {
                width: 100%;
                min-height: calc(100vh - 64px);
            }

            .te-content-container {
                width: 100%;
            }

            /* ── Dark mode: app-level overrides ──────────────── */
            [data-theme="dark"] body,
            [data-theme="dark"] .min-h-screen {
                background: #0f172a !important;
                color: #f1f5f9;
            }

            [data-theme="dark"] .page-header {
                background: #1e293b !important;
                border-color: #334155 !important;
            }

            [data-theme="dark"] .ph-title { color: #f1f5f9 !important; }
            [data-theme="dark"] .ph-sub   { color: #94a3b8 !important; }
            [data-theme="dark"] .ph-bc-current { color: #f1f5f9 !important; }

            [data-theme="dark"] .flash-success { background:#052e16; color:#86efac; }
            [data-theme="dark"] .flash-error   { background:#450a0a; color:#fca5a5; }

        </style>
    </head>
    <body>
        <div class="min-h-screen" style="background:var(--surface)">

            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <div class="page-header">
                    <div class="page-header-inner">
                        {{ $header }}
                    </div>
                </div>
            @endisset

            <!-- Flash Messages -->
            @if(session('success') || session('error') || session('info'))
                <div class="flash-container">
                    @if(session('success'))
                        <div class="flash flash-success">✓ {{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="flash flash-error">✕ {{ session('error') }}</div>
                    @endif
                    @if(session('info'))
                        <div class="flash flash-info">ℹ {{ session('info') }}</div>
                    @endif
                </div>
                <script>
                    setTimeout(() => {
                        document.querySelectorAll('.flash').forEach(el => {
                            el.style.transition = 'opacity .4s';
                            el.style.opacity = '0';
                            setTimeout(() => el.remove(), 400);
                        });
                    }, 4000);
                </script>
            @endif

        <!-- Page Content -->
        <main class="te-main-content">
            <div class="te-content-container">
                {{ $slot }}
            </div>
        </main>
    </div>

    @stack('scripts')
</body>
</html>
