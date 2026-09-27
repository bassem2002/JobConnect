<style>
/* ── Navigation Variables ───────────────────────────────────── */
:root {
    --primary:       #4F46E5;
    --primary-dark:  #3730A3;
    --primary-light: #EEF2FF;
    --accent:        #F59E0B;
    --nav-bg:        #FFFFFF;
    --nav-border:    #E5E7EB;
    --text-main:     #111827;
    --text-muted:    #6B7280;
    --nav-link-hover:#4F46E5;
    --radius-sm:     8px;
    --radius:        12px;
    --shadow-nav:    0 1px 0 0 #E5E7EB, 0 4px 24px rgba(0,0,0,.05);
    --transition:    .18s cubic-bezier(.4,0,.2,1);
    /* Page-level */
    --dm-bg:         #ffffff;
    --dm-surface:    #f8fafc;
    --dm-card:       #ffffff;
    --dm-border:     #e2e8f0;
    --dm-txt:        #0f172a;
    --dm-muted:      #64748b;
    --dm-input-bg:   #ffffff;
}

[data-theme="dark"] {
    --primary-light: #1e1b4b;
    --nav-bg:        #0f172a;
    --nav-border:    #1e293b;
    --text-main:     #f1f5f9;
    --text-muted:    #94a3b8;
    --shadow-nav:    0 1px 0 0 #1e293b, 0 4px 24px rgba(0,0,0,.3);
    /* Page-level */
    --dm-bg:         #0f172a;
    --dm-surface:    #1e293b;
    --dm-card:       #1e293b;
    --dm-border:     #334155;
    --dm-txt:        #f1f5f9;
    --dm-muted:      #94a3b8;
    --dm-input-bg:   #0f172a;
}

/* ── Global dark mode overrides ─────────────────────────────── */
[data-theme="dark"] body {
    background: var(--dm-bg);
    color: var(--dm-txt);
}

[data-theme="dark"] .te-mobile-menu {
    background: #0f172a;
}

[data-theme="dark"] .te-user-btn {
    background: #1e293b;
    color: var(--dm-txt);
    border-color: #334155;
}

[data-theme="dark"] .te-hamburger {
    background: #1e293b;
    border-color: #334155;
    color: var(--dm-muted);
}

/* Cards, surfaces, borders */
[data-theme="dark"] .bg-white,
[data-theme="dark"] [class*="-card"],
[data-theme="dark"] [class*="adm-card"],
[data-theme="dark"] [class*="cf-card"],
[data-theme="dark"] [class*="cat-card"],
[data-theme="dark"] [class*="pf-card"],
[data-theme="dark"] [class*="-panel"] {
    background: var(--dm-card) !important;
    border-color: var(--dm-border) !important;
    color: var(--dm-txt) !important;
}

/* Inputs, selects, textareas */
[data-theme="dark"] input:not([type=radio]):not([type=checkbox]),
[data-theme="dark"] select,
[data-theme="dark"] textarea {
    background-color: #1e293b !important;
    border-color: #334155 !important;
    color: #f1f5f9 !important;
}

[data-theme="dark"] input::placeholder,
[data-theme="dark"] textarea::placeholder {
    color: #64748b !important;
}

/* ── Dark mode toggle button ─────────────────────────────────── */
.te-dm-toggle {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    border-radius: var(--radius-sm);
    border: 1.5px solid var(--nav-border);
    background: transparent;
    color: var(--text-muted);
    cursor: pointer;
    transition: background var(--transition), color var(--transition), border-color var(--transition);
    flex-shrink: 0;
}

.te-dm-toggle:hover {
    background: var(--primary-light);
    color: var(--primary);
    border-color: var(--primary);
}

.te-dm-toggle .icon-sun  { display: none; }
.te-dm-toggle .icon-moon { display: block; }

[data-theme="dark"] .te-dm-toggle .icon-sun  { display: block; }
[data-theme="dark"] .te-dm-toggle .icon-moon { display: none; }

/* ── Nav container ─────────────────────────────────────────── */
.te-nav {
    background: var(--nav-bg);
    box-shadow: var(--shadow-nav);
    position: sticky;
    top: 0;
    z-index: 100;
}

.te-nav-inner {
    max-width: 90rem;
    margin: 0 auto;
    padding: 0 1.5rem;
    display: flex;
    align-items: center;
    height: 64px;
    gap: 1.5rem;
}

/* ── Logo ──────────────────────────────────────────────────── */
.te-logo {
    display: flex;
    align-items: center;
    gap: .6rem;
    text-decoration: none;
    flex-shrink: 0;
}

@media (min-width: 768px) {
    .te-logo { flex: 1; }
}

.te-logo-mark {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, var(--primary), #818CF8);
    border-radius: 9px;
    box-shadow: 0 2px 8px rgba(79,70,229,.3);
}

.te-logo-text {
    font-size: 1.125rem;
    font-weight: 800;
    color: var(--primary-dark);
    letter-spacing: -.03em;
    line-height: 1;
}

.te-logo-text span { color: var(--primary); }

/* ── Nav links (desktop) ───────────────────────────────────── */
.te-nav-links {
    display: none;
    align-items: center;
    gap: .25rem;
    list-style: none;
}

@media (min-width: 768px) { .te-nav-links { display: flex; } }

.te-nav-link {
    position: relative;           /* needed for badge positioning */
    display: flex;
    align-items: center;
    gap: .4rem;
    padding: .45rem .85rem;
    border-radius: var(--radius-sm);
    font-size: .875rem;
    font-weight: 600;
    color: var(--text-muted);
    text-decoration: none;
    transition: color var(--transition), background var(--transition);
    white-space: nowrap;
}

.te-nav-link:hover {
    color: var(--nav-link-hover);
    background: var(--primary-light);
}

.te-nav-link.active {
    color: var(--primary);
    background: var(--primary-light);
}

/* Role-specific nav group separator */
.te-role-section {
    display: flex;
    align-items: center;
    gap: .25rem;
    padding-left: .75rem;
    margin-left: .5rem;
    border-left: 1px solid var(--nav-border);
}

/* ── Role indicator — visual only, no interaction ─────────── */
.te-role-indicator {
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    padding: .18rem .55rem;
    border-radius: 99px;
    background: var(--primary-light);
    border: 1px solid #C7D2FE;
    font-size: .65rem;
    font-weight: 800;
    letter-spacing: .06em;
    text-transform: uppercase;
    color: var(--primary-dark);
    pointer-events: none;
    user-select: none;
    cursor: default;
    flex-shrink: 0;
}

.te-role-indicator::before {
    content: '';
    width: 5px;
    height: 5px;
    border-radius: 50%;
    background: var(--primary);
    flex-shrink: 0;
}

/* Mobile version of the indicator */
.te-role-indicator-mobile {
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    padding: .22rem .65rem;
    border-radius: 99px;
    background: var(--primary-light);
    border: 1px solid #C7D2FE;
    font-size: .7rem;
    font-weight: 800;
    letter-spacing: .06em;
    text-transform: uppercase;
    color: var(--primary-dark);
    pointer-events: none;
    user-select: none;
    cursor: default;
}

.te-role-indicator-mobile::before {
    content: '';
    width: 5px;
    height: 5px;
    border-radius: 50%;
    background: var(--primary);
    flex-shrink: 0;
}

/* ── Right side actions ────────────────────────────────────── */
.te-actions {
    display: none;
    align-items: center;
    gap: .5rem;
    flex-shrink: 0;
}

@media (min-width: 768px) {
    .te-actions {
        display: flex;
        flex: 1;
        justify-content: flex-end;
    }
}

/* Notification bell */
.te-notif {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    border-radius: var(--radius-sm);
    color: var(--text-muted);
    text-decoration: none;
    transition: background var(--transition), color var(--transition);
}

.te-notif:hover {
    background: var(--primary-light);
    color: var(--primary);
}

.te-notif svg { width: 20px; height: 20px; }

.te-notif-badge {
    position: absolute;
    top: 4px; right: 4px;
    min-width: 16px; height: 16px;
    background: #EF4444; color: white;
    font-size: .6rem; font-weight: 800;
    border-radius: 99px;
    display: flex; align-items: center; justify-content: center;
    padding: 0 3px;
    border: 1.5px solid white;
}

/* User dropdown trigger */
.te-user-btn {
    display: flex;
    align-items: center;
    gap: .5rem;
    padding: .4rem .85rem .4rem .5rem;
    border: 1.5px solid var(--nav-border);
    border-radius: 99px;
    background: white;
    cursor: pointer;
    font-family: inherit;
    font-size: .875rem;
    font-weight: 600;
    color: var(--text-main);
    transition: border-color var(--transition), box-shadow var(--transition);
}

.te-user-btn:hover {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(79,70,229,.1);
}

.te-avatar {
    width: 28px; height: 28px; border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), #818CF8);
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: .75rem; font-weight: 800;
    flex-shrink: 0;
}

.te-chevron {
    width: 14px; height: 14px;
    color: var(--text-muted);
    transition: transform var(--transition);
}

/* ── Hamburger (mobile) ────────────────────────────────────── */
.te-hamburger {
    margin-left: auto;
    display: flex; align-items: center; justify-content: center;
    width: 40px; height: 40px;
    border-radius: var(--radius-sm);
    border: 1.5px solid var(--nav-border);
    background: white;
    cursor: pointer;
    color: var(--text-muted);
    transition: background var(--transition);
}

.te-hamburger:hover { background: var(--primary-light); color: var(--primary); }

@media (min-width: 768px) { .te-hamburger { display: none; } }

/* ── Mobile menu ───────────────────────────────────────────── */
.te-mobile-menu {
    background: white;
    border-top: 1px solid var(--nav-border);
    padding: 1rem 1.25rem 1.25rem;
    display: none;
}

.te-mobile-menu.open { display: block; }

.te-mobile-search {
    display: flex; align-items: center;
    background: #F9FAFB;
    border: 1.5px solid var(--nav-border);
    border-radius: var(--radius-sm);
    padding: .6rem 1rem; gap: .5rem;
    margin-bottom: 1rem;
    transition: border-color var(--transition);
}

.te-mobile-search:focus-within { border-color: var(--primary); }
.te-mobile-search svg { width: 16px; height: 16px; color: var(--text-muted); flex-shrink: 0; }
.te-mobile-search input {
    border: none; outline: none; background: transparent;
    font-size: .9rem; font-family: inherit; color: var(--text-main); width: 100%;
}

.te-mobile-links { display: flex; flex-direction: column; gap: .25rem; }

.te-mobile-link {
    display: flex; align-items: center; justify-content: space-between;
    padding: .7rem .9rem;
    border-radius: var(--radius-sm);
    font-size: .9rem; font-weight: 600;
    color: var(--text-muted);
    text-decoration: none;
    transition: background var(--transition), color var(--transition);
}

.te-mobile-link:hover, .te-mobile-link.active {
    background: var(--primary-light);
    color: var(--primary);
}

.te-section-label {
    font-size: .7rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .08em;
    color: var(--text-muted);
    padding: .75rem .9rem .25rem;
}

/* Mobile user card */
.te-mobile-user {
    display: flex; align-items: center; gap: .75rem;
    padding: .9rem;
    background: var(--primary-light);
    border-radius: var(--radius-sm);
    margin-bottom: .75rem;
}

.te-mobile-avatar {
    width: 40px; height: 40px; border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), #818CF8);
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: .9rem; font-weight: 800;
    flex-shrink: 0;
}

.te-mobile-user-info { flex: 1; min-width: 0; }
.te-mobile-user-name { font-weight: 700; font-size: .9rem; color: var(--primary-dark); }
.te-mobile-user-email { font-size: .75rem; color: var(--text-muted); margin-top: .1rem; }

.te-divider { height: 1px; background: var(--nav-border); margin: .75rem 0; }

/* ── Nav badge animation ────────────────────────────────────── */
@keyframes nb-pop {
    from { transform: translate(50%, -50%) scale(0); }
    to   { transform: translate(50%, -50%) scale(1); }
}

/* ── Mobile badge pill ──────────────────────────────────────── */
.te-mobile-badge {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 20px; height: 20px;
    background: #EF4444; color: white;
    font-size: .65rem; font-weight: 800;
    border-radius: 99px;
    padding: 0 5px;
    flex-shrink: 0;
    box-shadow: 0 1px 4px rgba(239,68,68,.35);
}

</style>

<nav class="te-nav" x-data="{ open: false }">
    <div class="te-nav-inner">

        <!-- Logo -->
        <a href="{{ route('dashboard') }}" class="te-logo">
            <div class="te-logo-mark">
                <svg width="20" height="20" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <span class="te-logo-text">Job<span>Connect</span></span>
        </a>

        <!-- Primary nav links (desktop) -->
        <ul class="te-nav-links">
            <li>
                <a href="{{ route('job-offers.index') }}"
                   class="te-nav-link {{ request()->routeIs('job-offers.index') ? 'active' : '' }}">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Offres
                </a>
            </li>

            @auth
            {{-- ════════════ ADMIN ════════════ --}}
            @if(Auth::user()->isAdmin())
                <li class="te-role-section">
                    <a href="{{ route('admin.dashboard') }}"
                       class="te-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                       <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                       Stats
                    </a>

                    <a href="{{ route('admin.categories.index') }}"
                       class="te-nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                       <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 11h.01M7 15h.01M11 7h.01M11 11h.01M11 15h.01M15 7h.01M15 11h.01M15 15h.01M4 4h16v16H4V4z"/></svg>
                       Catégories
                    </a>

                    {{-- Offres modération + badge --}}
                    <a href="{{ route('admin.offers.moderation') }}"
                       class="te-nav-link {{ request()->routeIs('admin.offers.*') ? 'active' : '' }}">
                       <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                       Offres
                       <x-nav-badge :count="$navBadgeOffers" />
                    </a>

                    {{-- Utilisateurs + badge --}}
                    <a href="{{ route('admin.users.moderation') }}"
                       class="te-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                       <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                       Utilisateurs
                       <x-nav-badge :count="$navBadgeUsers" />
                    </a>

                    {{-- Signalements + badge --}}
                    <a href="{{ route('admin.reports.index') }}"
                       class="te-nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                       <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6H11l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/></svg>
                       Signalements
                       <x-nav-badge :count="$navBadgeReports" />
                    </a>
                </li>
            @endif

            {{-- ════════════ CANDIDAT ════════════ --}}
            @if(Auth::user()->isCandidate())
                <li class="te-role-section">
                    {{-- Mes candidatures + badge --}}
                    <a href="{{ route('applications.index') }}"
                       class="te-nav-link {{ request()->routeIs('applications.*') ? 'active' : '' }}">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        Mes candidatures
                        <x-nav-badge :count="$navBadgeMyApps" />
                    </a>
                    <a href="{{ route('saved-jobs.index') }}"
                       class="te-nav-link {{ request()->routeIs('saved-jobs.*') ? 'active' : '' }}">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        Mes favoris
                    </a>
                </li>
            @endif

            {{-- ════════════ ENTREPRISE ════════════ --}}
            @if(Auth::user()->isCompany())
                <li class="te-role-section">
                    {{-- Mes offres + badge --}}
                    <a href="{{ route('job-offers.my-offers') }}"
                       class="te-nav-link {{ request()->routeIs('job-offers.my-offers') ? 'active' : '' }}">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        Mes offres
                        <x-nav-badge :count="$navBadgeMyOffers" />
                    </a>
                    <a href="{{ route('candidates.index') }}"
                       class="te-nav-link {{ request()->routeIs('candidates.*') ? 'active' : '' }}">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        Candidats
                    </a>
                </li>
            @endif
            @endauth
        </ul>

        <!-- Right actions (desktop) -->
        @auth
        <div class="te-actions">
            <!-- Dark mode toggle -->
            <button class="te-dm-toggle" id="dmToggle" onclick="toggleDarkMode()" title="Basculer mode sombre/clair" aria-label="Basculer mode sombre/clair">
                <svg class="icon-moon" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
                <svg class="icon-sun" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </button>
            <!-- Notifications bell -->
            <a href="{{ route('notifications.index') }}" class="te-notif">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                @php $unreadCount = Auth::user()->unreadNotifications()->count(); @endphp
                <span class="te-notif-badge" id="te-notif-badge" style="{{ $unreadCount > 0 ? '' : 'display:none' }}">{{ $unreadCount }}</span>
            </a>

            <!-- User dropdown -->
            <x-dropdown align="right" width="52">
                <x-slot name="trigger">
                    <button class="te-user-btn">
                        @if(Auth::user()->isCompany() && Auth::user()->logo_path)
                            <img src="{{ Auth::user()->logo_url }}"
                                 alt="{{ Auth::user()->name }}"
                                 style="width:28px;height:28px;border-radius:50%;object-fit:cover;flex-shrink:0;border:1px solid #E5E7EB;">
                        @else
                            <div class="te-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                        @endif

                        @if(Auth::user()->isAdmin())
                            <span class="te-role-indicator">Admin</span>
                        @elseif(Auth::user()->isCompany())
                            <span class="te-role-indicator">Entreprise</span>
                        @elseif(Auth::user()->isCandidate())
                            <span class="te-role-indicator">Candidat</span>
                        @endif

                        <span>{{ Auth::user()->name }}</span>
                        <svg class="te-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <div style="padding:.75rem 1rem;border-bottom:1px solid #E5E7EB;margin-bottom:.5rem;display:flex;align-items:center;gap:.75rem;">
                        @if(Auth::user()->isCompany() && Auth::user()->logo_path)
                            <img src="{{ Auth::user()->logo_url }}"
                                 alt="{{ Auth::user()->name }}"
                                 style="width:40px;height:40px;border-radius:8px;object-fit:cover;flex-shrink:0;border:1px solid #E5E7EB;">
                        @else
                            <div style="width:40px;height:40px;border-radius:8px;background:linear-gradient(135deg,#4F46E5,#818CF8);display:flex;align-items:center;justify-content:center;color:white;font-size:.9rem;font-weight:800;flex-shrink:0;">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        @endif
                        <div style="min-width:0;">
                            <div style="font-weight:700;font-size:.85rem;color:#111827;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ Auth::user()->name }}</div>
                            <div style="font-size:.75rem;color:#6B7280;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ Auth::user()->email }}</div>
                        </div>
                    </div>

                    <x-dropdown-link :href="route('profile.edit')">
                        <div style="display:flex;align-items:center;gap:.5rem">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            {{ __('Mon profil') }}
                        </div>
                    </x-dropdown-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                            <div style="display:flex;align-items:center;gap:.5rem;color:#EF4444">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                {{ __('Se déconnecter') }}
                            </div>
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>
        @else
        <div class="te-actions">
            <button class="te-dm-toggle" id="dmToggle" onclick="toggleDarkMode()" title="Basculer mode sombre/clair" aria-label="Basculer mode sombre/clair">
                <svg class="icon-moon" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
                <svg class="icon-sun" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </button>
            <a href="{{ route('login') }}"
               style="display:inline-flex;align-items:center;padding:.45rem 1rem;border-radius:8px;border:1.5px solid #E5E7EB;background:#fff;color:#374151;font-size:.875rem;font-weight:600;text-decoration:none;transition:all .15s;"
               onmouseover="this.style.borderColor='#4F46E5';this.style.color='#4F46E5'"
               onmouseout="this.style.borderColor='#E5E7EB';this.style.color='#374151'">
                Se connecter
            </a>
            <a href="{{ route('register') }}"
               style="display:inline-flex;align-items:center;padding:.45rem 1rem;border-radius:8px;background:#4F46E5;color:#fff;font-size:.875rem;font-weight:700;text-decoration:none;box-shadow:0 2px 8px rgba(79,70,229,.3);transition:background .15s;"
               onmouseover="this.style.background='#3730A3'"
               onmouseout="this.style.background='#4F46E5'">
                Créer un compte
            </a>
        </div>
        @endauth

        <!-- Hamburger (mobile) -->
        <button class="te-hamburger" @click="open = !open" aria-label="Menu">
            <svg x-show="!open" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            <svg x-show="open" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <!-- ── Mobile menu ────────────────────────────────────────── -->
    <div class="te-mobile-menu" :class="{ 'open': open }">

        @auth
        <!-- User card with role indicator -->
        <div class="te-mobile-user">
            @if(Auth::user()->isCompany() && Auth::user()->logo_path)
                <img src="{{ Auth::user()->logo_url }}"
                     alt="{{ Auth::user()->name }}"
                     style="width:44px;height:44px;border-radius:10px;object-fit:cover;flex-shrink:0;border:1.5px solid rgba(79,70,229,.2);">
            @else
                <div class="te-mobile-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
            @endif
            <div class="te-mobile-user-info">
                <div style="display:flex;align-items:center;gap:.5rem;flex-wrap:wrap;">
                    <span class="te-mobile-user-name">{{ Auth::user()->name }}</span>
                    @if(Auth::user()->isAdmin())
                        <span class="te-role-indicator-mobile">Admin</span>
                    @elseif(Auth::user()->isCompany())
                        <span class="te-role-indicator-mobile">Entreprise</span>
                    @elseif(Auth::user()->isCandidate())
                        <span class="te-role-indicator-mobile">Candidat</span>
                    @endif
                </div>
                <div class="te-mobile-user-email">{{ Auth::user()->email }}</div>
            </div>
        </div>

        <div class="te-mobile-links">
            <div class="te-section-label">Navigation</div>
            <a href="{{ route('job-offers.index') }}"
               class="te-mobile-link {{ request()->routeIs('job-offers.index') ? 'active' : '' }}">
                Offres d'emploi
            </a>

            {{-- ════════ ADMIN mobile ════════ --}}
            @if(Auth::user()->isAdmin())
                <div class="te-section-label">Administration</div>
                <a href="{{ route('admin.dashboard') }}"
                   class="te-mobile-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    Dashboard
                </a>
                <a href="{{ route('admin.categories.index') }}"
                   class="te-mobile-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    Catégories
                </a>
                <a href="{{ route('admin.offers.moderation') }}"
                   class="te-mobile-link {{ request()->routeIs('admin.offers.*') ? 'active' : '' }}">
                    Modération Offres
                    @if($navBadgeOffers > 0)
                        <span class="te-mobile-badge">{{ $navBadgeOffers > 99 ? '99+' : $navBadgeOffers }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.users.moderation') }}"
                   class="te-mobile-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    Utilisateurs
                    @if($navBadgeUsers > 0)
                        <span class="te-mobile-badge">{{ $navBadgeUsers > 99 ? '99+' : $navBadgeUsers }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.reports.index') }}"
                   class="te-mobile-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    Signalements
                    @if($navBadgeReports > 0)
                        <span class="te-mobile-badge">{{ $navBadgeReports > 99 ? '99+' : $navBadgeReports }}</span>
                    @endif
                </a>
            @endif

            {{-- ════════ CANDIDAT mobile ════════ --}}
            @if(Auth::user()->isCandidate())
                <div class="te-section-label">Mon espace</div>
                <a href="{{ route('applications.index') }}"
                   class="te-mobile-link {{ request()->routeIs('applications.*') ? 'active' : '' }}">
                    Mes candidatures
                    @if($navBadgeMyApps > 0)
                        <span class="te-mobile-badge">{{ $navBadgeMyApps > 99 ? '99+' : $navBadgeMyApps }}</span>
                    @endif
                </a>
                <a href="{{ route('saved-jobs.index') }}"
                   class="te-mobile-link {{ request()->routeIs('saved-jobs.*') ? 'active' : '' }}">
                    Mes favoris
                </a>
            @endif

            {{-- ════════ ENTREPRISE mobile ════════ --}}
            @if(Auth::user()->isCompany())
                <div class="te-section-label">Mon entreprise</div>
                <a href="{{ route('job-offers.my-offers') }}"
                   class="te-mobile-link {{ request()->routeIs('job-offers.my-offers') ? 'active' : '' }}">
                    Mes offres
                    @if($navBadgeMyOffers > 0)
                        <span class="te-mobile-badge">{{ $navBadgeMyOffers > 99 ? '99+' : $navBadgeMyOffers }}</span>
                    @endif
                </a>
                <a href="{{ route('candidates.index') }}"
                   class="te-mobile-link {{ request()->routeIs('candidates.*') ? 'active' : '' }}">
                    Candidats
                </a>
            @endif

            <div class="te-section-label">Compte</div>
            <a href="{{ route('notifications.index') }}"
               class="te-mobile-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                Notifications
                @if(Auth::user()->unreadNotifications->count() > 0)
                    <span class="te-mobile-badge">{{ Auth::user()->unreadNotifications->count() }}</span>
                @endif
            </a>
            <a href="{{ route('profile.edit') }}" class="te-mobile-link">Mon profil</a>

            <div class="te-divider"></div>

            <button onclick="toggleDarkMode()"
                style="width:100%;display:flex;align-items:center;gap:.6rem;padding:.7rem .9rem;border-radius:var(--radius-sm);border:none;background:none;cursor:pointer;font-family:inherit;font-size:.9rem;font-weight:600;color:var(--text-muted);text-align:left;transition:background var(--transition),color var(--transition);"
                onmouseover="this.style.background='var(--primary-light)';this.style.color='var(--primary)'"
                onmouseout="this.style.background='none';this.style.color='var(--text-muted)'">
                <svg id="dmMobileIcon" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
                <span id="dmMobileLabel">Mode sombre</span>
            </button>

            <div class="te-divider"></div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" style="width:100%;text-align:left;background:none;border:none;cursor:pointer;padding:0;font-family:inherit">
                    <span class="te-mobile-link" style="color:#EF4444">Se déconnecter</span>
                </button>
            </form>
        </div>
        @else
        <div class="te-mobile-links" style="margin-top:.5rem;">
            <a href="{{ route('job-offers.index') }}" class="te-mobile-link {{ request()->routeIs('job-offers.index') ? 'active' : '' }}">
                Offres d'emploi
            </a>
            <div class="te-divider"></div>
            <a href="{{ route('login') }}"
               style="display:flex;align-items:center;justify-content:center;padding:.7rem;border-radius:8px;border:1.5px solid #E5E7EB;background:#fff;color:#374151;font-size:.9rem;font-weight:600;text-decoration:none;margin-bottom:.5rem;">
                Se connecter
            </a>
            <a href="{{ route('register') }}"
               style="display:flex;align-items:center;justify-content:center;padding:.7rem;border-radius:8px;background:#4F46E5;color:#fff;font-size:.9rem;font-weight:700;text-decoration:none;">
                Créer un compte
            </a>
        </div>
        @endauth
    </div>
</nav>

<script>
/* ── Dark mode init (runs before paint to avoid flash) ── */
(function () {
    if (localStorage.getItem('theme') === 'dark') {
        document.documentElement.setAttribute('data-theme', 'dark');
    }
})();

function toggleDarkMode() {
    var isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    if (isDark) {
        document.documentElement.removeAttribute('data-theme');
        localStorage.setItem('theme', 'light');
    } else {
        document.documentElement.setAttribute('data-theme', 'dark');
        localStorage.setItem('theme', 'dark');
    }
    updateDmMobileBtn();
}

function updateDmMobileBtn() {
    var isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    var lbl  = document.getElementById('dmMobileLabel');
    var icon = document.getElementById('dmMobileIcon');
    if (lbl)  lbl.textContent = isDark ? 'Mode clair' : 'Mode sombre';
    if (icon) icon.innerHTML  = isDark
        ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>'
        : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>';
}

document.addEventListener('DOMContentLoaded', updateDmMobileBtn);
</script>

@auth
<script>
(function () {
    var badge = document.getElementById('te-notif-badge');
    if (!badge) return;
    function pollUnread() {
        fetch('{{ route('notifications.unread-count') }}', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                var count = data.count || 0;
                badge.textContent = count;
                badge.style.display = count > 0 ? '' : 'none';
            })
            .catch(function() {});
    }
    setInterval(pollUnread, 30000);
})();
</script>
@endauth

