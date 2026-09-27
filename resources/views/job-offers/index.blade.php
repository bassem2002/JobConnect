<x-app-layout>

    <style>
        /* === UNIFIED DESIGN TOKENS === */
        :root {
            --p:    #4F46E5;
            --pd:   #3730A3;
            --pl:   #EEF2FF;
            --pr:   rgba(79,70,229,.12);
            --bd:   #E5E7EB;
            --bg:   #F9FAFB;
            --card: #FFFFFF;
            --ink:  #111827;
            --mid:  #374151;
            --sub:  #6B7280;
            --r:    12px;
            --shd:  0 1px 3px rgba(0,0,0,.07), 0 2px 4px rgba(0,0,0,.04);
            --shd-h:0 8px 30px rgba(79,70,229,.11);
            --ease: .18s cubic-bezier(.4,0,.2,1);
        }
        .pg { max-width:80rem; margin:0 auto; padding:2rem 1.5rem; }
        @media(max-width:640px){ .pg { padding:1rem; } }
        .pg-2col { display:flex; gap:2rem; align-items:flex-start; }
        @media(max-width:1024px){ .pg-2col { flex-direction:column; } }
        .flt-sidebar { width:244px; flex-shrink:0; position:sticky; top:80px; z-index:10; }
        @media(max-width:1024px){ .flt-sidebar { width:100%; position:static; } }
        .flt-card { background:var(--card); border:1px solid var(--bd); border-radius:var(--r); overflow:hidden; box-shadow:var(--shd); }
        .flt-head { display:flex; align-items:center; justify-content:space-between; padding:.875rem 1rem; background:var(--p); }
        .flt-head-l { display:flex; align-items:center; gap:.5rem; }
        .flt-head-l svg { color:#fff; flex-shrink:0; }
        .flt-head-title { font-size:.72rem; font-weight:800; text-transform:uppercase; letter-spacing:.08em; color:rgba(255,255,255,.95); }
        .flt-count { font-size:.65rem; font-weight:800; padding:.15rem .5rem; border-radius:99px; background:rgba(255,255,255,.2); color:#fff; }
        .flt-count.on { background:#fff; color:var(--p); }
        .flt-active-tags { display:flex; flex-wrap:wrap; gap:.3rem; padding:.6rem 1rem .2rem; border-top:1px solid #F1F5F9; }
        .flt-tag { display:inline-flex; align-items:center; gap:.25rem; padding:.18rem .55rem; border-radius:99px; font-size:.67rem; font-weight:600; background:var(--pl); color:var(--p); border:1px solid #C7D2FE; }
        .flt-tag a { color:inherit; opacity:.6; text-decoration:none; }
        .flt-tag a:hover { opacity:1; }
        .flt-body { padding:.875rem 1rem; display:flex; flex-direction:column; gap:0; }
        .flt-field { padding:.6rem 0; border-bottom:1px solid #F1F5F9; }
        .flt-field:last-of-type { border-bottom:none; }
        .flt-lbl { display:flex; align-items:center; gap:.35rem; font-size:.66rem; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:#94A3B8; margin-bottom:.4rem; }
        .flt-lbl svg { color:#CBD5E1; flex-shrink:0; }
        .flt-sel-wrap { position:relative; }
        .flt-sel-wrap::after { content:''; position:absolute; right:.65rem; top:50%; transform:translateY(-50%); width:10px; height:10px; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236B7280' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E"); background-size:contain; background-repeat:no-repeat; pointer-events:none; }
        .flt-sel, .flt-inp { width:100%; padding:.48rem 1.8rem .48rem .7rem; border:1.5px solid var(--bd); border-radius:8px; font-size:.8rem; font-family:inherit; font-weight:500; color:var(--ink); background:var(--bg); outline:none; appearance:none; cursor:pointer; transition:border-color var(--ease),box-shadow var(--ease),background var(--ease); }
        .flt-inp { appearance:auto; cursor:text; }
        .flt-sel:focus,.flt-inp:focus { border-color:var(--p); box-shadow:0 0 0 3px var(--pr); background:#fff; }
        .flt-sel.on,.flt-inp.on { border-color:#C7D2FE; background:var(--pl); color:var(--pd); font-weight:600; }
        .flt-acts { display:flex; flex-direction:column; gap:.4rem; padding-top:.7rem; }
        .flt-apply { display:flex; align-items:center; justify-content:center; gap:.4rem; padding:.58rem; background:var(--p); color:#fff; border:none; border-radius:8px; font-family:inherit; font-size:.8rem; font-weight:700; cursor:pointer; text-decoration:none; transition:background var(--ease),transform var(--ease); }
        .flt-apply:hover { background:var(--pd); transform:translateY(-1px); }
        .flt-reset { display:flex; align-items:center; justify-content:center; gap:.4rem; padding:.53rem; background:#fff; color:#64748B; border:1.5px solid var(--bd); border-radius:8px; font-family:inherit; font-size:.8rem; font-weight:600; cursor:pointer; text-decoration:none; transition:all var(--ease); }
        .flt-reset:hover { border-color:var(--p); color:var(--p); background:var(--pl); }
        .pg-main { flex:1; min-width:0; }
        .res-bar { display:flex; align-items:center; justify-content:space-between; margin-bottom:1.25rem; flex-wrap:wrap; gap:.75rem; }
        .res-count { font-size:.875rem; font-weight:600; color:var(--sub); }
        .res-count strong { color:var(--p); font-weight:800; }
        .chip-row { display:flex; flex-wrap:wrap; gap:.4rem; }
        .chip-active { display:inline-flex; align-items:center; gap:.3rem; padding:.25rem .7rem; border-radius:99px; font-size:.75rem; font-weight:600; background:var(--pl); color:var(--p); border:1px solid #C7D2FE; }
        .chip-active a { color:inherit; text-decoration:none; opacity:.6; }
        .chip-active a:hover { opacity:1; }
        .uc { background:var(--card); border:1px solid var(--bd); border-radius:var(--r); overflow:hidden; display:flex; flex-direction:column; position:relative; transition:border-color var(--ease),box-shadow var(--ease),transform var(--ease); }
        .uc::before { content:''; position:absolute; top:0; left:0; right:0; height:3px; background:linear-gradient(90deg,var(--p),#818CF8); transform:scaleX(0); transition:transform var(--ease); }
        .uc:hover { border-color:#C7D2FE; box-shadow:var(--shd-h); transform:translateY(-3px); }
        .uc:hover::before { transform:scaleX(1); }
        .uc-body { padding:1.125rem 1.25rem; flex:1; }
        .uc-foot { padding:.75rem 1.25rem; border-top:1px solid #F3F4F6; background:var(--bg); display:flex; align-items:center; justify-content:space-between; gap:.5rem; flex-wrap:wrap; }
        .uc-logo { width:46px; height:46px; border-radius:11px; border:1.5px solid var(--bd); object-fit:cover; flex-shrink:0; background:#fff; }
        .uc-logo-ph { width:46px; height:46px; border-radius:11px; background:var(--pl); border:1.5px solid #C7D2FE; display:flex; align-items:center; justify-content:center; font-size:1.1rem; font-weight:800; color:var(--p); flex-shrink:0; }
        .uc-co-row { display:flex; align-items:flex-start; gap:.875rem; margin-bottom:.75rem; }
        .uc-co-info { flex:1; min-width:0; }
        .uc-co-name { font-size:.8rem; font-weight:700; color:var(--p); line-height:1.25; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
        .uc-co-loc { display:flex; align-items:center; gap:.3rem; font-size:.73rem; color:var(--sub); margin-top:.2rem; }
        .uc-co-loc svg { flex-shrink:0; }
        .uc-title { font-size:.975rem; font-weight:800; color:var(--ink); text-decoration:none; display:block; line-height:1.4; transition:color var(--ease); margin:.5rem 0; }
        .uc-title:hover { color:var(--p); }
        .uc-desc { font-size:.78rem; color:var(--sub); line-height:1.55; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; margin:.25rem 0; }
        .uc-meta { display:flex; flex-wrap:wrap; gap:.35rem; margin-top:.7rem; }
        .uc-pill { display:inline-flex; align-items:center; gap:.28rem; padding:.22rem .6rem; border-radius:7px; font-size:.71rem; font-weight:600; background:var(--bg); color:var(--sub); border:1px solid var(--bd); }
        .uc-pill.hi { background:var(--pl); color:var(--p); border-color:#C7D2FE; font-weight:700; }
        .uc-pill.gr { background:#F0FDF4; color:#166534; border-color:#A7F3D0; }
        .uc-pill.am { background:#FFFBEB; color:#92400E; border-color:#FDE68A; }
        .uc-pill.rd { background:#FEF2F2; color:#991B1B; border-color:#FECACA; }
        .uc-date { font-size:.72rem; color:var(--sub); display:flex; align-items:center; gap:.25rem; }
        .btn-p { display:inline-flex; align-items:center; gap:.4rem; padding:.45rem .95rem; background:var(--p); color:#fff; border:none; border-radius:8px; font-family:inherit; font-size:.78rem; font-weight:700; cursor:pointer; text-decoration:none; transition:background var(--ease),transform var(--ease),box-shadow var(--ease); white-space:nowrap; }
        .btn-p:hover { background:var(--pd); transform:translateY(-1px); box-shadow:0 4px 12px var(--pr); }
        .btn-g { display:inline-flex; align-items:center; gap:.4rem; padding:.43rem .875rem; background:#fff; color:var(--sub); border:1.5px solid var(--bd); border-radius:8px; font-family:inherit; font-size:.78rem; font-weight:600; cursor:pointer; text-decoration:none; transition:all var(--ease); white-space:nowrap; }
        .btn-g:hover { border-color:var(--p); color:var(--p); background:var(--pl); }
        .btn-d { display:inline-flex; align-items:center; gap:.4rem; padding:.43rem .875rem; background:#fff; color:#DC2626; border:1.5px solid #FECACA; border-radius:8px; font-family:inherit; font-size:.78rem; font-weight:700; cursor:pointer; text-decoration:none; transition:all var(--ease); white-space:nowrap; }
        .btn-d:hover { background:#FEF2F2; border-color:#EF4444; }
        .btn-i { display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; border:1.5px solid var(--bd); background:#fff; color:var(--sub); cursor:pointer; transition:all var(--ease); text-decoration:none; }
        .btn-i:hover { border-color:var(--p); color:var(--p); background:var(--pl); }
        .btn-i.dg:hover { border-color:#FECACA; color:#DC2626; background:#FEF2F2; }
        .empty-box { background:var(--card); border:2px dashed var(--bd); border-radius:var(--r); padding:3.5rem 2rem; text-align:center; }
        .empty-ico { width:52px; height:52px; border-radius:14px; background:var(--pl); margin:0 auto .875rem; display:flex; align-items:center; justify-content:center; }
        .empty-box h3 { font-size:1rem; font-weight:800; color:var(--ink); margin-bottom:.4rem; }
        .empty-box p { font-size:.85rem; color:var(--sub); margin-bottom:1.25rem; max-width:300px; margin-left:auto; margin-right:auto; line-height:1.6; }

        /* Save button */
        .jo-btn-save { display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; border:1.5px solid var(--bd); background:#fff; color:var(--sub); cursor:pointer; transition:all var(--ease); }
        .jo-btn-save:hover { border-color:#C7D2FE; color:var(--p); background:var(--pl); }
        .jo-btn-save.saved { border-color:var(--p); background:var(--pl); color:var(--p); }
        .jo-btn-edit { display:inline-flex; align-items:center; gap:.3rem; padding:.38rem .75rem; border:1.5px solid var(--bd); color:var(--mid); background:#fff; border-radius:8px; font-family:inherit; font-size:.72rem; font-weight:600; text-decoration:none; cursor:pointer; transition:all var(--ease); }
        .jo-btn-edit:hover { border-color:var(--p); color:var(--p); background:var(--pl); }
        .jo-btn-archive { display:inline-flex; align-items:center; gap:.3rem; padding:.38rem .75rem; border:1.5px solid #FECACA; color:#DC2626; background:#fff; border-radius:8px; font-family:inherit; font-size:.72rem; font-weight:600; cursor:pointer; transition:all var(--ease); }
        .jo-btn-archive:hover { background:#FEF2F2; border-color:#EF4444; }

        /* jo-main must retain for AJAX */
        .jo-main { flex:1; min-width:0; }

        /* Modal */
        .modal-backdrop { display:none; position:fixed; inset:0; z-index:500; background:rgba(15,23,42,.45); backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:1rem; }
        .modal-backdrop.open { display:flex; }
        .modal { background:#fff; border-radius:16px; width:100%; max-width:420px; border:1.5px solid #E5E7EB; box-shadow:0 20px 60px rgba(0,0,0,.18); overflow:hidden; }
        .modal-head { display:flex; align-items:flex-start; gap:1rem; padding:1.25rem 1.5rem; border-bottom:1px solid #E5E7EB; }
        .modal-ico { width:42px; height:42px; border-radius:10px; flex-shrink:0; display:flex; align-items:center; justify-content:center; }
        .modal-title { font-size:1rem; font-weight:800; color:#111827; margin-bottom:.2rem; }
        .modal-msg { font-size:.85rem; color:#6B7280; line-height:1.55; margin:0; }
        .modal-foot { display:flex; justify-content:flex-end; gap:.75rem; padding:1rem 1.5rem; background:#F9FAFB; }
        .modal-cancel { padding:.53rem 1.2rem; border-radius:8px; border:1.5px solid #E5E7EB; background:#fff; font-size:.85rem; font-weight:600; color:#6B7280; cursor:pointer; transition:all .18s; font-family:inherit; }
        .modal-cancel:hover { border-color:#4F46E5; color:#4F46E5; }
        .modal-ok { padding:.53rem 1.2rem; border-radius:8px; border:none; font-size:.85rem; font-weight:700; color:#fff; cursor:pointer; transition:opacity .18s; font-family:inherit; }
        .modal-ok:hover { opacity:.9; }

        /* Cards list — 2-per-row grid */
        .jo-cards { display:grid; grid-template-columns:repeat(2,1fr); gap:1rem; }
        @media(max-width:960px){ .jo-cards { grid-template-columns:1fr; } }
        @keyframes cardIn { from { opacity:0; transform:translateY(8px); } to { opacity:1; transform:translateY(0); } }
        .jo-cards .uc { animation:cardIn .25s ease both; }
        .jo-cards .uc:nth-child(1) { animation-delay:.03s; }
        .jo-cards .uc:nth-child(2) { animation-delay:.07s; }
        .jo-cards .uc:nth-child(3) { animation-delay:.11s; }
        .jo-cards .uc:nth-child(4) { animation-delay:.15s; }
        .jo-cards .uc:nth-child(5) { animation-delay:.19s; }

        /* Category badge */
        .uc-cat { display:inline-flex;align-items:center;gap:.3rem;padding:.12rem .55rem .12rem .2rem;border-radius:99px;font-size:.67rem;font-weight:700;background:var(--pl);color:var(--p);border:1px solid #C7D2FE;letter-spacing:.02em; }
        .uc-cat-img { width:18px;height:18px;border-radius:50%;object-fit:cover;flex-shrink:0;border:1.5px solid #C7D2FE;background:#fff; }
        .uc-cat-ico { flex-shrink:0; }

        /* Card inner layout */
        .uc-inner { display:flex; align-items:flex-start; gap:.85rem; padding:.95rem 1.1rem .8rem; }
        .uc-inner-content { flex:1; min-width:0; }
        .uc-inner-top { display:flex; align-items:flex-start; justify-content:space-between; gap:.6rem; margin-bottom:.25rem; }
        .uc-type-tag { font-size:.65rem; font-weight:700; letter-spacing:.04em; white-space:nowrap; flex-shrink:0; text-transform:uppercase; color:var(--sub); padding:.18rem 0; }
        .uc-type-tag.green  { color:#059669; }
        .uc-type-tag.amber  { color:#D97706; }
        .uc-type-tag.indigo { color:#4F46E5; }
        .uc-type-tag.purple { color:#7C3AED; }
        .uc-co { display:flex; align-items:center; gap:.28rem; font-size:.75rem; font-weight:600; color:var(--sub); margin-bottom:.55rem; }
        .uc-co svg { opacity:.55; flex-shrink:0; }
        .uc-inline-meta { display:flex; flex-wrap:wrap; gap:.3rem .5rem; align-items:center; }
        .uc-mpill { display:inline-flex; align-items:center; gap:.25rem; font-size:.72rem; font-weight:500; color:var(--sub); background:var(--bg); border:1px solid var(--bd); border-radius:6px; padding:.18rem .55rem; }
        .uc-mpill svg { width:11px; height:11px; color:#94A3B8; flex-shrink:0; }
        .uc-mpill.salary { color:var(--p); background:var(--pl); border-color:#C7D2FE; font-weight:700; }
        .uc-mpill.salary svg { color:var(--p); }
        .uc-foot-inner { display:flex; align-items:center; justify-content:flex-end; gap:.4rem; padding:.55rem 1.1rem; border-top:1px solid var(--bg); background:var(--bg); }

        /* ── Custom scrollable dropdown ─────────────────────────── */
        .flt-cdd { position:relative; }
        .flt-cdd-trigger {
            width:100%; padding:.48rem 1.8rem .48rem .7rem; border:1.5px solid var(--bd);
            border-radius:8px; font-size:.8rem; font-family:inherit; font-weight:500;
            color:var(--ink); background:var(--bg); cursor:pointer;
            display:flex; align-items:center; justify-content:space-between; gap:.4rem;
            transition:border-color var(--ease),box-shadow var(--ease),background var(--ease);
            text-align:left; outline:none; position:relative;
        }
        .flt-cdd-trigger::after {
            content:''; position:absolute; right:.65rem; top:50%; transform:translateY(-50%);
            width:10px; height:10px;
            background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236B7280' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
            background-size:contain; background-repeat:no-repeat;
            transition:transform var(--ease);
        }
        .flt-cdd.open .flt-cdd-trigger::after { transform:translateY(-50%) rotate(180deg); }
        .flt-cdd-trigger:focus,.flt-cdd.open .flt-cdd-trigger { border-color:var(--p); box-shadow:0 0 0 3px var(--pr); background:#fff; }
        .flt-cdd-trigger.on { border-color:#C7D2FE; background:var(--pl); color:var(--pd); font-weight:600; }
        .flt-cdd-val { flex:1; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
        .flt-cdd-panel {
            position:absolute; top:calc(100% + 3px); left:0; right:0; z-index:200;
            background:#fff; border:1.5px solid var(--bd); border-radius:8px;
            max-height:calc(5 * 2.15rem); overflow-y:auto;
            box-shadow:0 8px 24px rgba(0,0,0,.1); display:none;
        }
        .flt-cdd.open .flt-cdd-panel { display:block; }
        .flt-cdd-opt {
            padding:.48rem .7rem; font-size:.8rem; font-weight:500; color:var(--ink);
            cursor:pointer; transition:background var(--ease),color var(--ease);
            white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
        }
        .flt-cdd-opt:hover { background:var(--pl); color:var(--pd); }
        .flt-cdd-opt.sel { background:var(--pl); color:var(--p); font-weight:700; }
    </style>

    {{-- ── Hero section ──────────────────────────────────────── --}}
    <div class="jo-hero">
        <h1 class="jo-hero-title">
            Trouvez votre<br><em>prochain defi</em>
        </h1>
        <p class="jo-hero-sub">
            Explorez des milliers d'offres d'emploi en Tunisie et donnez un nouvel elan a votre carriere.
        </p>
        <form action="{{ route('job-offers.index') }}" method="GET" class="jo-search-hero" id="hero-search-form">
            <div class="jo-search-field">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" id="hero-search-input"
                       value="{{ request('search') }}"
                       placeholder="Titre, entreprise, mot-clé, catégorie, ville..."
                       autocomplete="off">
            </div>
            <button type="submit" class="jo-search-btn">
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Rechercher
            </button>
        </form>
    </div>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap');
        .jo-hero { background:#0F172A; border-radius:20px; padding:3rem 2rem 2.25rem; margin-bottom:1.75rem; text-align:center; position:relative; overflow:hidden; }
        .jo-hero::before { content:''; position:absolute; top:-60px; right:-60px; width:280px; height:280px; border-radius:50%; background:#4F46E518; pointer-events:none; }
        .jo-hero::after { content:''; position:absolute; bottom:-80px; left:-40px; width:220px; height:220px; border-radius:50%; background:#7C3AED12; pointer-events:none; }
        .jo-hero-badge { display:inline-flex; align-items:center; gap:.45rem; background:rgba(79,70,229,.15); border:1px solid rgba(79,70,229,.3); color:#A5B4FC; font-family:'Plus Jakarta Sans',sans-serif; font-size:.72rem; font-weight:600; padding:.3rem .85rem; border-radius:99px; margin-bottom:1.25rem; letter-spacing:.02em; position:relative; z-index:1; }
        .jo-hero-badge-dot { width:6px; height:6px; border-radius:50%; background:#6366F1; animation:pulse-dot 2s ease infinite; flex-shrink:0; }
        @keyframes pulse-dot { 0%,100% { opacity:1; transform:scale(1); } 50% { opacity:.5; transform:scale(.75); } }
        .jo-hero-title { font-family:'Syne',sans-serif; font-size:clamp(1.6rem,4vw,2.4rem); font-weight:800; color:#fff; line-height:1.15; letter-spacing:-.04em; margin:0 0 .85rem; position:relative; z-index:1; }
        .jo-hero-title em { font-style:normal; color:#818CF8; }
        .jo-hero-sub { font-family:'Plus Jakarta Sans',sans-serif; font-size:.875rem; color:rgba(255,255,255,.45); max-width:420px; margin:0 auto 1.75rem; line-height:1.65; position:relative; z-index:1; }
        .jo-search-hero { display:flex; align-items:center; background:#fff; border-radius:14px; padding:5px; max-width:640px; margin:0 auto 1.25rem; position:relative; z-index:1; }
        .jo-search-field { flex:1; display:flex; align-items:center; gap:.5rem; padding:.55rem 1rem; }
        .jo-search-field svg { color:#94A3B8; flex-shrink:0; }
        .jo-search-field input { border:none; outline:none; background:transparent; font-family:'Plus Jakarta Sans',sans-serif; font-size:.825rem; color:#0F172A; width:100%; }
        .jo-search-field input::placeholder { color:#94A3B8; }
        .jo-search-btn { display:inline-flex; align-items:center; gap:.4rem; background:#3730A3; color:#fff; border:none; border-radius:10px; padding:.6rem 1.25rem; font-family:'Plus Jakarta Sans',sans-serif; font-size:.825rem; font-weight:700; cursor:pointer; white-space:nowrap; margin-left:4px; transition:background .18s; }
        .jo-search-btn:hover { background:#312E81; }
        .jo-hero-tags { display:flex; align-items:center; justify-content:center; flex-wrap:wrap; gap:.4rem; position:relative; z-index:1; }
        .jo-hero-tag-label { font-family:'Plus Jakarta Sans',sans-serif; font-size:.72rem; color:rgba(255,255,255,.35); font-weight:500; }
        .jo-hero-tag { font-family:'Plus Jakarta Sans',sans-serif; font-size:.72rem; font-weight:600; color:rgba(255,255,255,.6); background:rgba(255,255,255,.07); border:1px solid rgba(255,255,255,.1); padding:.25rem .65rem; border-radius:99px; text-decoration:none; transition:background .15s,color .15s,border-color .15s; }
        .jo-hero-tag:hover { background:rgba(79,70,229,.25); border-color:rgba(79,70,229,.4); color:#A5B4FC; }
        @media (max-width:600px) {
            .jo-hero { padding:2rem 1.1rem 1.75rem; }
            .jo-search-hero { flex-direction:column; border-radius:12px; padding:.5rem; gap:0; }
            .jo-search-field { width:100%; }
            .jo-search-btn { width:100%; justify-content:center; margin-left:0; margin-top:4px; border-radius:8px; }
        }
    </style>

    {{-- ── Page layout ─────────────────────────────────────── --}}
    <div style="max-width:80rem;margin:0 auto;padding:0 1.5rem 2rem;display:flex;gap:2rem;align-items:flex-start;">

        {{-- ── Sidebar ─────────────────────────────────────── --}}
        @php
            $activeCount = collect(['location','category_id','education_level','type'])
                ->filter(fn($k) => request($k))->count();
        @endphp

        <aside class="flt-sidebar">
            <div class="flt-card">
                <div class="flt-head">
                    <div class="flt-head-l">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 4h18M7 12h10M11 20h2"/>
                        </svg>
                        <span class="flt-head-title">Affiner la recherche</span>
                    </div>
                    <span class="flt-count {{ $activeCount > 0 ? 'on' : '' }}" {{ $activeCount == 0 ? 'style=display:none' : '' }}>
                        {{ $activeCount }}
                    </span>
                </div>

                @if(request()->hasAny(['location','category_id','education_level','type']))
                    <div class="flt-active-tags">
                        @if(request('location'))
                            <span class="flt-tag">
                                {{ request('location') }}
                                <a href="{{ request()->fullUrlWithoutQuery(['location']) }}">x</a>
                            </span>
                        @endif
                        @if(request('type'))
                            @php $tl = ['full-time'=>'Temps plein','part-time'=>'Temps partiel','remote'=>'Teletravail']; @endphp
                            <span class="flt-tag">
                                {{ $tl[request('type')] ?? request('type') }}
                                <a href="{{ request()->fullUrlWithoutQuery(['type']) }}">x</a>
                            </span>
                        @endif
                        @if(request('education_level'))
                            <span class="flt-tag">
                                {{ request('education_level') }}
                                <a href="{{ request()->fullUrlWithoutQuery(['education_level']) }}">x</a>
                            </span>
                        @endif
                        @if(request('category_id'))
                            @php $catName = $categories->firstWhere('id', request('category_id'))?->name ?? ''; @endphp
                            @if($catName)
                                <span class="flt-tag">
                                    {{ $catName }}
                                    <a href="{{ request()->fullUrlWithoutQuery(['category_id']) }}">x</a>
                                </span>
                            @endif
                        @endif
                    </div>
                @endif

                <div class="flt-body">
                    <form method="GET" action="{{ route('job-offers.index') }}" id="sidebar-filter-form">
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif

                        {{-- Location --}}
                        <div class="flt-field">
                            <div class="flt-lbl">
                                <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Localisation
                            </div>
                            <div class="flt-cdd" id="dd-location">
                                <button type="button" class="flt-cdd-trigger {{ request('location') ? 'on' : '' }}">
                                    <span class="flt-cdd-val">{{ request('location') ?: 'Toutes les villes' }}</span>
                                </button>
                                <div class="flt-cdd-panel">
                                    <div class="flt-cdd-opt {{ !request('location') ? 'sel' : '' }}" data-v="">Toutes les villes</div>
                                    @foreach(['Ariana','Beja','Ben Arous','Bizerte','Gabes','Gafsa','Jendouba','Kairouan','Kasserine','Kebili','Le Kef','Mahdia','La Manouba','Medenine','Monastir','Nabeul','Sfax','Sidi Bouzid','Siliana','Sousse','Tataouine','Tozeur','Tunis','Zaghouan'] as $city)
                                        <div class="flt-cdd-opt {{ request('location') == $city ? 'sel' : '' }}" data-v="{{ $city }}">{{ $city }}</div>
                                    @endforeach
                                </div>
                                <input type="hidden" name="location" value="{{ request('location') }}">
                            </div>
                        </div>

                        {{-- Category --}}
                        <div class="flt-field">
                            <div class="flt-lbl">
                                <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                                Domaine
                            </div>
                            @php $selCatName = $categories->firstWhere('id', request('category_id'))?->name ?? ''; @endphp
                            <div class="flt-cdd" id="dd-category">
                                <button type="button" class="flt-cdd-trigger {{ request('category_id') ? 'on' : '' }}">
                                    <span class="flt-cdd-val">{{ $selCatName ?: 'Toutes les catégories' }}</span>
                                </button>
                                <div class="flt-cdd-panel">
                                    <div class="flt-cdd-opt {{ !request('category_id') ? 'sel' : '' }}" data-v="">Toutes les catégories</div>
                                    @foreach($categories as $category)
                                        <div class="flt-cdd-opt {{ request('category_id') == $category->id ? 'sel' : '' }}" data-v="{{ $category->id }}">{{ $category->name }}</div>
                                    @endforeach
                                </div>
                                <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                            </div>
                        </div>

                        {{-- Education --}}
                        <div class="flt-field">
                            <div class="flt-lbl">
                                <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                                </svg>
                                Niveau d'etudes
                            </div>
                            <div class="flt-sel-wrap"><select name="education_level" class="flt-sel {{ request('education_level') ? 'on' : '' }}">
                                <option value="">Tous les niveaux</option>
                                @foreach(['Inférieur au baccalauréat','Bac','Bac+3','Bac+5','Plus que Bac +5'] as $level)
                                    <option value="{{ $level }}" {{ request('education_level') == $level ? 'selected' : '' }}>{{ $level }}</option>
                                @endforeach
                            </select></div>
                        </div>

                        {{-- Type --}}
                        <div class="flt-field">
                            <div class="flt-lbl">
                                <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Type de contrat
                            </div>
                            <div class="flt-sel-wrap"><select name="type" class="flt-sel {{ request('type') ? 'on' : '' }}">
                                <option value="">Tous les types</option>
                                <option value="full-time"  {{ request('type') == 'full-time'  ? 'selected' : '' }}>Temps plein</option>
                                <option value="part-time"  {{ request('type') == 'part-time'  ? 'selected' : '' }}>Temps partiel</option>
                                <option value="remote"     {{ request('type') == 'remote'     ? 'selected' : '' }}>Teletravail</option>
                                <option value="freelance"  {{ request('type') == 'freelance'  ? 'selected' : '' }}>Freelance</option>
                                <option value="internship" {{ request('type') == 'internship' ? 'selected' : '' }}>Stage</option>
                            </select></div>
                        </div>

                        <div class="flt-acts">
                            <button type="submit" class="flt-apply">
                                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 4h18M7 12h10M11 20h2"/>
                                </svg>
                                Appliquer les filtres
                            </button>
                            <button type="button" class="flt-reset" onclick="resetFilters()">
                                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Effacer tout
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </aside>

        {{-- ── Main content (KEEP .jo-main class) ─────────── --}}
        <div class="jo-main">

            {{-- Results bar --}}
            <div class="res-bar">
                <p class="res-count">
                    <strong>{{ $jobOffers->total() }}</strong>
                    opportunite{{ $jobOffers->total() > 1 ? 's' : '' }} disponible{{ $jobOffers->total() > 1 ? 's' : '' }}
                </p>

                @if(request()->hasAny(['search','location','category_id','education_level','type']))
                    <div class="chip-row">
                        @if(request('search'))
                            <span class="chip-active">
                                {{ request('search') }}
                                <a href="{{ request()->fullUrlWithoutQuery(['search']) }}">x</a>
                            </span>
                        @endif
                        @if(request('location'))
                            <span class="chip-active">
                                {{ request('location') }}
                                <a href="{{ request()->fullUrlWithoutQuery(['location']) }}">x</a>
                            </span>
                        @endif
                        @if(request('type'))
                            @php $tl2 = ['full-time'=>'Temps plein','part-time'=>'Temps partiel','remote'=>'Teletravail','freelance'=>'Freelance','internship'=>'Stage']; @endphp
                            <span class="chip-active">
                                {{ $tl2[request('type')] ?? request('type') }}
                                <a href="{{ request()->fullUrlWithoutQuery(['type']) }}">x</a>
                            </span>
                        @endif
                        @if(request('education_level'))
                            <span class="chip-active">
                                {{ request('education_level') }}
                                <a href="{{ request()->fullUrlWithoutQuery(['education_level']) }}">x</a>
                            </span>
                        @endif
                        @if(request('category_id'))
                            @php $catN = $categories->firstWhere('id', request('category_id'))?->name ?? ''; @endphp
                            @if($catN)
                                <span class="chip-active">
                                    {{ $catN }}
                                    <a href="{{ request()->fullUrlWithoutQuery(['category_id']) }}">x</a>
                                </span>
                            @endif
                        @endif
                    </div>
                @endif
            </div>

            {{-- Cards --}}
            @php
                $typeMap = [
                    'full-time'  => ['label' => 'Temps plein',   'cls' => 'green'],
                    'part-time'  => ['label' => 'Temps partiel', 'cls' => 'amber'],
                    'remote'     => ['label' => 'Teletravail',   'cls' => 'indigo'],
                    'freelance'  => ['label' => 'Freelance',     'cls' => 'purple'],
                    'internship' => ['label' => 'Stage',         'cls' => 'blue'],
                ];
                $savedJobIds = (Auth::check() && Auth::user()->isCandidate())
                    ? Auth::user()->savedJobs->pluck('job_offer_id')->toArray()
                    : [];
            @endphp

            <div class="jo-cards">
                @forelse($jobOffers as $offer)
                    @php $tm = $typeMap[$offer->type] ?? ['label' => ucfirst($offer->type), 'cls' => '']; @endphp

                    <div class="uc" style="cursor:pointer;"
                         onclick="if(!event.target.closest('button')&&!event.target.closest('a')&&!event.target.closest('form')) window.location.href='{{ route('job-offers.show', $offer) }}'">

                        <div class="uc-inner">
                            {{-- Logo --}}
                            @if($offer->company->logo_path)
                                <img src="{{ $offer->company->logo_url }}" alt="Logo" class="uc-logo">
                            @else
                                <img src="{{ 'https://ui-avatars.com/api/?name='.urlencode(optional($offer->offerCategory)->name ?? 'X').'&background=EEF2FF&color=4F46E5' }}"
                                     alt="{{ optional($offer->offerCategory)->name ?? 'Categorie' }}"
                                     class="uc-logo" style="object-fit:cover;">
                            @endif

                            <div class="uc-inner-content">
                                <div class="uc-inner-top">
                                    <a href="{{ route('job-offers.show', $offer) }}" class="uc-title" style="margin:0;">
                                        {{ $offer->title }}
                                    </a>
                                    <span class="uc-type-tag {{ $tm['cls'] }}">{{ $tm['label'] }}</span>
                                </div>

                                <div class="uc-co">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    {{ $offer->company->name }}
                                </div>

                                <div class="uc-inline-meta">
                                    <span class="uc-mpill">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        {{ $offer->location }}
                                    </span>
                                    @if($offer->experience_years)
                                        <span class="uc-mpill">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ $offer->experience_years }} ans exp.
                                        </span>
                                    @endif
                                    <span class="uc-mpill salary">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $offer->salary ? number_format($offer->salary, 0, ',', ' ').' TND' : 'Non specifie' }}
                                    </span>
                                    @if(optional($offer->offerCategory)->name)
                                        <span class="uc-cat">
                                            @if($offer->offerCategory->image_url)
                                                <img src="{{ $offer->offerCategory->image_url }}" alt="{{ $offer->offerCategory->name }}" class="uc-cat-img">
                                            @else
                                                <svg class="uc-cat-ico" width="9" height="9" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z"/></svg>
                                            @endif
                                            {{ $offer->offerCategory->name }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="uc-foot-inner">
                            @if(Auth::check() && Auth::user()->isCandidate())
                                <form method="POST" action="{{ route('saved-jobs.toggle', $offer) }}"
                                      class="save-job-form" style="display:contents;">
                                    @csrf
                                    <button type="submit"
                                            class="jo-btn-save save-job-btn {{ in_array($offer->id, $savedJobIds) ? 'saved' : '' }}"
                                            title="{{ in_array($offer->id, $savedJobIds) ? 'Retirer des favoris' : 'Sauvegarder' }}">
                                        @if(in_array($offer->id, $savedJobIds))
                                            <svg width="13" height="13" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/>
                                            </svg>
                                        @else
                                            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                                            </svg>
                                        @endif
                                    </button>
                                </form>
                            @endif

                            @if(Auth::check() && Auth::user()->isCompany() && $offer->user_id === Auth::id())
                                <a href="{{ route('job-offers.edit', $offer) }}" class="jo-btn-edit">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                    Modifier
                                </a>
                                @if($offer->status === 'archived')
                                    <form method="POST" action="{{ route('job-offers.unarchive', $offer) }}"
                                          class="js-restore-form" data-title="{{ $offer->title }}"
                                          style="display:contents;">
                                        @csrf
                                        <button type="submit" class="jo-btn-edit" style="background:#EEF2FF;color:#4F46E5;border-color:#C7D2FE;">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <polyline stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          points="21 8 21 21 3 21 3 8"/>
                                                <rect x="1" y="3" width="22" height="5" stroke-width="2"/>
                                            </svg>
                                            Désarchiver
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('job-offers.archive', $offer) }}"
                                          class="js-archive-form" data-title="{{ $offer->title }}"
                                          style="display:contents;">
                                        @csrf
                                        <button type="submit" class="jo-btn-archive">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <polyline stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          points="21 8 21 21 3 21 3 8"/>
                                                <rect x="1" y="3" width="22" height="5" stroke-width="2"/>
                                            </svg>
                                            Archiver
                                        </button>
                                    </form>
                                @endif
                            @endif

                            <a href="{{ route('job-offers.show', $offer) }}" class="btn-p">
                                Voir l'offre
                                <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                @empty
                    <div class="empty-box" style="grid-column:1/-1;">
                        <div class="empty-ico">
                            <svg width="26" height="26" fill="none" stroke="#4F46E5" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <h3>Aucune offre ne correspond aux filtres sélectionnés</h3>
                        <a href="{{ route('job-offers.index') }}" class="btn-p" style="display:inline-flex;">
                            Réinitialiser la recherche
                        </a>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($jobOffers->hasPages())
                <div style="margin-top:2rem;display:flex;justify-content:center;">
                    {{ $jobOffers->links() }}
                </div>
            @endif
        </div>
    </div>

    <script>
    // ── Custom scrollable dropdowns ─────────────────────────────
    function initCustomDropdowns() {
        document.querySelectorAll('.flt-cdd').forEach(function(wrap) {
            var trigger = wrap.querySelector('.flt-cdd-trigger');
            var panel   = wrap.querySelector('.flt-cdd-panel');
            var input   = wrap.querySelector('input[type=hidden]');
            var valSpan = wrap.querySelector('.flt-cdd-val');
            if (!trigger || !panel) return;

            trigger.addEventListener('click', function(e) {
                e.stopPropagation();
                document.querySelectorAll('.flt-cdd.open').forEach(function(w) { if (w !== wrap) w.classList.remove('open'); });
                wrap.classList.toggle('open');
            });

            wrap.querySelectorAll('.flt-cdd-opt').forEach(function(opt) {
                opt.addEventListener('click', function() {
                    var val  = opt.dataset.v;
                    var text = opt.textContent.trim();
                    if (input) input.value = val;
                    if (valSpan) valSpan.textContent = text;
                    wrap.querySelectorAll('.flt-cdd-opt').forEach(function(o) { o.classList.remove('sel'); });
                    opt.classList.add('sel');
                    wrap.classList.remove('open');
                    trigger.classList.toggle('on', !!val);
                });
            });
        });

        document.addEventListener('click', function(e) {
            if (!e.target.closest('.flt-cdd')) {
                document.querySelectorAll('.flt-cdd.open').forEach(function(w) { w.classList.remove('open'); });
            }
        });
    }
    initCustomDropdowns();

    // ── Real-time search debounce ───────────────────────────────
    (function () {
        var input = document.getElementById('hero-search-input');
        var form  = document.getElementById('hero-search-form');
        if (!input || !form) return;
        var timer = null;
        input.addEventListener('input', function () {
            clearTimeout(timer);
            timer = setTimeout(function () {
                // Preserve sidebar filters when auto-submitting
                var sidebarForm = document.getElementById('sidebar-filter-form');
                if (sidebarForm) {
                    sidebarForm.querySelectorAll('input[type=hidden], select').forEach(function (el) {
                        if (el.name && el.name !== 'search' && el.value) {
                            var existing = form.querySelector('input[name="' + el.name + '"]');
                            if (!existing) {
                                var h = document.createElement('input');
                                h.type = 'hidden';
                                h.name = el.name;
                                h.value = el.value;
                                h.setAttribute('data-auto', '1');
                                form.appendChild(h);
                            }
                        }
                    });
                }
                form.submit();
            }, 400);
        });
    })();

    function resetFilters() {
        const form = document.getElementById('sidebar-filter-form');
        if (!form) return;
        form.querySelectorAll('select').forEach(function(s) { s.value = ''; s.classList.remove('on'); });
        form.querySelectorAll('input[type="text"]').forEach(function(i) { i.value = ''; i.classList.remove('on'); });
        // Reset custom dropdowns
        form.querySelectorAll('.flt-cdd').forEach(function(wrap) {
            var input   = wrap.querySelector('input[type=hidden]');
            var valSpan = wrap.querySelector('.flt-cdd-val');
            var trigger = wrap.querySelector('.flt-cdd-trigger');
            var firstOpt = wrap.querySelector('.flt-cdd-opt');
            if (input)   input.value = '';
            if (valSpan && firstOpt) valSpan.textContent = firstOpt.textContent.trim();
            if (trigger) trigger.classList.remove('on');
            wrap.querySelectorAll('.flt-cdd-opt').forEach(function(o) { o.classList.remove('sel'); });
            if (firstOpt) firstOpt.classList.add('sel');
        });
        const heroInput = document.querySelector('.jo-search-hero input[name="search"]');
        if (heroInput) heroInput.value = '';
        var url = new URL('{{ route('job-offers.index') }}');
        sessionStorage.removeItem('jo_index_url');
        if (typeof loadResults === 'function') loadResults(url.toString());
        else form.submit();
    }

    document.addEventListener('DOMContentLoaded', function () {

        // Save current URL when server renders the page with active filters
        if (window.location.search) {
            sessionStorage.setItem('jo_index_url', window.location.href);
        }

        function initSaveButtons() {
            document.querySelectorAll('.save-job-form').forEach(function (form) {
                form.replaceWith(form.cloneNode(true));
            });
            document.querySelectorAll('.save-job-form').forEach(function (form) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    var btn = this.querySelector('.save-job-btn');
                    fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': this.querySelector('[name="_token"]').value,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({})
                    }).then(function (r) { return r.json(); }).then(function (data) {
                        if (data.status === 'added') {
                            btn.classList.add('saved');
                            btn.title = 'Retirer des favoris';
                            btn.innerHTML = '<svg width="13" height="13" viewBox="0 0 20 20" fill="currentColor"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/></svg>';
                        } else {
                            btn.classList.remove('saved');
                            btn.title = 'Sauvegarder';
                            btn.innerHTML = '<svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>';
                        }
                    });
                });
            });
        }

        initSaveButtons();

        var joMain    = document.querySelector('.jo-main');
        var heroForm  = document.querySelector('.jo-search-hero');
        var sideForm  = document.getElementById('sidebar-filter-form');
        var controller = new AbortController();

        async function loadResults(url) {
            joMain.style.opacity = '0.5';
            joMain.style.pointerEvents = 'none';
            controller.abort();
            controller = new AbortController();
            try {
                var response = await fetch(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    signal: controller.signal
                });
                var html = await response.text();
                var parser = new DOMParser();
                var doc = parser.parseFromString(html, 'text/html');
                var newMain = doc.querySelector('.jo-main');
                if (newMain) joMain.innerHTML = newMain.innerHTML;

                var oldHead = document.querySelector('.flt-head');
                var newHead = doc.querySelector('.flt-head');
                if (oldHead && newHead) oldHead.outerHTML = newHead.outerHTML;

                var oldTags = document.querySelector('.flt-active-tags');
                var newTags = doc.querySelector('.flt-active-tags');
                if (oldTags && newTags) oldTags.outerHTML = newTags.outerHTML;

                window.history.pushState({}, '', url);
                sessionStorage.setItem('jo_index_url', url);
                initSaveButtons();
                var mainTop = document.querySelector('.jo-main');
                if (mainTop) window.scrollTo({ top: mainTop.offsetTop - 100, behavior: 'smooth' });
            } catch (e) {
                if (e.name !== 'AbortError') console.error('Fetch error:', e);
            } finally {
                joMain.style.opacity = '1';
                joMain.style.pointerEvents = 'auto';
            }
        }

        if (sideForm) {
            sideForm.addEventListener('submit', function (e) {
                e.preventDefault();
                var url = new URL(this.action);
                var formData = new FormData(this);
                formData.forEach(function (value, key) {
                    if (value) url.searchParams.append(key, value);
                });
                loadResults(url.toString());
            });
        }

        if (heroForm) {
            heroForm.addEventListener('submit', function (e) {
                e.preventDefault();
                var url = new URL(this.action);
                var input = this.querySelector('input[name="search"]');
                if (input && input.value) url.searchParams.append('search', input.value);
                if (sideForm) {
                    var fd = new FormData(sideForm);
                    fd.forEach(function (value, key) {
                        if (value && key !== 'search') url.searchParams.append(key, value);
                    });
                }
                loadResults(url.toString());
            });
        }

        document.addEventListener('click', function (e) {
            var link = e.target.closest('.jo-main nav a') ||
                       e.target.closest('.chip-active a') ||
                       e.target.closest('.flt-tag a') ||
                       e.target.closest('.jo-hero-tags a');
            if (link) {
                e.preventDefault();
                loadResults(link.href);
            }
        });

        window.addEventListener('popstate', function () {
            loadResults(window.location.href);
        });

        // Restore last filter state when navigating back from another page
        (function () {
            var KEY = 'jo_index_url';
            var currentPath = window.location.pathname;
            var referrer = document.referrer;
            var referrerPath = referrer ? (function() { try { return new URL(referrer).pathname; } catch(e) { return null; } })() : null;
            // Only restore if: URL has no filters AND coming from a different page
            if (!window.location.search && referrerPath && referrerPath !== currentPath) {
                var saved = sessionStorage.getItem(KEY);
                if (saved) {
                    try {
                        var savedPath = new URL(saved).pathname;
                        if (savedPath === currentPath) {
                            history.replaceState({}, '', saved);
                            loadResults(saved);
                        }
                    } catch(e) {}
                }
            }
        })();

        // Persist bfcache: reload AJAX content if page was restored from cache
        window.addEventListener('pageshow', function (e) {
            if (e.persisted) loadResults(window.location.href);
        });
    });
    </script>

    {{-- Archive confirmation modal --}}
    @auth
        @if(Auth::user()->isCompany())
        <div id="archive-modal" class="modal-backdrop" role="dialog" aria-modal="true">
            <div class="modal">
                <div class="modal-head">
                    <div class="modal-ico" id="archive-modal-ico">
                        <svg width="20" height="20" fill="none" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"
                             id="archive-modal-svg">
                            <polyline points="21 8 21 21 3 21 3 8"/>
                            <rect x="1" y="3" width="22" height="5"/>
                            <line x1="10" y1="12" x2="14" y2="12"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="modal-title" id="archive-modal-title">Archiver cette offre ?</h3>
                        <p class="modal-msg" id="archive-modal-msg">L'offre sera masquée des résultats de recherche. Vous pourrez la restaurer à tout moment.</p>
                    </div>
                </div>
                <div class="modal-foot">
                    <button type="button" class="modal-cancel" id="archive-modal-cancel">Annuler</button>
                    <button type="button" class="modal-ok" id="archive-modal-ok">Confirmer</button>
                </div>
            </div>
        </div>
        <script>
        (function () {
            var modal   = document.getElementById('archive-modal');
            var btnOk   = document.getElementById('archive-modal-ok');
            var btnNo   = document.getElementById('archive-modal-cancel');
            var ico     = document.getElementById('archive-modal-ico');
            var svg     = document.getElementById('archive-modal-svg');
            var title   = document.getElementById('archive-modal-title');
            var msg     = document.getElementById('archive-modal-msg');
            var pending = null;

            function open(form, isRestore) {
                pending = form;
                if (isRestore) {
                    ico.style.background = '#EEF2FF';
                    svg.setAttribute('stroke', '#4F46E5');
                    title.textContent = 'Désarchiver cette offre ?';
                    msg.textContent   = "L'offre sera à nouveau visible dans les résultats de recherche.";
                    btnOk.style.background = '#4F46E5';
                    btnOk.textContent = 'Désarchiver';
                } else {
                    ico.style.background = '#FEF2F2';
                    svg.setAttribute('stroke', '#DC2626');
                    title.textContent = 'Archiver cette offre ?';
                    msg.textContent   = "L'offre sera masquée des résultats de recherche. Vous pourrez la restaurer à tout moment.";
                    btnOk.style.background = '#DC2626';
                    btnOk.textContent = 'Archiver';
                }
                modal.classList.add('open');
                document.body.style.overflow = 'hidden';
            }
            function close() { pending = null; modal.classList.remove('open'); document.body.style.overflow = ''; }

            btnNo.addEventListener('click', close);
            modal.addEventListener('click', function (e) { if (e.target === modal) close(); });
            btnOk.addEventListener('click', function () { if (pending) pending.submit(); });
            document.addEventListener('keydown', function (e) { if (e.key === 'Escape') close(); });

            // Event delegation — works after AJAX re-renders of .jo-main
            document.addEventListener('submit', function (e) {
                var archiveForm = e.target.closest('.js-archive-form');
                var restoreForm = e.target.closest('.js-restore-form');
                if (!archiveForm && !restoreForm) return;
                e.preventDefault();
                open(archiveForm || restoreForm, !!restoreForm);
            });
        })();
        </script>
        @endif
    @endauth

</x-app-layout>
