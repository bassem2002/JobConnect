<x-app-layout>
    <x-slot name="header">
        <x-page-header
            :title="$candidate->name"
            subtitle="Profil du candidat"
            icon="user"
            :breadcrumbs="[
                ['label' => session('nav_level0.label', 'Candidats'), 'url' => session('nav_level0.url', route('candidates.index'))],
                ['label' => $candidate->name],
            ]"
        />
    </x-slot>

    @php
        $initial = strtoupper(substr($candidate->name, 0, 1));
    @endphp

    <style>
    /* ══ Design tokens (aligned with job-offers/show) ═══════════ */
    :root {
        --p:       #4F46E5;
        --p-dk:    #3730A3;
        --p-lt:    #EEF2FF;
        --p-mid:   #818CF8;
        --border:  #E5E7EB;
        --border2: #D1D5DB;
        --card:    #FFFFFF;
        --surf:    #F8F9FC;
        --txt:     #111827;
        --muted:   #6B7280;
        --r:       16px;
        --r-sm:    10px;
        --sh:      0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
        --sh-md:   0 4px 20px rgba(79,70,229,.1);
        --tr:      .18s cubic-bezier(.4,0,.2,1);
    }

    /* ── Breadcrumb ─────────────────────────────────────────────── */
    .show-breadcrumb { display:flex; align-items:center; gap:.4rem; flex-wrap:wrap; }
    .show-bc-link { display:inline-flex; align-items:center; gap:.3rem; font-size:.8rem; font-weight:600; color:var(--muted); text-decoration:none; transition:color var(--tr); }
    .show-bc-link:hover { color:var(--p); }
    .show-bc-sep { color:#D1D5DB; }
    .show-bc-current { font-size:.8rem; font-weight:700; color:var(--txt); }

    /* ── Page layout ────────────────────────────────────────────── */
    .show-page {
        max-width: 80rem; margin: 0 auto;
        padding: 1.75rem 1.5rem 3rem;
        display: grid; grid-template-columns: 1fr 320px;
        gap: 1.75rem; align-items: start;
    }
    @media (max-width: 960px) { .show-page { grid-template-columns: 1fr; padding: 1rem; } }

    /* ── Sticky sidebar ─────────────────────────────────────────── */
    .show-sidebar { position: sticky; top: 5rem; display: flex; flex-direction: column; gap: 1.25rem; }

    /* ── Card base ──────────────────────────────────────────────── */
    .show-card { background:var(--card); border:1px solid var(--border); border-radius:var(--r); box-shadow:var(--sh); overflow:hidden; }
    .show-card + .show-card { margin-top: 1.25rem; }
    .show-card-head { display:flex; align-items:center; gap:.65rem; padding:1rem 1.5rem; border-bottom:1px solid #F3F4F6; background:#FAFBFF; }
    .show-card-head-icon { width:28px; height:28px; border-radius:8px; flex-shrink:0; display:flex; align-items:center; justify-content:center; }
    .show-card-head h2 { font-size:.95rem; font-weight:800; color:var(--txt); margin:0; }
    .show-card-body { padding: 1.5rem; }

    /* ── Hero ───────────────────────────────────────────────────── */
    .show-hero { border-radius:var(--r); overflow:hidden; background:var(--card); border:1px solid var(--border); box-shadow:var(--sh); margin-bottom:1.25rem; }
    .show-hero-banner {
        background: linear-gradient(135deg, #1e1b4b 0%, #3730a3 45%, #4f46e5 75%, #818cf8 100%);
        padding: 1.75rem 1.75rem 2.75rem; position:relative; overflow:hidden;
    }
    .show-hero-banner::before {
        content:''; position:absolute; inset:0;
        background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='30' cy='30' r='20'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
        pointer-events:none;
    }
    .show-hero-banner::after {
        content:''; position:absolute; bottom:-1px; left:0; right:0; height:32px;
        background:var(--card); clip-path:ellipse(55% 100% at 50% 100%);
    }
    .show-hero-top { display:flex; align-items:flex-start; gap:1rem; position:relative; z-index:1; }
    .show-hero-logo-ph {
        width:72px; height:72px; border-radius:16px; flex-shrink:0;
        background:rgba(255,255,255,.15); border:3px solid rgba(255,255,255,.25);
        display:flex; align-items:center; justify-content:center;
        font-size:1.75rem; font-weight:900; color:white;
        box-shadow:0 8px 24px rgba(0,0,0,.2); backdrop-filter:blur(4px);
    }
    .show-hero-info { flex:1; min-width:0; }
    .show-hero-subtitle { font-size:.78rem; font-weight:700; color:rgba(255,255,255,.7); text-transform:uppercase; letter-spacing:.06em; margin-bottom:.35rem; }
    .show-hero-title { font-size:1.5rem; font-weight:900; color:white; margin:0 0 .6rem; line-height:1.2; text-shadow:0 1px 3px rgba(0,0,0,.2); }
    @media(max-width:600px) { .show-hero-title { font-size:1.2rem; } }
    .show-hero-tags { display:flex; flex-wrap:wrap; gap:.45rem; position:relative; z-index:1; margin-top:.5rem; }
    .show-hero-pullup { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:.75rem; padding:1rem 1.75rem 1.25rem; background:white; margin-top:-1px; }
    .show-hero-meta { display:flex; align-items:center; gap:1.25rem; flex-wrap:wrap; }
    .show-hero-meta-item { display:flex; align-items:center; gap:.4rem; font-size:.8rem; color:var(--muted); font-weight:600; }
    .show-hero-meta-item svg { color:var(--p); flex-shrink:0; }

    /* ── Tags ───────────────────────────────────────────────────── */
    .show-tag { display:inline-flex; align-items:center; gap:.3rem; padding:.28rem .75rem; border-radius:99px; font-size:.72rem; font-weight:700; border:1px solid; white-space:nowrap; }
    .show-tag-hero { background:rgba(255,255,255,.15); border-color:rgba(255,255,255,.25); color:white; backdrop-filter:blur(4px); }
    .show-tag-green  { color:#065F46; background:#ECFDF5; border-color:#A7F3D0; }
    .show-tag-indigo { color:#3730A3; background:#EEF2FF; border-color:#C7D2FE; }
    .show-tag-gray   { color:#374151; background:#F9FAFB; border-color:#E5E7EB; }
    .show-tag-blue   { color:#1E40AF; background:#EFF6FF; border-color:#BFDBFE; }

    /* ── Section title ──────────────────────────────────────────── */
    .show-section-title { display:flex; align-items:center; gap:.5rem; font-size:.7rem; font-weight:800; text-transform:uppercase; letter-spacing:.08em; color:var(--muted); margin:0 0 1rem; }
    .show-section-title::after { content:''; flex:1; height:1px; background:#F3F4F6; }
    .show-section-title svg { flex-shrink:0; color:var(--p); }

    /* ── Detail chips ───────────────────────────────────────────── */
    .show-details-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:.85rem; }
    @media(max-width:600px){ .show-details-grid { grid-template-columns:1fr; } }
    .show-detail-chip { display:flex; align-items:center; gap:.75rem; background:var(--surf); border:1px solid var(--border); border-radius:var(--r-sm); padding:.75rem 1rem; transition:box-shadow var(--tr),transform var(--tr); }
    .show-detail-chip:hover { box-shadow:var(--sh-md); transform:translateY(-1px); }
    .show-detail-chip-icon { width:36px; height:36px; border-radius:8px; flex-shrink:0; display:flex; align-items:center; justify-content:center; }
    .show-detail-chip-icon.indigo { background:var(--p-lt); }
    .show-detail-chip-icon.indigo svg { color:var(--p); }
    .show-detail-chip-icon.green  { background:#F0FDF4; }
    .show-detail-chip-icon.green  svg { color:#16A34A; }
    .show-detail-chip-icon.blue   { background:#EFF6FF; }
    .show-detail-chip-icon.blue   svg { color:#2563EB; }
    .show-detail-chip-icon.purple { background:#FAF5FF; }
    .show-detail-chip-icon.purple svg { color:#9333EA; }
    .show-detail-chip-icon.amber  { background:#FFFBEB; }
    .show-detail-chip-icon.amber  svg { color:#D97706; }
    .show-detail-chip-icon.slate  { background:#F1F5F9; }
    .show-detail-chip-icon.slate  svg { color:#64748B; }
    .show-detail-chip-label { font-size:.68rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:var(--muted); margin-bottom:.15rem; }
    .show-detail-chip-value { font-size:.875rem; font-weight:800; color:var(--txt); }

    /* ── Bio prose ──────────────────────────────────────────────── */
    .show-prose { font-size:.9rem; color:#374151; line-height:1.8; white-space:pre-line; margin:0; }
    .show-prose-empty { font-size:.875rem; color:#D1D5DB; font-style:italic; margin:0; }
    .show-hero-bio { font-size:.82rem; color:rgba(255,255,255,.78); line-height:1.65; margin:.5rem 0 0; white-space:pre-line; }

    /* ── Sidebar stat rows ──────────────────────────────────────── */
    .sb-stat-row { display:flex; align-items:center; justify-content:space-between; padding:.6rem 0; border-bottom:1px solid #F3F4F6; }
    .sb-stat-row:last-child { border-bottom:none; }
    .sb-stat-label { font-size:.78rem; font-weight:600; color:var(--muted); }
    .sb-stat-val { font-size:.85rem; font-weight:800; color:var(--txt); }

    /* ── Buttons ────────────────────────────────────────────────── */
    .show-btn-solid {
        display:inline-flex; align-items:center; justify-content:center; gap:.45rem;
        width:100%; padding:.65rem 1rem; border-radius:var(--r-sm);
        background:var(--p); color:#fff; border:none;
        font-family:inherit; font-size:.85rem; font-weight:700;
        text-decoration:none; cursor:pointer;
        box-shadow:0 2px 8px rgba(79,70,229,.25);
        transition:background var(--tr),transform var(--tr),box-shadow var(--tr);
    }
    .show-btn-solid:hover { background:var(--p-dk); transform:translateY(-1px); box-shadow:0 4px 12px rgba(79,70,229,.35); }

    .show-btn-outline {
        display:inline-flex; align-items:center; justify-content:center; gap:.45rem;
        width:100%; padding:.63rem 1rem; border-radius:var(--r-sm);
        background:#fff; color:var(--p); border:1.5px solid #C7D2FE;
        font-family:inherit; font-size:.85rem; font-weight:700;
        text-decoration:none; cursor:pointer; transition:all var(--tr);
    }
    .show-btn-outline:hover { background:var(--p-lt); border-color:var(--p); }

    /* ── Report banner ──────────────────────────────────────────── */
    .show-report-card {
        background:#FEF2F2; border:1.5px solid #FECACA;
        border-radius:var(--r); padding:1rem 1.25rem;
        display:flex; align-items:center; justify-content:space-between; gap:.75rem; flex-wrap:wrap;
    }
    .show-report-btn {
        display:inline-flex; align-items:center; gap:.35rem;
        padding:.4rem .875rem; border-radius:8px;
        font-size:.8rem; font-weight:700; color:#DC2626;
        background:#fff; border:1.5px solid #FECACA;
        text-decoration:none; white-space:nowrap;
        transition:all var(--tr);
    }
    .show-report-btn:hover { background:#FEE2E2; border-color:#EF4444; }

    /* ── Animations ─────────────────────────────────────────────── */
    @keyframes fadeUp { from{opacity:0;transform:translateY(14px);} to{opacity:1;transform:none;} }
    .show-anim    { animation:fadeUp .35s ease both; }
    .show-anim-d1 { animation-delay:.05s; }
    .show-anim-d2 { animation-delay:.1s; }
    .show-anim-d3 { animation-delay:.15s; }
    </style>

    <div class="show-page">

        {{-- ══════════════  LEFT COLUMN  ══════════════ --}}
        <div>

            {{-- ── Hero Banner ── --}}
            <div class="show-hero show-anim">
                <div class="show-hero-banner">
                    <div class="show-hero-top">
                        <div class="show-hero-logo-ph" aria-hidden="true">{{ $initial }}</div>
                        <div class="show-hero-info">
                            @if($candidate->domain)
                                <div class="show-hero-subtitle">{{ $candidate->domain }}</div>
                            @else
                                <div class="show-hero-subtitle">Candidat</div>
                            @endif
                            <h1 class="show-hero-title">{{ $candidate->name }}</h1>
                            @if($candidate->bio)
                                <p class="show-hero-bio">{{ $candidate->bio }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="show-hero-tags">
                        @if($candidate->city)
                        <span class="show-tag show-tag-hero">
                            <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                            {{ $candidate->city }}
                        </span>
                        @endif
                        @if($candidate->experience_years)
                        <span class="show-tag show-tag-hero">
                            <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>
                            {{ $candidate->experience_years }} ans d'exp.
                        </span>
                        @endif
                        @if($candidate->education_level)
                        <span class="show-tag show-tag-hero">
                            <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M22 10v6M2 10l10-5 10 5-10 5z"/></svg>
                            {{ $candidate->education_level }}
                        </span>
                        @endif
                        @if($candidate->cv_path)
                        <span class="show-tag show-tag-hero">
                            <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            CV disponible
                        </span>
                        @endif
                    </div>
                </div>

                {{-- Pull-up meta row --}}
                <div class="show-hero-pullup">
                    <div class="show-hero-meta">
                        <div class="show-hero-meta-item">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span>{{ $candidate->email }}</span>
                        </div>
                        @if($candidate->phone)
                        <div class="show-hero-meta-item">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span>{{ $candidate->phone }}</span>
                        </div>
                        @endif
                        @if($candidate->linkedin_url)
                        <div class="show-hero-meta-item">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/>
                            </svg>
                            <a href="{{ $candidate->linkedin_url }}" target="_blank" style="color:var(--p);font-weight:700;text-decoration:none;">LinkedIn</a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ── Informations personnelles ── --}}
            <div class="show-card show-anim show-anim-d1">
                <div class="show-card-head">
                    <div class="show-card-head-icon" style="background:#EEF2FF;">
                        <svg width="15" height="15" fill="none" stroke="#4F46E5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <h2>Informations personnelles</h2>
                </div>
                <div class="show-card-body">
                    <div class="show-details-grid">

                        @if($candidate->birth_date)
                        <div class="show-detail-chip">
                            <div class="show-detail-chip-icon indigo">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            </div>
                            <div>
                                <div class="show-detail-chip-label">Date de naissance</div>
                                <div class="show-detail-chip-value">{{ \Carbon\Carbon::parse($candidate->birth_date)->format('d/m/Y') }}</div>
                            </div>
                        </div>
                        @endif

                        @if($candidate->city)
                        <div class="show-detail-chip">
                            <div class="show-detail-chip-icon purple">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                            </div>
                            <div>
                                <div class="show-detail-chip-label">Ville</div>
                                <div class="show-detail-chip-value">{{ $candidate->city }}</div>
                            </div>
                        </div>
                        @endif

                        <div class="show-detail-chip">
                            <div class="show-detail-chip-icon blue">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <div class="show-detail-chip-label">Email</div>
                                <div class="show-detail-chip-value" style="font-size:.78rem;word-break:break-all;">{{ $candidate->email }}</div>
                            </div>
                        </div>

                        @if($candidate->phone)
                        <div class="show-detail-chip">
                            <div class="show-detail-chip-icon green">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </div>
                            <div>
                                <div class="show-detail-chip-label">Téléphone</div>
                                <div class="show-detail-chip-value">{{ $candidate->phone }}</div>
                            </div>
                        </div>
                        @endif

                    </div>
                </div>
            </div>

            {{-- ── Parcours professionnel ── --}}
            <div class="show-card show-anim show-anim-d2">
                <div class="show-card-head">
                    <div class="show-card-head-icon" style="background:#EFF6FF;">
                        <svg width="15" height="15" fill="none" stroke="#2563EB" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h2>Parcours professionnel</h2>
                </div>
                <div class="show-card-body">
                    <div class="show-details-grid">

                        @if($candidate->education_level)
                        <div class="show-detail-chip">
                            <div class="show-detail-chip-icon blue">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                            </div>
                            <div>
                                <div class="show-detail-chip-label">Niveau d'étude</div>
                                <div class="show-detail-chip-value">{{ $candidate->education_level }}</div>
                            </div>
                        </div>
                        @endif

                        @if($candidate->experience_years)
                        <div class="show-detail-chip">
                            <div class="show-detail-chip-icon green">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>
                            </div>
                            <div>
                                <div class="show-detail-chip-label">Expérience</div>
                                <div class="show-detail-chip-value">{{ $candidate->experience_years }} ans</div>
                            </div>
                        </div>
                        @endif

                        @if($candidate->domain)
                        <div class="show-detail-chip">
                            <div class="show-detail-chip-icon indigo">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
                            </div>
                            <div>
                                <div class="show-detail-chip-label">Domaine</div>
                                <div class="show-detail-chip-value">{{ $candidate->domain }}</div>
                            </div>
                        </div>
                        @endif

                        @if($candidate->linkedin_url)
                        <div class="show-detail-chip">
                            <div class="show-detail-chip-icon purple">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>
                            </div>
                            <div>
                                <div class="show-detail-chip-label">LinkedIn</div>
                                <a href="{{ $candidate->linkedin_url }}" target="_blank"
                                   style="font-size:.85rem;color:var(--p);font-weight:700;text-decoration:none;">
                                    Voir le profil →
                                </a>
                            </div>
                        </div>
                        @endif

                    </div>
                </div>
            </div>

        </div>
        {{-- end left column --}}

        {{-- ══════════════  SIDEBAR  ══════════════ --}}
        <aside class="show-sidebar show-anim show-anim-d2">

            {{-- Actions card --}}
            <div class="show-card">
                <div class="show-card-head">
                    <div class="show-card-head-icon" style="background:#EEF2FF;">
                        <svg width="15" height="15" fill="none" stroke="#4F46E5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h2>Actions</h2>
                </div>
                <div class="show-card-body" style="display:flex;flex-direction:column;gap:.65rem;padding-top:1rem;padding-bottom:1rem;">
                    @if($candidate->cv_path)
                    <a href="{{ route('applications.download-cv-candidate', $candidate) }}" class="show-btn-solid">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Télécharger le CV
                    </a>
                    @endif
                    <a href="mailto:{{ $candidate->email }}" class="show-btn-outline">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        Contacter par email
                    </a>
                </div>
            </div>

            {{-- Quick stats card --}}
            <div class="show-card">
                <div class="show-card-head">
                    <div class="show-card-head-icon" style="background:#F0FDF4;">
                        <svg width="15" height="15" fill="none" stroke="#16A34A" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h2>Aperçu</h2>
                </div>
                <div class="show-card-body" style="padding-top:1rem;padding-bottom:1rem;">

                    @if($candidate->experience_years)
                    <div class="sb-stat-row">
                        <span class="sb-stat-label">Expérience</span>
                        <span class="sb-stat-val">{{ $candidate->experience_years }} ans</span>
                    </div>
                    @endif

                    @if($candidate->education_level)
                    <div class="sb-stat-row">
                        <span class="sb-stat-label">Niveau d'étude</span>
                        <span class="sb-stat-val" style="font-size:.78rem;text-align:right;max-width:140px;">{{ $candidate->education_level }}</span>
                    </div>
                    @endif

                    @if($candidate->city)
                    <div class="sb-stat-row">
                        <span class="sb-stat-label">Localisation</span>
                        <span class="sb-stat-val">{{ $candidate->city }}</span>
                    </div>
                    @endif

                    <div class="sb-stat-row">
                        <span class="sb-stat-label">CV disponible</span>
                        @if($candidate->cv_path)
                            <span style="display:inline-flex;align-items:center;gap:.3rem;font-size:.82rem;font-weight:700;color:#16A34A;">
                                <span style="width:7px;height:7px;border-radius:50%;background:#22C55E;"></span>
                                Oui
                            </span>
                        @else
                            <span style="font-size:.82rem;font-weight:700;color:#9CA3AF;">Non</span>
                        @endif
                    </div>

                </div>
            </div>

            {{-- Report card (company only, if applied) --}}
            @php
                $hasAppliedToSomeOffer = false;
                if(Auth::user()->isCompany()) {
                    $hasAppliedToSomeOffer = \App\Models\Application::where('user_id', $candidate->id)
                        ->whereHas('jobOffer', function($q) {
                            $q->where('user_id', Auth::id());
                        })->exists();
                }
            @endphp

            @if(Auth::user()->isCompany() && $hasAppliedToSomeOffer)
            <div class="show-report-card">
                <div>
                    <div style="font-size:.83rem;font-weight:800;color:#991B1B;">Un problème avec ce candidat ?</div>
                    <div style="font-size:.72rem;color:#DC2626;margin-top:.15rem;">Signalez-le à notre équipe de modération.</div>
                </div>
                <a href="{{ route('reports.create', $candidate) }}" class="show-report-btn">
                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                    </svg>
                    Signaler
                </a>
            </div>
            @endif

        </aside>

    </div>

@push('scripts')
<script>
document.querySelectorAll('.show-anim').forEach(el => {
    el.style.willChange = 'opacity, transform';
    const obs = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) { e.target.style.opacity = '1'; e.target.style.transform = 'none'; obs.unobserve(e.target); }
        });
    }, { threshold: 0.08 });
    obs.observe(el);
});
</script>
@endpush

</x-app-layout>
