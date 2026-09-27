<x-app-layout>
    <x-slot name="header">
        <div class="adm-header">
            <div class="adm-header-left">
                <div class="adm-header-avatar" aria-hidden="true">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div>
                    <h1 class="adm-header-title">Tableau de bord</h1>
                    <p class="adm-header-sub">
                        <span class="adm-live-dot" title="Données en temps réel"></span>
                        Bonjour, <strong>{{ auth()->user()->name }}</strong> · {{ now()->format('d F Y') }}
                    </p>
                </div>
            </div>
            <div class="adm-header-right">
                <button onclick="window.location.reload()" class="adm-btn-ghost" title="Rafraîchir les données">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Rafraîchir
                </button>
                <a href="{{ route('admin.stats.export') }}" class="adm-btn-primary">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Exporter CSV
                </a>
            </div>
        </div>
    </x-slot>

    @php
        $pendingOffers = \App\Models\JobOffer::where('status','pending_validation')->count();
        $openOffers    = \App\Models\JobOffer::where('status','open')->count();
        $pendingReports= \App\Models\Report::count();
    @endphp

    <style>
    /* ══ Design tokens (admin: slate + indigo accent) ════════ */
    :root {
        --adm-slate:    #334155;
        --adm-slate-dk: #1e293b;
        --adm-slate-lt: #f1f5f9;
        --adm-indigo:   #4f46e5;
        --adm-ind-lt:   #eef2ff;
        --adm-ind-mid:  #818cf8;
        --adm-border:   #e2e8f0;
        --adm-border2:  #cbd5e1;
        --adm-card:     #ffffff;
        --adm-surf:     #f8fafc;
        --adm-txt:      #0f172a;
        --adm-muted:    #64748b;
        --adm-r:        14px;
        --adm-r-sm:     9px;
        --adm-sh:       0 1px 3px rgba(15,23,42,.06), 0 1px 2px rgba(15,23,42,.04);
        --adm-sh-md:    0 4px 20px rgba(15,23,42,.08);
        --adm-sh-lg:    0 8px 32px rgba(15,23,42,.12);
        --adm-tr:       .18s cubic-bezier(.4,0,.2,1);
    }

    /* ══ Header slot ═════════════════════════════════════════ */
    .adm-header { display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; }
    .adm-header-left { display:flex; align-items:center; gap:.85rem; }
    .adm-header-avatar {
        width:42px; height:42px; border-radius:12px; flex-shrink:0;
        background:linear-gradient(135deg, var(--adm-slate), var(--adm-indigo));
        color:white; font-size:1.1rem; font-weight:800;
        display:flex; align-items:center; justify-content:center;
        box-shadow:0 4px 12px rgba(79,70,229,.3);
    }
    .adm-header-title { font-size:1.1rem; font-weight:800; color:var(--adm-txt); line-height:1.2; margin:0; }
    .adm-header-sub   { font-size:.75rem; color:var(--adm-muted); margin-top:2px; display:flex; align-items:center; gap:.35rem; }
    .adm-live-dot {
        width:7px; height:7px; border-radius:50%; background:#22c55e; flex-shrink:0;
        box-shadow:0 0 0 2px rgba(34,197,94,.25);
        animation:adm-pulse 2s infinite;
    }
    @keyframes adm-pulse { 0%,100%{ box-shadow:0 0 0 0 rgba(34,197,94,.4); } 70%{ box-shadow:0 0 0 6px rgba(34,197,94,0); } }

    .adm-header-right { display:flex; align-items:center; gap:.65rem; }
    .adm-btn-ghost {
        display:inline-flex; align-items:center; gap:.4rem;
        font-size:.78rem; font-weight:700; color:var(--adm-slate);
        background:white; border:1.5px solid var(--adm-border2);
        padding:.42rem .9rem; border-radius:var(--adm-r-sm);
        cursor:pointer; text-decoration:none;
        transition:all var(--adm-tr);
    }
    .adm-btn-ghost:hover { border-color:var(--adm-slate); background:var(--adm-slate-lt); }
    .adm-btn-primary {
        display:inline-flex; align-items:center; gap:.4rem;
        font-size:.78rem; font-weight:700; color:white;
        background:linear-gradient(135deg,var(--adm-slate),var(--adm-indigo));
        border:none; padding:.45rem 1rem; border-radius:var(--adm-r-sm);
        cursor:pointer; text-decoration:none;
        box-shadow:0 2px 8px rgba(79,70,229,.25);
        transition:all var(--adm-tr);
    }
    .adm-btn-primary:hover { opacity:.9; box-shadow:0 4px 14px rgba(79,70,229,.35); transform:translateY(-1px); }

    /* ══ Page wrapper ════════════════════════════════════════ */
    .adm-page { max-width:1280px; margin:0 auto; padding:1.75rem 1.5rem 3rem; }

    /* ══ Welcome banner ══════════════════════════════════════ */
    .adm-welcome {
        background:linear-gradient(135deg, var(--adm-slate-dk) 0%, var(--adm-slate) 50%, #475569 100%);
        border-radius:var(--adm-r); padding:1.75rem 2rem;
        display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1.25rem;
        margin-bottom:1.5rem; position:relative; overflow:hidden;
    }
    .adm-welcome::before {
        content:''; position:absolute; inset:0;
        background:url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Ccircle cx='40' cy='40' r='30'/%3E%3Ccircle cx='40' cy='40' r='16'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
        pointer-events:none;
    }
    .adm-welcome-left { position:relative; z-index:1; }
    .adm-welcome-greeting { font-size:1.4rem; font-weight:900; color:white; margin:0 0 .3rem; line-height:1.2; }
    .adm-welcome-sub { font-size:.83rem; color:rgba(255,255,255,.65); }
    .adm-welcome-right { display:flex; gap:.75rem; flex-wrap:wrap; position:relative; z-index:1; }
    .adm-welcome-badge {
        background:rgba(255,255,255,.1); border:1px solid rgba(255,255,255,.18);
        border-radius:var(--adm-r-sm); padding:.65rem 1rem;
        text-align:center; backdrop-filter:blur(4px); min-width:90px;
    }
    .adm-welcome-badge-val { font-size:1.4rem; font-weight:900; color:white; line-height:1; }
    .adm-welcome-badge-label { font-size:.65rem; font-weight:700; color:rgba(255,255,255,.6); text-transform:uppercase; letter-spacing:.06em; margin-top:.2rem; }

    @keyframes badgeIn { from{ opacity:0; transform:translateY(8px);} to{ opacity:1; transform:none;} }

    /* ══ Alert bar (pending) ═════════════════════════════════ */
    .adm-alert-bar {
        display:flex; align-items:center; justify-content:space-between; gap:.75rem; flex-wrap:wrap;
        background:#fffbeb; border:1.5px solid #fde68a;
        border-radius:var(--adm-r-sm); padding:.75rem 1.1rem;
        margin-bottom:1.5rem;
    }
    .adm-alert-left { display:flex; align-items:center; gap:.6rem; }
    .adm-alert-dot { width:8px; height:8px; border-radius:50%; background:#f59e0b; flex-shrink:0; animation:adm-pulse-amber 2s infinite; }
    @keyframes adm-pulse-amber { 0%,100%{ box-shadow:0 0 0 0 rgba(245,158,11,.4); } 70%{ box-shadow:0 0 0 6px rgba(245,158,11,0); } }
    .adm-alert-text { font-size:.82rem; font-weight:700; color:#92400e; }
    .adm-alert-sub  { font-size:.73rem; color:#b45309; }
    .adm-alert-link {
        display:inline-flex; align-items:center; gap:.35rem;
        font-size:.78rem; font-weight:700; color:#92400e;
        background:white; border:1.5px solid #fde68a;
        padding:.35rem .85rem; border-radius:7px; text-decoration:none; white-space:nowrap;
        transition:all var(--adm-tr);
    }
    .adm-alert-link:hover { background:#fef3c7; border-color:#f59e0b; }

    /* ══ KPI cards grid ══════════════════════════════════════ */
    .adm-kpi-grid {
        display:grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap:1rem;
        margin-bottom:1.5rem;
    }
    @media(max-width:700px) { .adm-kpi-grid{ grid-template-columns:1fr 1fr; } }
    @media(max-width:480px) { .adm-kpi-grid{ grid-template-columns:1fr; } }

    .adm-kpi {
        background:var(--adm-card); border:1px solid var(--adm-border);
        border-radius:var(--adm-r); padding:1.1rem 1.25rem;
        box-shadow:var(--adm-sh); position:relative; overflow:hidden;
        cursor:default;
        transition:transform var(--adm-tr), box-shadow var(--adm-tr);
    }
    .adm-kpi:hover { transform:translateY(-3px); box-shadow:var(--adm-sh-md); }
    .adm-kpi::after {
        content:''; position:absolute; top:0; left:0; right:0; height:3px;
        border-radius:var(--adm-r) var(--adm-r) 0 0;
    }
    .adm-kpi-slate  ::after,.adm-kpi.adm-kpi-slate::after  { background:linear-gradient(90deg,#334155,#475569); }
    .adm-kpi-indigo ::after,.adm-kpi.adm-kpi-indigo::after { background:linear-gradient(90deg,#4f46e5,#818cf8); }
    .adm-kpi-blue   ::after,.adm-kpi.adm-kpi-blue::after   { background:linear-gradient(90deg,#2563eb,#60a5fa); }
    .adm-kpi-green  ::after,.adm-kpi.adm-kpi-green::after  { background:linear-gradient(90deg,#16a34a,#4ade80); }
    .adm-kpi-purple ::after,.adm-kpi.adm-kpi-purple::after { background:linear-gradient(90deg,#9333ea,#c084fc); }
    .adm-kpi-red    ::after,.adm-kpi.adm-kpi-red::after    { background:linear-gradient(90deg,#dc2626,#f87171); }
    .adm-kpi-amber  ::after,.adm-kpi.adm-kpi-amber::after  { background:linear-gradient(90deg,#d97706,#fbbf24); }
    .adm-kpi-teal   ::after,.adm-kpi.adm-kpi-teal::after   { background:linear-gradient(90deg,#0d9488,#14b8a6); }

    .adm-kpi-top { display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:.85rem; }
    .adm-kpi-icon {
        width:40px; height:40px; border-radius:10px; flex-shrink:0;
        display:flex; align-items:center; justify-content:center;
    }
    .adm-kpi-slate  .adm-kpi-icon { background:#f1f5f9; color:#334155; }
    .adm-kpi-indigo .adm-kpi-icon { background:#eef2ff; color:#4f46e5; }
    .adm-kpi-blue   .adm-kpi-icon { background:#eff6ff; color:#2563eb; }
    .adm-kpi-green  .adm-kpi-icon { background:#f0fdf4; color:#16a34a; }
    .adm-kpi-purple .adm-kpi-icon { background:#faf5ff; color:#9333ea; }
    .adm-kpi-red    .adm-kpi-icon { background:#fef2f2; color:#dc2626; }
    .adm-kpi-amber  .adm-kpi-icon { background:#fffbeb; color:#d97706; }
    .adm-kpi-teal   .adm-kpi-icon { background:#f0fdfa; color:#0d9488; }

    .adm-kpi-label { font-size:.68rem; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:var(--adm-muted); }
    .adm-kpi-val   { font-size:2rem; font-weight:900; color:var(--adm-txt); line-height:1; margin:.15rem 0 0; }
    .adm-kpi-foot  { display:flex; align-items:center; justify-content:space-between; }
    .adm-kpi-sub   { font-size:.7rem; color:var(--adm-muted); }
    .adm-kpi-bar   { height:3px; border-radius:99px; background:#e2e8f0; overflow:hidden; margin:.6rem 0 .5rem; }
    .adm-kpi-bar-fill { height:100%; border-radius:99px; transition:width 1.2s cubic-bezier(.4,0,.2,1); }
    .adm-kpi-slate  .adm-kpi-bar-fill { background:linear-gradient(90deg,#334155,#475569); }
    .adm-kpi-indigo .adm-kpi-bar-fill { background:linear-gradient(90deg,#4f46e5,#818cf8); }
    .adm-kpi-blue   .adm-kpi-bar-fill { background:linear-gradient(90deg,#2563eb,#60a5fa); }
    .adm-kpi-green  .adm-kpi-bar-fill { background:linear-gradient(90deg,#16a34a,#4ade80); }
    .adm-kpi-purple .adm-kpi-bar-fill { background:linear-gradient(90deg,#9333ea,#c084fc); }
    .adm-kpi-red    .adm-kpi-bar-fill { background:linear-gradient(90deg,#dc2626,#f87171); }
    .adm-kpi-amber  .adm-kpi-bar-fill { background:linear-gradient(90deg,#d97706,#fbbf24); }
    .adm-kpi-teal   .adm-kpi-bar-fill { background:linear-gradient(90deg,#0d9488,#14b8a6); }

    /* ══ Two-column analytics ════════════════════════════════ */
    .adm-analytics { display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; margin-bottom:1.5rem; }
    @media(max-width:800px){ .adm-analytics{ grid-template-columns:1fr; } }

    /* ══ Card base ═══════════════════════════════════════════ */
    .adm-card {
        background:var(--adm-card); border:1px solid var(--adm-border);
        border-radius:var(--adm-r); box-shadow:var(--adm-sh); overflow:hidden;
    }
    .adm-card-head {
        display:flex; align-items:center; gap:.6rem; justify-content:space-between;
        padding:.9rem 1.25rem; border-bottom:1px solid #f1f5f9;
        background:var(--adm-surf);
    }
    .adm-card-head-left { display:flex; align-items:center; gap:.6rem; }
    .adm-card-head-icon {
        width:28px; height:28px; border-radius:8px; flex-shrink:0;
        display:flex; align-items:center; justify-content:center;
    }
    .adm-card-title { font-size:.88rem; font-weight:800; color:var(--adm-txt); }
    .adm-card-badge {
        font-size:.65rem; font-weight:700; padding:.18rem .55rem;
        border-radius:99px;
    }
    .adm-card-body { padding:1.25rem; }

    /* ══ Horizontal bar chart rows ═══════════════════════════ */
    .adm-bar-list { display:flex; flex-direction:column; gap:.75rem; }
    .adm-bar-row  { display:grid; gap:.35rem; }
    .adm-bar-meta { display:flex; align-items:center; justify-content:space-between; }
    .adm-bar-name  { font-size:.78rem; font-weight:700; color:var(--adm-txt); overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:60%; }
    .adm-bar-right { display:flex; align-items:center; gap:.5rem; flex-shrink:0; }
    .adm-bar-count { font-size:.78rem; font-weight:800; }
    .adm-bar-pct   { font-size:.68rem; font-weight:600; color:var(--adm-muted); }
    .adm-bar-track { height:6px; border-radius:99px; background:#e2e8f0; overflow:hidden; }
    .adm-bar-fill  { height:100%; border-radius:99px; transition:width 1.1s cubic-bezier(.4,0,.2,1); width:0; }

    /* ══ Quick actions ═══════════════════════════════════════ */
    .adm-actions-grid { display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:1rem; }
    @media(max-width:900px){ .adm-actions-grid{ grid-template-columns:1fr 1fr; } }
    @media(max-width:480px){ .adm-actions-grid{ grid-template-columns:1fr; } }

    .adm-action-card {
        background:var(--adm-card); border:1.5px solid var(--adm-border);
        border-radius:var(--adm-r); padding:1.25rem;
        display:flex; flex-direction:column; gap:.75rem;
        text-decoration:none; cursor:pointer;
        transition:all var(--adm-tr); position:relative; overflow:hidden;
    }
    .adm-action-card:hover { transform:translateY(-3px); box-shadow:var(--adm-sh-md); }
    .adm-action-card::after { content:''; position:absolute; top:0; left:0; right:0; height:3px; border-radius:var(--adm-r) var(--adm-r) 0 0; }

    .adm-ac-slate::after  { background:linear-gradient(90deg,#334155,#64748b); }
    .adm-ac-indigo::after { background:linear-gradient(90deg,#4f46e5,#818cf8); }
    .adm-ac-amber::after  { background:linear-gradient(90deg,#d97706,#fbbf24); }
    .adm-ac-red::after    { background:linear-gradient(90deg,#dc2626,#f87171); }
    .adm-ac-green::after  { background:linear-gradient(90deg,#16a34a,#4ade80); }
    .adm-ac-purple::after { background:linear-gradient(90deg,#9333ea,#c084fc); }
    .adm-ac-teal::after   { background:linear-gradient(90deg,#0d9488,#14b8a6); }

    .adm-action-icon {
        width:44px; height:44px; border-radius:12px;
        display:flex; align-items:center; justify-content:center;
    }
    .adm-ac-slate  .adm-action-icon { background:#f1f5f9; color:#334155; }
    .adm-ac-indigo .adm-action-icon { background:#eef2ff; color:#4f46e5; }
    .adm-ac-amber  .adm-action-icon { background:#fffbeb; color:#d97706; }
    .adm-ac-red    .adm-action-icon { background:#fef2f2; color:#dc2626; }
    .adm-ac-green  .adm-action-icon { background:#f0fdf4; color:#16a34a; }
    .adm-ac-purple .adm-action-icon { background:#faf5ff; color:#9333ea; }
    .adm-ac-teal   .adm-action-icon { background:#f0fdfa; color:#0d9488; }

    .adm-action-title { font-size:.88rem; font-weight:800; color:var(--adm-txt); }
    .adm-action-desc  { font-size:.73rem; color:var(--adm-muted); line-height:1.5; }
    .adm-action-arrow {
        display:inline-flex; align-items:center; gap:.3rem;
        font-size:.72rem; font-weight:700; margin-top:auto;
        transition:gap var(--adm-tr);
    }
    .adm-action-card:hover .adm-action-arrow { gap:.55rem; }

    .adm-ac-slate  .adm-action-arrow { color:#334155; }
    .adm-ac-indigo .adm-action-arrow { color:#4f46e5; }
    .adm-ac-amber  .adm-action-arrow { color:#d97706; }
    .adm-ac-red    .adm-action-arrow { color:#dc2626; }
    .adm-ac-green  .adm-action-arrow { color:#16a34a; }
    .adm-ac-purple .adm-action-arrow { color:#9333ea; }
    .adm-ac-teal   .adm-action-arrow { color:#0d9488; }

    /* ══ Fade-up animation ═══════════════════════════════════ */
    @keyframes adm-fadeup { from{opacity:0;transform:translateY(16px);} to{opacity:1;transform:none;} }
    .adm-anim   { animation:adm-fadeup .35s ease both; }
    .adm-d1 { animation-delay:.06s; }
    .adm-d2 { animation-delay:.12s; }
    .adm-d3 { animation-delay:.18s; }
    .adm-d4 { animation-delay:.24s; }
    </style>

    <div class="adm-page">

        {{-- ── Welcome banner ── --}}
        <div class="adm-welcome adm-anim">
            <div class="adm-welcome-left">
                <p class="adm-welcome-greeting">Bonjour, {{ auth()->user()->name }} 👋</p>
                <p class="adm-welcome-sub">
                    @php
                        $h = now()->hour;
                        $salut = $h < 12 ? 'Bonjour' : ($h < 18 ? 'Bon après-midi' : 'Bonsoir');
                        $moisFr = ['janvier','février','mars','avril','mai','juin','juillet','août','septembre','octobre','novembre','décembre'];
                        $joursFr = ['dimanche','lundi','mardi','mercredi','jeudi','vendredi','samedi'];
                        $dateFr = $joursFr[now()->dayOfWeek] . ' ' . now()->day . ' ' . $moisFr[now()->month - 1] . ' ' . now()->year;
                    @endphp
                    {{ $salut }} — voici un aperçu de la plateforme en ce {{ $dateFr }}.
                </p>
            </div>
            <div class="adm-welcome-right">
                <div class="adm-welcome-badge" style="animation:badgeIn .4s .1s ease both;">
                    <div class="adm-welcome-badge-val">{{ $stats['offers_count'] }}</div>
                    <div class="adm-welcome-badge-label">Offres actives</div>
                </div>
                <div class="adm-welcome-badge" style="animation:badgeIn .4s .2s ease both;">
                    <div class="adm-welcome-badge-val">{{ $stats['applications_count'] }}</div>
                    <div class="adm-welcome-badge-label">Candidatures</div>
                </div>
                @if($pendingOffers > 0)
                <div class="adm-welcome-badge" style="background:rgba(245,158,11,.2); border-color:rgba(245,158,11,.4); animation:badgeIn .4s .3s ease both;">
                    <div class="adm-welcome-badge-val" style="color:#fcd34d;">{{ $pendingOffers }}</div>
                    <div class="adm-welcome-badge-label" style="color:rgba(252,211,77,.7);">En attente</div>
                </div>
                @endif
            </div>
        </div>

        {{-- ── Alert bar if there are pending offers ── --}}
        @if($pendingOffers > 0)
        <div class="adm-alert-bar adm-anim adm-d1">
            <div class="adm-alert-left">
                <span class="adm-alert-dot"></span>
                <div>
                    <div class="adm-alert-text">
                        {{ $pendingOffers }} offre{{ $pendingOffers > 1 ? 's' : '' }} en attente de modération
                    </div>
                    <div class="adm-alert-sub">Ces offres ne sont pas encore visibles par les candidats.</div>
                </div>
            </div>
            <a href="{{ route('admin.offers.moderation') }}" class="adm-alert-link">
                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                Modérer maintenant
            </a>
        </div>
        @endif

        {{-- ── KPI cards ── --}}
        <div class="adm-kpi-grid adm-anim adm-d1">

            @php
                $total = max($stats['users_count'], 1);
                $kpis = [
                    ['label'=>'Utilisateurs',  'val'=>$stats['users_count'],        'sub'=>'Total comptes inscrits',  'cls'=>'adm-kpi-slate',  'pct'=>100,
                     'icon'=>'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
                    ['label'=>'Candidats',     'val'=>$stats['candidates_count'],   'sub'=>'Profils candidats actifs','cls'=>'adm-kpi-indigo', 'pct'=>round($stats['candidates_count']/$total*100),
                     'icon'=>'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                    ['label'=>'Entreprises',   'val'=>$stats['companies_count'],    'sub'=>'Sociétés inscrites',       'cls'=>'adm-kpi-blue',   'pct'=>round($stats['companies_count']/$total*100),
                     'icon'=>'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                    ['label'=>'Offres',        'val'=>$stats['offers_count'],       'sub'=>'Total offres publiées',    'cls'=>'adm-kpi-green',  'pct'=>min(100, $stats['offers_count']),
                     'icon'=>'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                    ['label'=>'Candidatures', 'val'=>$stats['applications_count'],  'sub'=>'Total candidatures',       'cls'=>'adm-kpi-purple', 'pct'=>min(100, $stats['applications_count']),
                     'icon'=>'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                    ['label'=>'Signalements',  'val'=>$stats['reports_count'],       'sub'=>'Signalements reçus',       'cls'=>'adm-kpi-red',    'pct'=>min(100, $stats['reports_count']),
                     'icon'=>'M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9'],
                    ['label'=>'Offres en attente',    'val'=>$stats['pending_offers_count'],'sub'=>'Offres à modérer',        'cls'=>'adm-kpi-amber',  'pct'=>$stats['offers_count'] > 0 ? round($stats['pending_offers_count']/$stats['offers_count']*100) : 0,
                     'icon'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['label'=>'Taux conv.',    'val'=>$stats['conversion_rate'],     'sub'=>'Candidatures par offre',   'cls'=>'adm-kpi-teal',   'pct'=>$stats['conversion_rate'],
                     'icon'=>'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                ];
            @endphp

            @foreach($kpis as $i => $kpi)
            <div class="adm-kpi {{ $kpi['cls'] }}" data-pct="{{ $kpi['pct'] }}" style="animation:adm-fadeup .35s {{ $i*0.07 }}s ease both;">
                <div class="adm-kpi-top">
                    <div>
                        <div class="adm-kpi-label">{{ $kpi['label'] }}</div>
                        <div class="adm-kpi-val" data-count="{{ $kpi['val'] }}">0</div>
                    </div>
                    <div class="adm-kpi-icon">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $kpi['icon'] }}"/>
                        </svg>
                    </div>
                </div>
                <div class="adm-kpi-bar"><div class="adm-kpi-bar-fill" style="width:0%"></div></div>
                <div class="adm-kpi-foot">
                    <span class="adm-kpi-sub">{{ $kpi['sub'] }}</span>
                </div>
            </div>
            @endforeach
        </div>

        {{-- ══════════════════════════════════════════════════════════════
             CHARTS SECTION
        ═══════════════════════════════════════════════════════════════ --}}

        {{-- ── Row 1: Line chart (full width) ── --}}
        <div class="adm-card adm-anim adm-d2" style="margin-bottom:1.25rem;">
            <div class="adm-card-head">
                <div class="adm-card-head-left">
                    <div class="adm-card-head-icon" style="background:#eff6ff; color:#2563eb;">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <span class="adm-card-title">Évolution sur 6 mois</span>
                </div>
                <div style="display:flex; align-items:center; gap:.75rem; flex-wrap:wrap;">
                    <span style="display:inline-flex; align-items:center; gap:.3rem; font-size:.72rem; font-weight:600; color:#4f46e5;">
                        <span style="width:10px; height:3px; background:#4f46e5; border-radius:99px; display:inline-block;"></span>Utilisateurs
                    </span>
                    <span style="display:inline-flex; align-items:center; gap:.3rem; font-size:.72rem; font-weight:600; color:#16a34a;">
                        <span style="width:10px; height:3px; background:#16a34a; border-radius:99px; display:inline-block;"></span>Offres
                    </span>
                    <span style="display:inline-flex; align-items:center; gap:.3rem; font-size:.72rem; font-weight:600; color:#9333ea;">
                        <span style="width:10px; height:3px; background:#9333ea; border-radius:99px; display:inline-block;"></span>Candidatures
                    </span>
                </div>
            </div>
            <div class="adm-card-body" style="padding:1.25rem 1.5rem;">
                <div style="position:relative; height:240px;">
                    <canvas id="chartLine"></canvas>
                </div>
            </div>
        </div>

        {{-- ── Row 2: Donut (roles) + Donut (applications) + Bar (categories) ── --}}
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; margin-bottom:1.25rem;" class="adm-charts-row adm-anim adm-d2">

            {{-- Donut: Répartition utilisateurs --}}
            <div class="adm-card">
                <div class="adm-card-head">
                    <div class="adm-card-head-left">
                        <div class="adm-card-head-icon" style="background:#eef2ff; color:#4f46e5;">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <span class="adm-card-title">Répartition utilisateurs</span>
                    </div>
                </div>
                <div class="adm-card-body" style="display:flex; flex-direction:column; align-items:center; gap:1rem;">
                    <div style="position:relative; height:170px; width:170px;">
                        <canvas id="chartRoles"></canvas>
                        <div style="position:absolute; inset:0; display:flex; flex-direction:column; align-items:center; justify-content:center; pointer-events:none;">
                            <span style="font-size:1.6rem; font-weight:900; color:#0f172a;">{{ $stats['users_count'] }}</span>
                            <span style="font-size:.65rem; font-weight:600; color:#64748b; text-transform:uppercase; letter-spacing:.06em;">total</span>
                        </div>
                    </div>
                    <div style="display:flex; flex-direction:column; gap:.4rem; width:100%;">
                        @php $roleColors = ['#4f46e5','#059669','#334155']; @endphp
                        @foreach($chartRoleLabels as $i => $lbl)
                        <div style="display:flex; align-items:center; justify-content:space-between;">
                            <span style="display:flex; align-items:center; gap:.4rem; font-size:.75rem; font-weight:600; color:#334155;">
                                <span style="width:9px; height:9px; border-radius:3px; background:{{ $roleColors[$i] }}; flex-shrink:0;"></span>
                                {{ $lbl }}
                            </span>
                            <span style="font-size:.75rem; font-weight:800; color:{{ $roleColors[$i] }};">{{ $chartRoleData[$i] }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Donut: Candidatures par statut --}}
            <div class="adm-card">
                <div class="adm-card-head">
                    <div class="adm-card-head-left">
                        <div class="adm-card-head-icon" style="background:#fef3c7; color:#d97706;">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <span class="adm-card-title">Statuts candidatures</span>
                    </div>
                </div>
                <div class="adm-card-body" style="display:flex; flex-direction:column; align-items:center; gap:1rem;">
                    <div style="position:relative; height:170px; width:170px;">
                        <canvas id="chartAppStatus"></canvas>
                        <div style="position:absolute; inset:0; display:flex; flex-direction:column; align-items:center; justify-content:center; pointer-events:none;">
                            <span style="font-size:1.6rem; font-weight:900; color:#0f172a;">{{ $stats['applications_count'] }}</span>
                            <span style="font-size:.65rem; font-weight:600; color:#64748b; text-transform:uppercase; letter-spacing:.06em;">total</span>
                        </div>
                    </div>
                    <div style="display:flex; flex-direction:column; gap:.4rem; width:100%;">
                        @php $appColors = ['#f59e0b','#10b981','#ef4444','#6b7280']; @endphp
                        @foreach($chartAppLabels as $i => $lbl)
                        <div style="display:flex; align-items:center; justify-content:space-between;">
                            <span style="display:flex; align-items:center; gap:.4rem; font-size:.75rem; font-weight:600; color:#334155;">
                                <span style="width:9px; height:9px; border-radius:3px; background:{{ $appColors[$i % count($appColors)] }}; flex-shrink:0;"></span>
                                {{ $lbl }}
                            </span>
                            <span style="font-size:.75rem; font-weight:800; color:{{ $appColors[$i % count($appColors)] }};">{{ $chartAppData[$i] }}</span>
                        </div>
                        @endforeach
                        @if(empty($chartAppLabels))
                            <p style="font-size:.78rem; color:#64748b; text-align:center;">Aucune candidature</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <style>
        @media(max-width:900px) { .adm-charts-row { grid-template-columns:1fr 1fr !important; } }
        @media(max-width:580px) { .adm-charts-row { grid-template-columns:1fr !important; } }
        </style>

        {{-- ── Analytics: City + Category + Applications ── --}}
        <div class="adm-analytics adm-anim adm-d2">

            {{-- Offres par ville --}}
            <div class="adm-card">
                <div class="adm-card-head">
                    <div class="adm-card-head-left">
                        <div class="adm-card-head-icon" style="background:#eef2ff; color:#4f46e5;">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                        </div>
                        <span class="adm-card-title">Offres par ville</span>
                    </div>
                    <span class="adm-card-badge" id="cityBadge" style="background:#eef2ff; color:#4f46e5;">
                        Top 5 / {{ count($chartCityLabels) }} villes
                    </span>
                </div>
                <div class="adm-card-body" style="padding:1rem 1.25rem;">
                    <div id="chartCitiesWrap" style="position:relative; height:175px;">
                        <canvas id="chartCities"></canvas>
                    </div>
                    @if($statsByCity->isNotEmpty())
                    <div style="margin-top:.75rem; padding-top:.75rem; border-top:1px solid #f1f5f9;">
                        <button id="cityLoadMore" onclick="loadMoreCities()"
                            style="width:100%; padding:.45rem; border:1.5px dashed #c7d2fe; border-radius:8px; background:#f8faff; color:#4f46e5; font-size:.75rem; font-weight:700; cursor:pointer; transition:all .15s;"
                            onmouseover="this.style.background='#eef2ff'" onmouseout="this.style.background='#f8faff'">
                            ＋ Charger 5 de plus
                        </button>
                        <div style="margin-top:.6rem; display:flex; justify-content:space-between; align-items:center;">
                            <span style="font-size:.7rem; color:var(--adm-muted);">
                                📍 Ville phare : <strong style="color:#4f46e5;">{{ $statsByCity->sortByDesc('total')->first()->location ?? 'N/A' }}</strong>
                            </span>
                            <span style="font-size:.7rem; color:var(--adm-muted);">{{ $statsByCity->sum('total') }} offres au total</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Bar: Top catégories --}}
            <div class="adm-card">
                <div class="adm-card-head">
                    <div class="adm-card-head-left">
                        <div class="adm-card-head-icon" style="background:#f0fdf4; color:#16a34a;">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <span class="adm-card-title">Top catégories</span>
                    </div>
                    <span class="adm-card-badge" id="catBadge" style="background:#f0fdf4; color:#16a34a;">
                        Top 5 / {{ count($chartCatLabels) }}
                    </span>
                </div>
                <div class="adm-card-body" style="padding:1rem 1.25rem;">
                    <div id="chartCategoriesWrap" style="position:relative; height:175px;">
                        <canvas id="chartCategories"></canvas>
                    </div>
                    <div style="margin-top:.75rem;">
                        <button id="catLoadMore" onclick="loadMoreCats()"
                            style="width:100%; padding:.45rem; border:1.5px dashed #bbf7d0; border-radius:8px; background:#f8fff8; color:#16a34a; font-size:.75rem; font-weight:700; cursor:pointer; transition:all .15s;"
                            onmouseover="this.style.background='#f0fdf4'" onmouseout="this.style.background='#f8fff8'">
                            ＋ Charger 5 de plus
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Analytics: Applications Status + Recent Activity ── --}}
        <div class="adm-analytics adm-anim adm-d2">

            {{-- Candidatures par statut --}}
            <div class="adm-card">
                <div class="adm-card-head">
                    <div class="adm-card-head-left">
                        <div class="adm-card-head-icon" style="background:#fef3c7; color:#d97706;">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <span class="adm-card-title">Candidatures par statut</span>
                    </div>
                    <span class="adm-card-badge" style="background:#fef3c7; color:#d97706;">
                        {{ $applicationsByStatus->count() }} statuts
                    </span>
                </div>
                <div class="adm-card-body">
                    <div class="adm-bar-list">
                        @php $maxApp = $applicationsByStatus->max('total') ?: 1; @endphp
                        @foreach($applicationsByStatus->sortByDesc('total') as $idx => $appStat)
                            @php
                                $pct  = round($appStat->total / $maxApp * 100);
                                $gpct = $stats['applications_count'] > 0 ? round($appStat->total / $stats['applications_count'] * 100) : 0;
                                $statusColors = [
                                    'pending' => '#f59e0b',
                                    'accepted' => '#10b981',
                                    'rejected' => '#ef4444',
                                    'withdrawn' => '#6b7280'
                                ];
                                $statusLabels = [
                                    'pending' => 'En attente',
                                    'accepted' => 'Acceptée',
                                    'rejected' => 'Refusée',
                                    'withdrawn' => 'Retirée'
                                ];
                                $c = $statusColors[$appStat->status] ?? '#6b7280';
                                $label = $statusLabels[$appStat->status] ?? ucfirst($appStat->status);
                            @endphp
                            <div class="adm-bar-row">
                                <div class="adm-bar-meta">
                                    <span class="adm-bar-name">{{ $label }}</span>
                                    <div class="adm-bar-right">
                                        <span class="adm-bar-count" style="color:{{ $c }};">{{ $appStat->total }}</span>
                                    </div>
                                </div>
                                <div class="adm-bar-track">
                                    <div class="adm-bar-fill" data-width="{{ $pct }}" style="background:{{ $c }};"></div>
                                </div>
                            </div>
                        @endforeach

                        @if($applicationsByStatus->isEmpty())
                            <p style="font-size:.8rem; color:var(--adm-muted); text-align:center; padding:1rem 0;">Aucune candidature.</p>
                        @endif
                    </div>

                    @if($applicationsByStatus->isNotEmpty())
                    <div style="margin-top:1rem; padding-top:.75rem; border-top:1px solid #f1f5f9; display:flex; justify-content:space-between; align-items:center;">
                        <span style="font-size:.7rem; color:var(--adm-muted);">
                            📊 Statut dominant : <strong style="color:#d97706;">{{ $statusLabels[$applicationsByStatus->sortByDesc('total')->first()->status] ?? 'N/A' }}</strong>
                        </span>
                        <span style="font-size:.7rem; color:var(--adm-muted);">{{ $applicationsByStatus->sum('total') }} candidatures au total</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Activité récente --}}
            <div class="adm-card">
                <div class="adm-card-head">
                    <div class="adm-card-head-left">
                        <div class="adm-card-head-icon" style="background:#f0fdfa; color:#0d9488;">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="adm-card-title">Activité récente (7 jours)</span>
                    </div>
                    <span class="adm-card-badge" style="background:#f0fdfa; color:#0d9488;">
                        Cette semaine
                    </span>
                </div>
                <div class="adm-card-body">
                    <div style="display:flex; flex-direction:column; gap:1rem;">
                        <div style="display:flex; align-items:center; justify-content:space-between; padding:.75rem; background:#f8fafc; border-radius:8px;">
                            <div style="display:flex; align-items:center; gap:.6rem;">
                                <div style="width:32px; height:32px; border-radius:8px; background:#eef2ff; color:#4f46e5; display:flex; align-items:center; justify-content:center;">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div style="font-size:.82rem; font-weight:700; color:#0f172a;">Nouveaux utilisateurs</div>
                                    <div style="font-size:.7rem; color:#64748b;">Inscriptions cette semaine</div>
                                </div>
                            </div>
                            <div style="text-align:right;">
                                <div style="font-size:1.2rem; font-weight:900; color:#4f46e5;">{{ $recentUsers }}</div>
                            </div>
                        </div>

                        <div style="display:flex; align-items:center; justify-content:space-between; padding:.75rem; background:#f8fafc; border-radius:8px;">
                            <div style="display:flex; align-items:center; gap:.6rem;">
                                <div style="width:32px; height:32px; border-radius:8px; background:#f0fdf4; color:#16a34a; display:flex; align-items:center; justify-content:center;">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div style="font-size:.82rem; font-weight:700; color:#0f172a;">Nouvelles offres</div>
                                    <div style="font-size:.7rem; color:#64748b;">Offres publiées cette semaine</div>
                                </div>
                            </div>
                            <div style="text-align:right;">
                                <div style="font-size:1.2rem; font-weight:900; color:#16a34a;">{{ $recentOffers }}</div>
                            </div>
                        </div>

                        <div style="display:flex; align-items:center; justify-content:space-between; padding:.75rem; background:#f8fafc; border-radius:8px;">
                            <div style="display:flex; align-items:center; gap:.6rem;">
                                <div style="width:32px; height:32px; border-radius:8px; background:#fef3c7; color:#d97706; display:flex; align-items:center; justify-content:center;">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div style="font-size:.82rem; font-weight:700; color:#0f172a;">Nouvelles candidatures</div>
                                    <div style="font-size:.7rem; color:#64748b;">Candidatures cette semaine</div>
                                </div>
                            </div>
                            <div style="text-align:right;">
                                <div style="font-size:1.2rem; font-weight:900; color:#d97706;">{{ $recentApplications }}</div>
                            </div>
                        </div>
                    </div>

                    <div style="margin-top:1rem; padding-top:.75rem; border-top:1px solid #f1f5f9; text-align:center;">
                        <span style="font-size:.7rem; color:var(--adm-muted);">
                            📈 Activité totale cette semaine : <strong style="color:#0d9488;">{{ $recentUsers + $recentOffers + $recentApplications }}</strong> actions
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Quick Actions ── --}}
        <div class="adm-card adm-anim adm-d3">
            <div class="adm-card-head">
                <div class="adm-card-head-left">
                    <div class="adm-card-head-icon" style="background:#f1f5f9; color:#334155;">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <span class="adm-card-title">Actions rapides</span>
                </div>
            </div>
            <div class="adm-card-body">
                <div class="adm-actions-grid">

                    {{-- Modérer les offres --}}
                    <a href="{{ route('admin.offers.moderation') }}" class="adm-action-card adm-ac-amber">
                        <div class="adm-action-icon">
                            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="adm-action-title">
                                Modération offres
                                @if($pendingOffers > 0)
                                    <span style="display:inline-flex; align-items:center; justify-content:center; width:18px; height:18px; border-radius:50%; background:#d97706; color:white; font-size:.65rem; font-weight:900; margin-left:.35rem; vertical-align:middle;">{{ $pendingOffers }}</span>
                                @endif
                            </div>
                            <div class="adm-action-desc">Valider ou refuser les offres soumises par les entreprises.</div>
                        </div>
                        <div class="adm-action-arrow">
                            Accéder <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </a>

                    {{-- Gérer utilisateurs --}}
                    <a href="{{ route('admin.users.moderation') }}" class="adm-action-card adm-ac-indigo">
                        <div class="adm-action-icon">
                            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="adm-action-title">Gérer utilisateurs</div>
                            <div class="adm-action-desc">Valider, bloquer ou consulter les profils inscrits sur la plateforme.</div>
                        </div>
                        <div class="adm-action-arrow">
                            Accéder <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </a>

                    {{-- Catégories --}}
                    <a href="{{ route('admin.categories.index') }}" class="adm-action-card adm-ac-green">
                        <div class="adm-action-icon">
                            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l5 5a2 2 0 01.586 1.414V19a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="adm-action-title">Catégories</div>
                            <div class="adm-action-desc">Ajouter, modifier ou supprimer les catégories d'offres d'emploi.</div>
                        </div>
                        <div class="adm-action-arrow">
                            Accéder <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </a>

                    {{-- Signalements --}}
                    <a href="{{ route('admin.reports.index') }}" class="adm-action-card adm-ac-red">
                        <div class="adm-action-icon">
                            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                            </svg>
                        </div>
                        <div>
                            <div class="adm-action-title">
                                Signalements
                                @if($pendingReports > 0)
                                    <span style="display:inline-flex; align-items:center; justify-content:center; width:18px; height:18px; border-radius:50%; background:#dc2626; color:white; font-size:.65rem; font-weight:900; margin-left:.35rem; vertical-align:middle;">{{ $pendingReports }}</span>
                                @endif
                            </div>
                            <div class="adm-action-desc">Consulter et traiter les signalements soumis par les candidats.</div>
                        </div>
                        <div class="adm-action-arrow">
                            Accéder <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </a>

                </div>
            </div>
        </div>

        {{-- ── Export footer ── --}}
        <div class="adm-anim adm-d4" style="margin-top:1.25rem; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:.75rem; padding:.85rem 1.1rem; background:var(--adm-surf); border:1px solid var(--adm-border); border-radius:var(--adm-r-sm);">
            <div style="display:flex; align-items:center; gap:.5rem; font-size:.75rem; color:var(--adm-muted);">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#16a34a;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Données actualisées en temps réel · Dernière mise à jour : <strong>{{ now()->format('H:i') }}</strong>
            </div>
            <a href="{{ route('admin.stats.export') }}" style="display:inline-flex; align-items:center; gap:.4rem; font-size:.75rem; font-weight:700; color:#16a34a; text-decoration:none; padding:.35rem .85rem; border-radius:7px; border:1.5px solid #bbf7d0; background:#f0fdf4; transition:all .18s;">
                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Exporter CSV
            </a>
        </div>

    </div>{{-- /.adm-page --}}

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
/* ── PHP data passed to JS ── */
const _months   = @json($chartMonths);
const _users    = @json($chartUsers);
const _offers   = @json($chartOffers);
const _apps     = @json($chartApps);
const _roleLbls = @json($chartRoleLabels);
const _roleData = @json($chartRoleData);
const _appLbls  = @json($chartAppLabels);
const _appData  = @json($chartAppData);
const _catLbls  = @json($chartCatLabels);
const _catData  = @json($chartCatData);
const _cityLbls = @json($chartCityLabels);
const _cityData = @json($chartCityData);

/* ── Shared helpers ── */
const font = { family: "'Plus Jakarta Sans', sans-serif" };

function gradientLine(ctx, color) {
    const g = ctx.createLinearGradient(0, 0, 0, 240);
    g.addColorStop(0, color.replace(')', ',.18)').replace('rgb', 'rgba'));
    g.addColorStop(1, color.replace(')', ',0)').replace('rgb', 'rgba'));
    return g;
}

Chart.defaults.font.family = font.family;
Chart.defaults.plugins.legend.display = false;

/* ─────────────────── Shared bar chart factory (global) ─────────────────── */
const STEP = 5;

const barChartOptions = (tooltipTitleFn) => ({
    responsive: true,
    maintainAspectRatio: false,
    indexAxis: 'y',
    plugins: {
        legend: { display: false },
        tooltip: {
            backgroundColor: '#1e293b', titleColor: '#f1f5f9',
            bodyColor: '#cbd5e1', padding: 9, cornerRadius: 8,
            bodyFont: { size: 12, weight: '600' },
            callbacks: {
                title: tooltipTitleFn,
                label: (item) => '  Offres : ' + item.raw,
            },
        },
    },
    scales: {
        x: {
            beginAtZero: true,
            grid: { color: '#f1f5f9' },
            ticks: { color: '#94a3b8', font: { size: 10 }, precision: 0 },
            border: { display: false },
        },
        y: {
            grid: { display: false },
            ticks: {
                color: '#334155', font: { size: 11, weight: '600' },
                callback: function(v) {
                    const lbl = this.getLabelForValue(v);
                    return lbl && lbl.length > 20 ? lbl.substring(0, 19) + '…' : lbl;
                },
            },
            border: { display: false },
        },
    },
});

function makeBarDataset(lbls, data, colors) {
    return {
        labels: lbls.length ? lbls : ['Aucune'],
        datasets: [{
            label: 'Offres',
            data: data.length ? data : [0],
            backgroundColor: lbls.map((_, i) => colors[i % colors.length] + 'cc'),
            hoverBackgroundColor: lbls.map((_, i) => colors[i % colors.length]),
            borderRadius: 6,
            borderSkipped: false,
        }],
    };
}

function setChartHeight(wrapId, count) {
    const wrap = document.getElementById(wrapId);
    if (wrap) wrap.style.height = Math.max(175, count * 35) + 'px';
}

/* ── Categories load-more (global) ── */
let catOffset = STEP;
let chartCat;
const catColors = ['#4f46e5','#6366f1','#059669','#0d9488','#d97706','#9333ea'];

function loadMoreCats() {
    const end  = Math.min(catOffset + STEP, _catLbls.length);
    const lbls = _catLbls.slice(0, end);
    const data = _catData.slice(0, end);
    catOffset  = end;
    setChartHeight('chartCategoriesWrap', lbls.length);
    chartCat.data = makeBarDataset(lbls, data, catColors);
    chartCat.update();
    updateCatBtn();
}

function updateCatBtn() {
    const btn   = document.getElementById('catLoadMore');
    const badge = document.getElementById('catBadge');
    const shown = Math.min(catOffset, _catLbls.length);
    const total = _catLbls.length;
    if (badge) badge.textContent = 'Top ' + shown + ' / ' + total;
    if (!btn) return;
    const rem = total - shown;
    if (rem <= 0) { btn.style.display = 'none'; return; }
    btn.textContent = '＋ Charger ' + Math.min(STEP, rem) + ' de plus (' + rem + ' restante' + (rem > 1 ? 's' : '') + ')';
}

/* ── Cities load-more (global) ── */
let cityOffset = STEP;
let chartCity;
const cityColors = ['#4f46e5','#0d9488','#d97706','#dc2626','#16a34a','#9333ea'];

function loadMoreCities() {
    const end  = Math.min(cityOffset + STEP, _cityLbls.length);
    const lbls = _cityLbls.slice(0, end);
    const data = _cityData.slice(0, end);
    cityOffset = end;
    setChartHeight('chartCitiesWrap', lbls.length);
    chartCity.data = makeBarDataset(lbls, data, cityColors);
    chartCity.update();
    updateCityBtn();
}

function updateCityBtn() {
    const btn   = document.getElementById('cityLoadMore');
    const badge = document.getElementById('cityBadge');
    const shown = Math.min(cityOffset, _cityLbls.length);
    const total = _cityLbls.length;
    if (badge) badge.textContent = 'Top ' + shown + ' / ' + total + ' villes';
    if (!btn) return;
    const rem = total - shown;
    if (rem <= 0) { btn.style.display = 'none'; return; }
    btn.textContent = '＋ Charger ' + Math.min(STEP, rem) + ' de plus (' + rem + ' restante' + (rem > 1 ? 's' : '') + ')';
}

document.addEventListener('DOMContentLoaded', () => {

    /* ─────────────────────── LINE CHART ─────────────────────── */
    (function() {
        const ctx = document.getElementById('chartLine');
        if (!ctx) return;
        const c = ctx.getContext('2d');

        const makeGrad = (hex, alpha1 = .18, alpha2 = 0) => {
            const g = c.createLinearGradient(0, 0, 0, 240);
            g.addColorStop(0,   hex + Math.round(alpha1 * 255).toString(16).padStart(2,'0'));
            g.addColorStop(1,   hex + Math.round(alpha2 * 255).toString(16).padStart(2,'0'));
            return g;
        };

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: _months,
                datasets: [
                    {
                        label: 'Utilisateurs',
                        data: _users,
                        borderColor: '#4f46e5',
                        backgroundColor: makeGrad('#4f46e5'),
                        borderWidth: 2.5,
                        pointBackgroundColor: '#4f46e5',
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        tension: 0.42,
                        fill: true,
                    },
                    {
                        label: 'Offres',
                        data: _offers,
                        borderColor: '#16a34a',
                        backgroundColor: makeGrad('#16a34a'),
                        borderWidth: 2.5,
                        pointBackgroundColor: '#16a34a',
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        tension: 0.42,
                        fill: true,
                    },
                    {
                        label: 'Candidatures',
                        data: _apps,
                        borderColor: '#9333ea',
                        backgroundColor: makeGrad('#9333ea'),
                        borderWidth: 2.5,
                        pointBackgroundColor: '#9333ea',
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        tension: 0.42,
                        fill: true,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleColor: '#f1f5f9',
                        bodyColor: '#cbd5e1',
                        padding: 10,
                        cornerRadius: 8,
                        bodyFont: { size: 12, weight: '600' },
                        titleFont: { size: 11, weight: '700' },
                    },
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#94a3b8', font: { size: 11, weight: '600' } },
                        border: { display: false },
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9', drawBorder: false },
                        ticks: { color: '#94a3b8', font: { size: 11 }, precision: 0 },
                        border: { display: false },
                    },
                },
            },
        });
    })();

    /* ─────────────────────── DONUT: Roles ─────────────────────── */
    (function() {
        const ctx = document.getElementById('chartRoles');
        if (!ctx) return;
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: _roleLbls,
                datasets: [{
                    data: _roleData,
                    backgroundColor: ['#4f46e5', '#059669', '#334155'],
                    hoverBackgroundColor: ['#4338ca', '#047857', '#1e293b'],
                    borderWidth: 3,
                    borderColor: '#ffffff',
                    hoverOffset: 6,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleColor: '#f1f5f9',
                        bodyColor: '#cbd5e1',
                        padding: 9,
                        cornerRadius: 8,
                        bodyFont: { size: 12, weight: '600' },
                    },
                },
            },
        });
    })();

    /* ─────────────────────── DONUT: App Status ─────────────────── */
    (function() {
        const ctx = document.getElementById('chartAppStatus');
        if (!ctx) return;
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: _appLbls.length ? _appLbls : ['Aucune'],
                datasets: [{
                    data: _appData.length ? _appData : [1],
                    backgroundColor: _appData.length
                        ? ['#f59e0b', '#10b981', '#ef4444', '#6b7280']
                        : ['#e2e8f0'],
                    hoverBackgroundColor: ['#d97706', '#059669', '#dc2626', '#475569'],
                    borderWidth: 3,
                    borderColor: '#ffffff',
                    hoverOffset: 6,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleColor: '#f1f5f9',
                        bodyColor: '#cbd5e1',
                        padding: 9,
                        cornerRadius: 8,
                        bodyFont: { size: 12, weight: '600' },
                    },
                },
            },
        });
    })();

    /* ─────────────────────── BAR: Categories (init) ─────────────────────── */
    (function() {
        const ctx = document.getElementById('chartCategories');
        if (!ctx) return;
        const lbls = _catLbls.slice(0, STEP);
        const data = _catData.slice(0, STEP);
        setChartHeight('chartCategoriesWrap', lbls.length);
        chartCat = new Chart(ctx, {
            type: 'bar',
            data: makeBarDataset(lbls, data, catColors),
            options: barChartOptions((items) => '🏷️ Catégorie : ' + items[0].label),
        });
        updateCatBtn();
    })();

    /* ─────────────────────── BAR: Cities (init) ─────────────────────── */
    (function() {
        const ctx = document.getElementById('chartCities');
        if (!ctx) return;
        const lbls = _cityLbls.slice(0, STEP);
        const data = _cityData.slice(0, STEP);
        setChartHeight('chartCitiesWrap', lbls.length);
        chartCity = new Chart(ctx, {
            type: 'bar',
            data: makeBarDataset(lbls, data, cityColors),
            options: barChartOptions((items) => '📍 Ville : ' + items[0].label),
        });
        updateCityBtn();
    })();

    /* ── Count-up animation for KPI values ── */
    const animateCount = (el, target, duration = 1000, isPercentage = false) => {
        const start = performance.now();
        const update = (now) => {
            const elapsed = now - start;
            const progress = Math.min(elapsed / duration, 1);
            const ease = 1 - Math.pow(1 - progress, 3);
            el.textContent = Math.round(ease * target).toLocaleString('fr-FR');
            if (progress < 1) requestAnimationFrame(update);
        };
        requestAnimationFrame(update);
    };

    /* ── Animate bars on scroll ── */
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;

            /* KPI bars */
            entry.target.querySelectorAll('.adm-kpi-bar-fill').forEach(fill => {
                const pct = entry.target.dataset.pct || 0;
                setTimeout(() => fill.style.width = pct + '%', 200);
            });

            /* Count-up */
            entry.target.querySelectorAll('[data-count]').forEach(el => {
                const isPercentage = el.closest('.adm-kpi').querySelector('.adm-kpi-label').textContent === 'Taux conv.';
                animateCount(el, parseFloat(el.dataset.count), 900, isPercentage);
            });

            /* Horizontal bars */
            entry.target.querySelectorAll('.adm-bar-fill').forEach((fill, i) => {
                setTimeout(() => {
                    fill.style.width = (fill.dataset.width || 0) + '%';
                }, 80 + i * 60);
            });

            observer.unobserve(entry.target);
        });
    }, { threshold: 0.15 });

    /* Observe KPI cards */
    document.querySelectorAll('.adm-kpi').forEach(el => observer.observe(el));

    /* Observe analytics section */
    document.querySelectorAll('.adm-analytics').forEach(el => observer.observe(el));

    /* Also trigger bars already in view on load */
    document.querySelectorAll('.adm-bar-fill').forEach((fill, i) => {
        setTimeout(() => {
            if (isInViewport(fill)) fill.style.width = (fill.dataset.width || 0) + '%';
        }, 600 + i * 60);
    });

    function isInViewport(el) {
        const r = el.getBoundingClientRect();
        return r.top < window.innerHeight && r.bottom > 0;
    }
});
</script>
@endpush

</x-app-layout>