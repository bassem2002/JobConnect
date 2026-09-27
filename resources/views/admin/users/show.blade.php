<x-app-layout>
    <x-slot name="header">
        <x-page-header
            :title="$user->name"
            :subtitle="$user->isCompany() ? 'Entreprise' : ($user->isAdmin() ? 'Administrateur' : 'Candidat')"
            icon="user"
            :breadcrumbs="[
                ['label' => session('nav_level0.label', 'Utilisateurs'), 'url' => session('nav_level0.url', route('admin.users.moderation'))],
                ['label' => $user->name],
            ]"
        />
    </x-slot>

    @php
        $roleLabel  = $user->isAdmin() ? 'Administrateur' : ($user->isCompany() ? 'Entreprise' : 'Candidat');
        $roleColors = [
            'admin'     => ['bg'=>'#F5F3FF','color'=>'#5B21B6','border'=>'#DDD6FE','dot'=>'#7C3AED'],
            'company'   => ['bg'=>'#EFF6FF','color'=>'#1D4ED8','border'=>'#BFDBFE','dot'=>'#3B82F6'],
            'candidate' => ['bg'=>'#EEF2FF','color'=>'#4338CA','border'=>'#C7D2FE','dot'=>'#4F46E5'],
        ];
        $rc = $roleColors[$user->role] ?? $roleColors['candidate'];
        $initial = strtoupper(substr($user->name, 0, 1));
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
    .show-bc-link {
        display:inline-flex; align-items:center; gap:.3rem;
        font-size:.8rem; font-weight:600; color:var(--muted);
        text-decoration:none; transition:color var(--tr);
    }
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
    .show-card-head {
        display:flex; align-items:center; gap:.65rem;
        padding: 1rem 1.5rem; border-bottom:1px solid #F3F4F6; background:#FAFBFF;
    }
    .show-card-head-icon { width:28px; height:28px; border-radius:8px; flex-shrink:0; display:flex; align-items:center; justify-content:center; }
    .show-card-head h2 { font-size:.95rem; font-weight:800; color:var(--txt); margin:0; display:flex; align-items:center; gap:.5rem; }
    .show-card-body { padding: 1.5rem; }

    /* ── Hero banner ────────────────────────────────────────────── */
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
    .show-hero-logo {
        width:72px; height:72px; border-radius:16px; object-fit:cover;
        border:3px solid rgba(255,255,255,.25); box-shadow:0 8px 24px rgba(0,0,0,.25);
        background:white; flex-shrink:0;
    }
    .show-hero-info { flex:1; min-width:0; }
    .show-hero-subtitle { font-size:.78rem; font-weight:700; color:rgba(255,255,255,.7); text-transform:uppercase; letter-spacing:.06em; margin-bottom:.35rem; }
    .show-hero-title { font-size:1.5rem; font-weight:900; color:white; margin:0 0 .6rem; line-height:1.2; text-shadow:0 1px 3px rgba(0,0,0,.2); }
    .show-hero-tags { display:flex; flex-wrap:wrap; gap:.45rem; position:relative; z-index:1; margin-top:.5rem; }
    .show-hero-pullup {
        display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:.75rem;
        padding:1rem 1.75rem 1.25rem; background:white; margin-top:-1px;
    }
    .show-hero-meta { display:flex; align-items:center; gap:1.25rem; flex-wrap:wrap; }
    .show-hero-meta-item { display:flex; align-items:center; gap:.4rem; font-size:.8rem; color:var(--muted); font-weight:600; }
    .show-hero-meta-item svg { color:var(--p); flex-shrink:0; }

    /* ── Tags ───────────────────────────────────────────────────── */
    .show-tag { display:inline-flex; align-items:center; gap:.3rem; padding:.28rem .75rem; border-radius:99px; font-size:.72rem; font-weight:700; border:1px solid; white-space:nowrap; }
    .show-tag-hero { background:rgba(255,255,255,.15); border-color:rgba(255,255,255,.25); color:white; backdrop-filter:blur(4px); }
    .show-tag-green  { color:#065F46; background:#ECFDF5; border-color:#A7F3D0; }
    .show-tag-amber  { color:#92400E; background:#FFFBEB; border-color:#FDE68A; }
    .show-tag-indigo { color:#3730A3; background:#EEF2FF; border-color:#C7D2FE; }
    .show-tag-purple { color:#5B21B6; background:#F5F3FF; border-color:#DDD6FE; }
    .show-tag-blue   { color:#1E40AF; background:#EFF6FF; border-color:#BFDBFE; }
    .show-tag-gray   { color:#374151; background:#F9FAFB; border-color:#E5E7EB; }

    /* ── Detail chips ───────────────────────────────────────────── */
    .show-details-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:.85rem; }
    @media (max-width:600px) { .show-details-grid { grid-template-columns:1fr; } }
    .show-detail-chip {
        display:flex; align-items:center; gap:.75rem;
        background:var(--surf); border:1px solid var(--border);
        border-radius:var(--r-sm); padding:.75rem 1rem;
        transition:box-shadow var(--tr), transform var(--tr);
    }
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

    /* ── Section title ──────────────────────────────────────────── */
    .show-section-title {
        display:flex; align-items:center; gap:.5rem;
        font-size:.7rem; font-weight:800; text-transform:uppercase; letter-spacing:.08em;
        color:var(--muted); margin:0 0 1rem;
    }
    .show-section-title::after { content:''; flex:1; height:1px; background:#F3F4F6; }
    .show-section-title svg { flex-shrink:0; color:var(--p); }

    /* ── Mini list rows ─────────────────────────────────────────── */
    .mini-row { display:flex; align-items:center; gap:.75rem; padding:.75rem 0; border-bottom:1px solid #F3F4F6; }
    .mini-row:last-child { border-bottom:none; }
    .mini-thumb { width:36px; height:36px; border-radius:9px; object-fit:cover; border:1px solid var(--border); flex-shrink:0; background:var(--p-lt); display:flex; align-items:center; justify-content:center; font-size:.75rem; font-weight:800; color:var(--p); }
    .mini-title { font-size:.85rem; font-weight:700; color:var(--txt); line-height:1.3; }
    .mini-sub { font-size:.72rem; color:var(--muted); margin-top:.1rem; }
    .mini-badge { display:inline-flex; align-items:center; padding:.2rem .6rem; border-radius:99px; font-size:.68rem; font-weight:700; border:1px solid; flex-shrink:0; margin-left:auto; }

    /* ── Action buttons ─────────────────────────────────────────── */
    .show-btn-solid {
        display:inline-flex; align-items:center; gap:.4rem;
        padding:.6rem 1.25rem; border-radius:8px;
        background:var(--p); color:white; font-size:.875rem; font-weight:700;
        text-decoration:none; box-shadow:0 2px 8px rgba(79,70,229,.25);
        transition:all var(--tr); border:none; cursor:pointer; font-family:inherit; white-space:nowrap;
    }
    .show-btn-solid:hover { background:var(--p-dk); box-shadow:0 4px 12px rgba(79,70,229,.35); transform:translateY(-1px); }

    .show-btn-ghost {
        display:inline-flex; align-items:center; gap:.4rem;
        padding:.6rem 1.25rem; border-radius:8px;
        border:1.5px solid var(--border2); background:white;
        color:#374151; font-size:.875rem; font-weight:600;
        text-decoration:none; transition:all var(--tr);
        cursor:pointer; font-family:inherit; white-space:nowrap;
    }
    .show-btn-ghost:hover { border-color:var(--p); color:var(--p); }

    .show-btn-danger {
        display:inline-flex; align-items:center; gap:.4rem;
        padding:.6rem 1.25rem; border-radius:8px;
        background:#FEF2F2; color:#991B1B; border:1.5px solid #FECACA;
        font-size:.875rem; font-weight:700; cursor:pointer; font-family:inherit;
        transition:all var(--tr); white-space:nowrap;
    }
    .show-btn-danger:hover { background:#FEE2E2; border-color:#F87171; }

    .show-btn-success {
        display:inline-flex; align-items:center; gap:.4rem;
        padding:.6rem 1.25rem; border-radius:8px;
        background:#F0FDF4; color:#166534; border:1.5px solid #A7F3D0;
        font-size:.875rem; font-weight:700; cursor:pointer; font-family:inherit;
        transition:all var(--tr); white-space:nowrap;
    }
    .show-btn-success:hover { background:#DCFCE7; border-color:#4ADE80; }

    /* Action row in sidebar card */
    .act-row { display:flex; align-items:center; justify-content:space-between; padding:.875rem 0; border-bottom:1px solid #F3F4F6; }
    .act-row:last-child { border-bottom:none; }
    .act-label { font-size:.875rem; font-weight:700; color:var(--txt); }
    .act-sub { font-size:.72rem; color:var(--muted); margin-top:.1rem; }

    /* ── Sidebar stat row ───────────────────────────────────────── */
    .sb-stat-row { display:flex; align-items:center; justify-content:space-between; padding:.6rem 0; border-bottom:1px solid #F3F4F6; }
    .sb-stat-row:last-child { border-bottom:none; }
    .sb-stat-label { font-size:.78rem; font-weight:600; color:var(--muted); }
    .sb-stat-val { font-size:.85rem; font-weight:800; color:var(--txt); }

    /* ── Modal ──────────────────────────────────────────────────── */
    .modal-bg { display:none; position:fixed; inset:0; z-index:500; background:rgba(15,23,42,.45); backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:1rem; }
    .modal-bg.open { display:flex; }
    .modal-box { background:#fff; border-radius:var(--r); width:100%; max-width:420px; border:1.5px solid var(--border); box-shadow:0 20px 60px rgba(0,0,0,.18); overflow:hidden; }
    .modal-hd { display:flex; align-items:flex-start; gap:1rem; padding:1.25rem 1.5rem; border-bottom:1px solid var(--border); }
    .modal-ico { width:42px; height:42px; border-radius:10px; flex-shrink:0; display:flex; align-items:center; justify-content:center; }
    .modal-title { font-size:1rem; font-weight:800; color:var(--txt); margin-bottom:.2rem; }
    .modal-msg { font-size:.85rem; color:var(--muted); line-height:1.55; margin:0; }
    .modal-ft { display:flex; justify-content:flex-end; gap:.75rem; padding:1rem 1.5rem; background:var(--surf); }
    .modal-cancel { padding:.53rem 1.2rem; border-radius:8px; border:1.5px solid var(--border); background:#fff; font-size:.85rem; font-weight:600; color:var(--muted); cursor:pointer; transition:all var(--tr); font-family:inherit; }
    .modal-cancel:hover { border-color:var(--p); color:var(--p); }
    .modal-ok { padding:.53rem 1.2rem; border-radius:8px; border:none; font-size:.85rem; font-weight:700; color:#fff; cursor:pointer; font-family:inherit; transition:opacity var(--tr); }
    .modal-ok:hover { opacity:.9; }

    /* ── Animations ─────────────────────────────────────────────── */
    @keyframes fadeUp { from{opacity:0;transform:translateY(14px);} to{opacity:1;transform:none;} }
    .show-anim   { animation:fadeUp .35s ease both; }
    .show-anim-d1{ animation-delay:.05s; }
    .show-anim-d2{ animation-delay:.1s; }
    .show-anim-d3{ animation-delay:.15s; }
    </style>

    <div class="show-page">

        {{-- ══════════════  LEFT COLUMN  ══════════════ --}}
        <div>

            {{-- ── Hero Banner ── --}}
            <div class="show-hero show-anim">
                <div class="show-hero-banner">
                    <div class="show-hero-top">
                        @if($user->isCompany() && $user->logo_path)
                            <img src="{{ $user->logo_url }}" alt="{{ $user->name }}" class="show-hero-logo">
                        @else
                            <div class="show-hero-logo-ph" aria-hidden="true">{{ $initial }}</div>
                        @endif
                        <div class="show-hero-info">
                            <div class="show-hero-subtitle">{{ $roleLabel }}</div>
                            <h1 class="show-hero-title">{{ $user->name }}</h1>
                        </div>
                    </div>
                    <div class="show-hero-tags">
                        {{-- Role badge --}}
                        @if($user->isAdmin())
                            <span class="show-tag show-tag-hero">
                                <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                Administrateur
                            </span>
                        @elseif($user->isCompany())
                            <span class="show-tag show-tag-hero">
                                <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                                Entreprise
                            </span>
                        @else
                            <span class="show-tag show-tag-hero">
                                <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Candidat
                            </span>
                        @endif
                        {{-- Validation badge --}}
                        @if($user->is_validated)
                            <span class="show-tag show-tag-hero">
                                <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                Compte validé
                            </span>
                        @else
                            <span class="show-tag" style="background:rgba(251,191,36,.2);border-color:rgba(251,191,36,.4);color:#FDE68A;">
                                <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                En attente de validation
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
                            <span>{{ $user->email }}</span>
                        </div>
                        @if($user->city)
                        <div class="show-hero-meta-item">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            <span>{{ $user->city }}</span>
                        </div>
                        @endif
                        <div class="show-hero-meta-item">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>Inscrit le {{ $user->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Profile Info Card ── --}}
            <div class="show-card show-anim show-anim-d1">
                <div class="show-card-head">
                    <div class="show-card-head-icon" style="background:#EEF2FF;">
                        <svg width="15" height="15" fill="none" stroke="#4F46E5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <h2>Informations du profil</h2>
                </div>
                <div class="show-card-body">
                    <div class="show-details-grid">

                        <div class="show-detail-chip">
                            <div class="show-detail-chip-icon indigo">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <div>
                                <div class="show-detail-chip-label">Nom complet</div>
                                <div class="show-detail-chip-value">{{ $user->name }}</div>
                            </div>
                        </div>

                        <div class="show-detail-chip">
                            <div class="show-detail-chip-icon blue">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <div class="show-detail-chip-label">Email</div>
                                <div class="show-detail-chip-value" style="font-size:.78rem;word-break:break-all;">{{ $user->email }}</div>
                            </div>
                        </div>

                        @if($user->phone)
                        <div class="show-detail-chip">
                            <div class="show-detail-chip-icon green">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </div>
                            <div>
                                <div class="show-detail-chip-label">Téléphone</div>
                                <div class="show-detail-chip-value">{{ $user->phone }}</div>
                            </div>
                        </div>
                        @endif

                        @if($user->city)
                        <div class="show-detail-chip">
                            <div class="show-detail-chip-icon purple">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                            </div>
                            <div>
                                <div class="show-detail-chip-label">Ville</div>
                                <div class="show-detail-chip-value">{{ $user->city }}</div>
                            </div>
                        </div>
                        @endif

                        @if($user->isCompany() && $user->company_size)
                        <div class="show-detail-chip">
                            <div class="show-detail-chip-icon amber">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <div class="show-detail-chip-label">Taille entreprise</div>
                                <div class="show-detail-chip-value">{{ $user->company_size }} employés</div>
                            </div>
                        </div>
                        @endif

                        @if($user->isCandidate() && $user->experience_years)
                        <div class="show-detail-chip">
                            <div class="show-detail-chip-icon green">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>
                            </div>
                            <div>
                                <div class="show-detail-chip-label">Expérience</div>
                                <div class="show-detail-chip-value">{{ $user->experience_years }} ans</div>
                            </div>
                        </div>
                        @endif

                        @if($user->isCandidate() && $user->education_level)
                        <div class="show-detail-chip">
                            <div class="show-detail-chip-icon blue">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                            </div>
                            <div>
                                <div class="show-detail-chip-label">Niveau d'étude</div>
                                <div class="show-detail-chip-value">{{ $user->education_level }}</div>
                            </div>
                        </div>
                        @endif

                        @if($user->isCandidate() && $user->domain)
                        <div class="show-detail-chip">
                            <div class="show-detail-chip-icon purple">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
                            </div>
                            <div>
                                <div class="show-detail-chip-label">Domaine</div>
                                <div class="show-detail-chip-value">{{ $user->domain }}</div>
                            </div>
                        </div>
                        @endif

                        <div class="show-detail-chip">
                            <div class="show-detail-chip-icon slate">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <div class="show-detail-chip-label">Inscription</div>
                                <div class="show-detail-chip-value">{{ $user->created_at->format('d/m/Y') }}</div>
                            </div>
                        </div>

                        <div class="show-detail-chip">
                            <div class="show-detail-chip-icon slate">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            </div>
                            <div>
                                <div class="show-detail-chip-label">Mise à jour</div>
                                <div class="show-detail-chip-value">{{ $user->updated_at->format('d/m/Y') }}</div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- ── Recent Offers (company) ── --}}
            @if($user->isCompany() && $recentOffers->count() > 0)
            <div class="show-card show-anim show-anim-d2">
                <div class="show-card-head">
                    <div class="show-card-head-icon" style="background:#EFF6FF;">
                        <svg width="15" height="15" fill="none" stroke="#2563EB" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h2>Offres récentes <span style="font-size:.75rem;font-weight:600;color:var(--muted);padding:.15rem .5rem;background:var(--surf);border:1px solid var(--border);border-radius:99px;">{{ $user->job_offers_count }}</span></h2>
                </div>
                <div class="show-card-body">
                    @foreach($recentOffers as $offer)
                    @php $stMap = ['open'=>['#ECFDF5','#065F46','#A7F3D0','Ouverte'],'closed'=>['#FEF2F2','#991B1B','#FECACA','Clôturée'],'pending_validation'=>['#FFFBEB','#92400E','#FDE68A','En attente'],'refused'=>['#FEF2F2','#991B1B','#FECACA','Refusée']]; $st = $stMap[$offer->status] ?? ['#F9FAFB','#374151','#E5E7EB',ucfirst($offer->status)]; @endphp
                    <div class="mini-row">
                        @if($offer->offerCategory && $offer->offerCategory->image_url)
                            <img src="{{ $offer->offerCategory->image_url }}" alt="" class="mini-thumb" style="width:36px;height:36px;border-radius:9px;object-fit:cover;border:1px solid var(--border);">
                        @else
                            <div class="mini-thumb">{{ strtoupper(substr($offer->title,0,1)) }}</div>
                        @endif
                        <div style="flex:1;min-width:0;">
                            <div class="mini-title">{{ $offer->title }}</div>
                            <div class="mini-sub">{{ $offer->applications_count }} candidature(s) · {{ $offer->created_at->format('d/m/Y') }}</div>
                        </div>
                        <span class="mini-badge" style="background:{{ $st[0] }};color:{{ $st[1] }};border-color:{{ $st[2] }};">{{ $st[3] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- ── Recent Applications (candidate) ── --}}
            @if($user->isCandidate() && $recentApplications->count() > 0)
            <div class="show-card show-anim show-anim-d2">
                <div class="show-card-head">
                    <div class="show-card-head-icon" style="background:#EFF6FF;">
                        <svg width="15" height="15" fill="none" stroke="#2563EB" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h2>Candidatures récentes <span style="font-size:.75rem;font-weight:600;color:var(--muted);padding:.15rem .5rem;background:var(--surf);border:1px solid var(--border);border-radius:99px;">{{ $user->applications_count }}</span></h2>
                </div>
                <div class="show-card-body">
                    @foreach($recentApplications as $app)
                    @php $aMap = ['pending'=>['#FFFBEB','#92400E','#FDE68A','En attente'],'accepted'=>['#ECFDF5','#065F46','#A7F3D0','Acceptée'],'rejected'=>['#FEF2F2','#991B1B','#FECACA','Refusée']]; $am = $aMap[$app->status] ?? ['#F9FAFB','#374151','#E5E7EB',ucfirst($app->status)]; @endphp
                    <div class="mini-row">
                        <img src="{{ $app->jobOffer->company->logo_path ? $app->jobOffer->company->logo_url : 'https://ui-avatars.com/api/?name='.urlencode($app->jobOffer->company->name).'&background=EEF2FF&color=4F46E5&bold=true' }}"
                             alt="" style="width:36px;height:36px;border-radius:9px;object-fit:cover;border:1px solid var(--border);flex-shrink:0;">
                        <div style="flex:1;min-width:0;">
                            <div class="mini-title">{{ $app->jobOffer->title }}</div>
                            <div class="mini-sub">{{ $app->jobOffer->company->name }} · {{ $app->created_at->format('d/m/Y') }}</div>
                        </div>
                        <span class="mini-badge" style="background:{{ $am[0] }};color:{{ $am[1] }};border-color:{{ $am[2] }};">{{ $am[3] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
        {{-- end left column --}}

        {{-- ══════════════  SIDEBAR  ══════════════ --}}
        <aside class="show-sidebar show-anim show-anim-d2">

            {{-- Statistics card --}}
            <div class="show-card">
                <div class="show-card-head">
                    <div class="show-card-head-icon" style="background:#EEF2FF;">
                        <svg width="15" height="15" fill="none" stroke="#4F46E5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h2>Statistiques</h2>
                </div>
                <div class="show-card-body" style="padding-top:1rem;padding-bottom:1rem;">
                    @if($user->isCompany())
                    <div class="sb-stat-row">
                        <span class="sb-stat-label">Offres publiées</span>
                        <span class="sb-stat-val" style="color:var(--p);">{{ $user->job_offers_count }}</span>
                    </div>
                    @endif
                    @if($user->isCandidate())
                    <div class="sb-stat-row">
                        <span class="sb-stat-label">Candidatures</span>
                        <span class="sb-stat-val" style="color:var(--p);">{{ $user->applications_count }}</span>
                    </div>
                    @endif
                    <div class="sb-stat-row">
                        <span class="sb-stat-label">Validation</span>
                        <span class="sb-stat-val" style="color:{{ $user->is_validated ? '#16A34A' : '#D97706' }};">
                            {{ $user->is_validated ? 'Validé' : 'Non validé' }}
                        </span>
                    </div>
                    <div class="sb-stat-row">
                        <span class="sb-stat-label">Rôle</span>
                        <span class="sb-stat-val">{{ $roleLabel }}</span>
                    </div>
                    <div class="sb-stat-row">
                        <span class="sb-stat-label">Membre depuis</span>
                        <span class="sb-stat-val">{{ $user->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>

            {{-- Actions card (non-admins only) --}}
            @if(!$user->isAdmin())
            <div class="show-card">
                <div class="show-card-head">
                    <div class="show-card-head-icon" style="background:#F0FDF4;">
                        <svg width="15" height="15" fill="none" stroke="#16A34A" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/>
                        </svg>
                    </div>
                    <h2>Actions</h2>
                </div>
                <div class="show-card-body" style="display:flex;flex-direction:column;gap:.75rem;padding-top:1rem;padding-bottom:1rem;">

                    @if(!$user->is_validated)
                    <div class="act-row">
                        <div>
                            <div class="act-label">Validation du compte</div>
                            <div class="act-sub">Activer l'accès à la plateforme</div>
                        </div>
                        <form method="POST" action="{{ route('admin.users.status', $user) }}"
                              class="js-user-form" data-variant="validate" data-name="{{ $user->name }}"
                              style="display:contents;">
                            @csrf @method('PATCH')
                            <input type="hidden" name="is_validated" value="1">
                            <button type="submit" class="show-btn-solid" style="padding:.45rem .9rem;font-size:.78rem;">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                                Valider
                            </button>
                        </form>
                    </div>
                    @endif

                    <div class="act-row">
                        <div>
                            <div class="act-label">{{ $user->is_blocked ? 'Compte bloqué' : 'Accès' }}</div>
                            <div class="act-sub">{{ $user->is_blocked ? 'Réactiver l\'accès' : 'Empêcher la connexion' }}</div>
                        </div>
                        <form method="POST" action="{{ route('admin.users.status', $user) }}"
                              class="js-user-form"
                              data-variant="{{ $user->is_blocked ? 'unblock' : 'block' }}"
                              data-name="{{ $user->name }}"
                              style="display:contents;">
                            @csrf @method('PATCH')
                            <input type="hidden" name="is_blocked" value="{{ $user->is_blocked ? '0' : '1' }}">
                            @if($user->is_blocked)
                                <button type="submit" class="show-btn-success" style="padding:.45rem .9rem;font-size:.78rem;">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                                    Débloquer
                                </button>
                            @else
                                <button type="submit" class="show-btn-danger" style="padding:.45rem .9rem;font-size:.78rem;">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                    Bloquer
                                </button>
                            @endif
                        </form>
                    </div>

                </div>
            </div>
            @endif

        </aside>

    </div>

    {{-- Confirm modal --}}
    <div id="us-modal" class="modal-bg" role="dialog" aria-modal="true">
        <div class="modal-box">
            <div class="modal-hd">
                <div class="modal-ico" id="us-modal-ico"></div>
                <div>
                    <h3 class="modal-title" id="us-modal-title"></h3>
                    <p class="modal-msg" id="us-modal-msg"></p>
                </div>
            </div>
            <div class="modal-ft">
                <button class="modal-cancel" id="us-modal-cancel">Annuler</button>
                <button class="modal-ok" id="us-modal-ok"></button>
            </div>
        </div>
    </div>

    <script>
    (function(){
        var modal=document.getElementById('us-modal'),ico=document.getElementById('us-modal-ico'),
            title=document.getElementById('us-modal-title'),msg=document.getElementById('us-modal-msg'),
            btnOk=document.getElementById('us-modal-ok'),btnNo=document.getElementById('us-modal-cancel'),
            pending=null;
        var configs={
            validate:{color:'#4F46E5',bg:'#EEF2FF',icon:'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>',title:'Valider ce compte ?',msg:'L\'utilisateur pourra accéder à la plateforme.',btn:'Valider'},
            block:{color:'#DC2626',bg:'#FEF2F2',icon:'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>',title:'Bloquer cet utilisateur ?',msg:'Il ne pourra plus se connecter à la plateforme.',btn:'Bloquer'},
            unblock:{color:'#166534',bg:'#F0FDF4',icon:'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>',title:'Débloquer cet utilisateur ?',msg:'Il pourra de nouveau accéder à la plateforme.',btn:'Débloquer'},
        };
        function open(form,variant,name){
            pending=form;var c=configs[variant]||configs.block;
            ico.style.background=c.bg;
            ico.innerHTML='<svg width="20" height="20" fill="none" stroke="'+c.color+'" stroke-width="2" viewBox="0 0 24 24">'+c.icon+'</svg>';
            title.textContent=c.title;msg.textContent='"'+name+'" : '+c.msg;
            btnOk.textContent=c.btn;btnOk.style.background=c.color;
            modal.classList.add('open');document.body.style.overflow='hidden';
        }
        function close(){pending=null;modal.classList.remove('open');document.body.style.overflow='';}
        btnNo.onclick=close;btnOk.onclick=function(){if(pending)pending.submit();};
        modal.onclick=function(e){if(e.target===modal)close();};
        document.addEventListener('keydown',function(e){if(e.key==='Escape')close();});
        document.querySelectorAll('.js-user-form').forEach(function(f){
            f.onsubmit=function(e){e.preventDefault();open(f,f.dataset.variant,f.dataset.name);};
        });
    })();
    </script>
</x-app-layout>
