<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'JobConnect') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                --primary:       #4F46E5;
                --primary-dark:  #3730A3;
                --primary-light: #EEF2FF;
                --accent:        #F59E0B;
                --surface:       #F3F4F6;
                --surface-2:     #FFFFFF;
                --border:        #E5E7EB;
                --text-main:     #111827;
                --text-muted:    #6B7280;
                --radius:        16px;
                --transition:    .15s cubic-bezier(.4, 0, .2, 1);
            }

            *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

            /* ── Animated gradient body ───────────────────────────── */
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
                color: var(--text-main);
                min-height: 100vh;
                background: linear-gradient(270deg, #1e1b4b, #3730a3, #4f46e5, #6366f1, #7c3aed, #4338ca);
                background-size: 400% 400%;
                animation: authGradient 14s ease infinite;
            }

            @keyframes authGradient {
                0%   { background-position: 0% 50%; }
                50%  { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }

            /* ── Floating blur blobs ──────────────────────────────── */
            .auth-blob {
                position: fixed;
                border-radius: 50%;
                filter: blur(90px);
                pointer-events: none;
                z-index: 0;
                will-change: transform;
            }
            .auth-blob-1 {
                width: 520px; height: 520px;
                background: rgba(129, 140, 248, 0.38);
                top: -160px; left: -160px;
                animation: blobA 12s ease-in-out infinite;
            }
            .auth-blob-2 {
                width: 420px; height: 420px;
                background: rgba(196, 181, 253, 0.32);
                bottom: -110px; right: -110px;
                animation: blobB 16s ease-in-out infinite;
            }
            .auth-blob-3 {
                width: 300px; height: 300px;
                background: rgba(251, 191, 36, 0.18);
                top: 44%; left: 3%;
                animation: blobC 20s ease-in-out infinite;
            }

            @keyframes blobA {
                0%, 100% { transform: translate(0, 0); }
                40%       { transform: translate(45px, 30px); }
                70%       { transform: translate(-20px, 18px); }
            }
            @keyframes blobB {
                0%, 100% { transform: translate(0, 0); }
                35%       { transform: translate(-35px, -28px); }
                65%       { transform: translate(18px, 22px); }
            }
            @keyframes blobC {
                0%, 100% { transform: translateY(0); }
                50%       { transform: translateY(-45px); }
            }

            /* ── Page wrapper ─────────────────────────────────────── */
            .auth-page {
                position: relative;
                z-index: 1;
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                padding: 3rem 1.25rem;
            }

            /* ── Brand above card ─────────────────────────────────── */
            .auth-brand {
                display: flex;
                align-items: center;
                gap: .65rem;
                margin-bottom: 1.75rem;
                text-decoration: none;
            }
            .auth-brand-name {
                font-size: 1.5rem;
                font-weight: 800;
                color: #fff;
                letter-spacing: -.03em;
                text-shadow: 0 2px 10px rgba(0,0,0,.25);
            }
            .auth-brand-name span { color: #fde68a; }

            /* ── Glassmorphism card ───────────────────────────────── */
            .auth-card {
                width: 100%;
                background: rgba(255, 255, 255, 0.93);
                backdrop-filter: blur(28px);
                -webkit-backdrop-filter: blur(28px);
                border-radius: 20px;
                padding: 2.75rem 2.5rem;
                box-shadow:
                    0 32px 72px rgba(0, 0, 0, 0.24),
                    0 0 0 1px rgba(255, 255, 255, 0.45);
                border: 1px solid rgba(255, 255, 255, 0.55);
            }

            @media (max-width: 640px) {
                .auth-card  { padding: 2rem 1.5rem; }
                .auth-page  { padding: 2rem 1rem; }
            }

        </style>
    </head>
    <body>

        <!-- Animated background blobs -->
        <div class="auth-blob auth-blob-1"></div>
        <div class="auth-blob auth-blob-2"></div>
        <div class="auth-blob auth-blob-3"></div>

        <div class="auth-page">

            <!-- Brand -->
            <a href="{{ url('/') }}" class="auth-brand">
                <span class="auth-brand-name">Job<span>Connect</span></span>
            </a>

            <!-- Slot — each page renders its own .auth-card wrapper -->
            {{ $slot }}

        </div>

    </body>
</html>
