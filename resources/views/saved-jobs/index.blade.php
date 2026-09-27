<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Mes offres sauvegardées" subtitle="Retrouvez toutes vos offres mises de côté" icon="bookmark"
        >
            <span style="display:inline-flex;align-items:center;gap:.35rem;padding:.3rem .85rem;border-radius:99px;background:#4F46E5;color:#fff;font-size:.75rem;font-weight:700;">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                </svg>
                {{ $savedJobs->total() }} offre{{ $savedJobs->total() > 1 ? 's' : '' }}
            </span>
        </x-page-header>
    </x-slot>

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
        .flt-sel, .flt-inp { width:100%; padding:.48rem .7rem; border:1.5px solid var(--bd); border-radius:8px; font-size:.8rem; font-family:inherit; font-weight:500; color:var(--ink); background:var(--bg); outline:none; appearance:none; cursor:pointer; transition:border-color var(--ease),box-shadow var(--ease),background var(--ease); }
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
        .grid-2 { display:grid; grid-template-columns:repeat(2,1fr); gap:1rem; }
        @media(max-width:860px){ .grid-2 { grid-template-columns:1fr; } }
        .grid-3 { display:grid; grid-template-columns:repeat(3,1fr); gap:1.125rem; }
        @media(max-width:1100px){ .grid-3 { grid-template-columns:repeat(2,1fr); } }
        @media(max-width:640px){ .grid-3 { grid-template-columns:1fr; } }
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
        .btn-i.ok:hover { border-color:#A7F3D0; color:#166534; background:#F0FDF4; }
        .stb { display:inline-flex; align-items:center; gap:.35rem; padding:.25rem .75rem; border-radius:99px; font-size:.72rem; font-weight:700; border:1.5px solid; white-space:nowrap; }
        .stb-dot { width:6px; height:6px; border-radius:50%; flex-shrink:0; }
        .empty-box { grid-column:1/-1; background:var(--card); border:2px dashed var(--bd); border-radius:var(--r); padding:3.5rem 2rem; text-align:center; }
        .empty-ico { width:52px; height:52px; border-radius:14px; background:var(--pl); margin:0 auto .875rem; display:flex; align-items:center; justify-content:center; }
        .empty-box h3 { font-size:1rem; font-weight:800; color:var(--ink); margin-bottom:.4rem; }
        .empty-box p { font-size:.85rem; color:var(--sub); margin-bottom:1.25rem; max-width:300px; margin-left:auto; margin-right:auto; line-height:1.6; }
        .tbl-card { background:var(--card); border-radius:var(--r); border:1px solid var(--bd); box-shadow:var(--shd); overflow:hidden; }
        .tbl-card-head { display:flex; align-items:center; justify-content:space-between; padding:1rem 1.5rem; border-bottom:1px solid var(--bd); background:var(--bg); flex-wrap:wrap; gap:.5rem; }
        .tbl-card-count { font-size:.875rem; font-weight:700; color:var(--sub); }
        .tbl-card-count strong { color:var(--p); }
        .tbl-wrap { overflow-x:auto; }
        .tbl { width:100%; border-collapse:collapse; }
        .tbl thead tr { border-bottom:1px solid var(--bd); background:var(--bg); }
        .tbl th { padding:.875rem 1.25rem; text-align:left; font-size:.68rem; font-weight:800; text-transform:uppercase; letter-spacing:.07em; color:var(--sub); white-space:nowrap; }
        .tbl th:last-child { text-align:right; }
        .tbl tbody tr { border-bottom:1px solid var(--bd); transition:background var(--ease); }
        .tbl tbody tr:hover { background:var(--pl); }
        .tbl tbody tr:last-child { border-bottom:none; }
        .tbl td { padding:1rem 1.25rem; vertical-align:middle; font-size:.875rem; color:var(--ink); }
        .tbl td:last-child { text-align:right; }
        .tbl-title { font-weight:800; color:var(--ink); text-decoration:none; display:block; transition:color var(--ease); font-size:.9rem; }
        .tbl-title:hover { color:var(--p); }
        .tbl-sub { font-size:.78rem; color:var(--sub); margin-top:.2rem; display:flex; align-items:center; gap:.3rem; }
        .stats-row { display:grid; grid-template-columns:repeat(auto-fit,minmax(160px,1fr)); gap:1rem; margin-bottom:1.5rem; }
        .stat-c { background:var(--card); border:1px solid var(--bd); border-radius:var(--r); padding:1.25rem 1.5rem; box-shadow:var(--shd); }
        .stat-l { font-size:.68rem; font-weight:800; text-transform:uppercase; letter-spacing:.07em; color:var(--sub); margin-bottom:.3rem; }
        .stat-v { font-size:1.75rem; font-weight:800; color:var(--p); line-height:1; }
        .pgn { padding:1.25rem 1.5rem; border-top:1px solid var(--bd); }
        .cand-avatar { width:44px; height:44px; border-radius:10px; background:var(--pl); border:1.5px solid #C7D2FE; display:flex; align-items:center; justify-content:center; font-size:1rem; font-weight:800; color:var(--p); flex-shrink:0; }
        .modal-backdrop { display:none; position:fixed; inset:0; z-index:500; background:rgba(15,23,42,.45); backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:1rem; }
        .modal-backdrop.open { display:flex; }
        .modal { background:#fff; border-radius:16px; width:100%; max-width:420px; border:1.5px solid var(--bd); box-shadow:0 20px 60px rgba(0,0,0,.18); overflow:hidden; }
        .modal-head { display:flex; align-items:flex-start; gap:1rem; padding:1.25rem 1.5rem; border-bottom:1px solid var(--bd); }
        .modal-ico { width:42px; height:42px; border-radius:10px; flex-shrink:0; display:flex; align-items:center; justify-content:center; }
        .modal-title { font-size:1rem; font-weight:800; color:var(--ink); margin-bottom:.2rem; }
        .modal-msg { font-size:.85rem; color:var(--sub); line-height:1.55; margin:0; }
        .modal-foot { display:flex; justify-content:flex-end; gap:.75rem; padding:1rem 1.5rem; background:var(--bg); }
        .modal-cancel { padding:.53rem 1.2rem; border-radius:8px; border:1.5px solid var(--bd); background:#fff; font-size:.85rem; font-weight:600; color:var(--sub); cursor:pointer; transition:all var(--ease); font-family:inherit; }
        .modal-cancel:hover { border-color:var(--p); color:var(--p); }
        .modal-ok { padding:.53rem 1.2rem; border-radius:8px; border:none; font-size:.85rem; font-weight:700; color:#fff; cursor:pointer; transition:opacity var(--ease); font-family:inherit; }
        .modal-ok:hover { opacity:.9; }

        /* Toast */
        .sv-toast { position:fixed; bottom:24px; right:24px; background:#22C55E; color:#fff; padding:.75rem 1.25rem; border-radius:12px; font-size:.85rem; font-weight:600; box-shadow:0 8px 20px rgba(0,0,0,.15); z-index:999; display:flex; align-items:center; gap:.6rem; opacity:0; transform:translateY(12px); transition:all .3s; pointer-events:none; }
        .sv-toast.show { opacity:1; transform:translateY(0); }
        .uc-cat { display:inline-flex;align-items:center;gap:.3rem;padding:.12rem .55rem .12rem .2rem;border-radius:99px;font-size:.67rem;font-weight:700;background:var(--pl);color:var(--p);border:1px solid #C7D2FE;letter-spacing:.02em; }
        .uc-cat-img { width:18px;height:18px;border-radius:50%;object-fit:cover;flex-shrink:0;border:1.5px solid #C7D2FE;background:#fff; }
        .uc-cat-ico { flex-shrink:0; }
    </style>

    <div class="pg">

        {{-- Results bar --}}
        <div class="res-bar">
            <p class="res-count">
                <strong>{{ $savedJobs->total() }}</strong>
                offre{{ $savedJobs->total() > 1 ? 's' : '' }} sauvegardée{{ $savedJobs->total() > 1 ? 's' : '' }}
            </p>
        </div>

        {{-- Grid --}}
        <div class="grid-3" id="saved-grid">
            @forelse($savedJobs as $savedJob)
                @php
                    $typeLabels = [
                        'full-time'  => ['label' => 'Temps plein',   'cls' => 'gr'],
                        'part-time'  => ['label' => 'Temps partiel', 'cls' => 'am'],
                        'remote'     => ['label' => 'Télétravail',   'cls' => 'hi'],
                        'freelance'  => ['label' => 'Freelance',     'cls' => 'hi'],
                        'internship' => ['label' => 'Stage',         'cls' => ''],
                    ];
                    $tm = $typeLabels[$savedJob->jobOffer->type] ?? ['label' => ucfirst($savedJob->jobOffer->type), 'cls' => ''];
                    $initial = strtoupper(substr($savedJob->jobOffer->company->name, 0, 1));
                @endphp

                <div class="uc" data-job-id="{{ $savedJob->jobOffer->id }}"
                     style="cursor:pointer;"
                     onclick="if(!event.target.closest('a')&&!event.target.closest('button')&&!event.target.closest('form')) window.location.href='{{ route('job-offers.show', $savedJob->jobOffer) }}'">
                    <div class="uc-body">
                        <div class="uc-co-row">
                            <img src="{{ $savedJob->jobOffer->company->logo_path ? Storage::url($savedJob->jobOffer->company->logo_path) : 'https://ui-avatars.com/api/?name='.urlencode($savedJob->jobOffer->company->name).'&background=EEF2FF&color=4F46E5&bold=true' }}"
                                 alt="{{ $savedJob->jobOffer->company->name }}"
                                 class="uc-logo">
                            <div class="uc-co-info">
                                <div class="uc-co-name">{{ $savedJob->jobOffer->company->name }}</div>
                                <div class="uc-co-loc">
                                    <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $savedJob->jobOffer->location }}
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('job-offers.show', $savedJob->jobOffer) }}" class="uc-title">
                            {{ $savedJob->jobOffer->title }}
                        </a>

                        @if($savedJob->jobOffer->description)
                            <div class="uc-desc">
                                {{ Str::limit(strip_tags($savedJob->jobOffer->description), 110) }}
                            </div>
                        @endif

                        <div class="uc-meta">
                            @if(optional($savedJob->jobOffer->offerCategory)->name)
                                <span class="uc-cat">
                                    @if($savedJob->jobOffer->offerCategory->image_url)
                                        <img src="{{ $savedJob->jobOffer->offerCategory->image_url }}" alt="{{ $savedJob->jobOffer->offerCategory->name }}" class="uc-cat-img">
                                    @else
                                        <svg class="uc-cat-ico" width="9" height="9" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z"/></svg>
                                    @endif
                                    {{ $savedJob->jobOffer->offerCategory->name }}
                                </span>
                            @endif
                            <span class="uc-pill {{ $tm['cls'] }}">
                                <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                {{ $tm['label'] }}
                            </span>
                            @if($savedJob->jobOffer->salary)
                                <span class="uc-pill hi">
                                    <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ number_format($savedJob->jobOffer->salary, 0, ',', ' ') }} TND
                                </span>
                            @endif
                            @if($savedJob->jobOffer->expiration_date)
                                <span class="uc-pill">
                                    <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Exp. {{ \Carbon\Carbon::parse($savedJob->jobOffer->expiration_date)->format('d/m/Y') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="uc-foot">
                        <span class="uc-date">
                            <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ $savedJob->created_at->format('d/m/Y') }}
                        </span>
                        <div style="display:flex;align-items:center;gap:.5rem;">
                            <a href="{{ route('job-offers.show', $savedJob->jobOffer) }}" class="btn-p">
                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                                Voir l'offre
                            </a>
                            <form method="POST" action="{{ route('saved-jobs.toggle', $savedJob->jobOffer) }}"
                                  class="unsave-form" style="display:contents;">
                                @csrf
                                <button type="submit" class="btn-d">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <polyline stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="3 6 5 6 21 6"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 6l-1 14H6L5 6"/>
                                    </svg>
                                    Retirer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            @empty
                <div class="empty-box">
                    <div class="empty-ico">
                        <svg width="26" height="26" fill="none" stroke="#4F46E5" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                        </svg>
                    </div>
                    <h3>Aucune offre sauvegardée</h3>
                    <p>Commencez a sauvegarder les offres qui vous interessent pour les retrouver facilement.</p>
                    <a href="{{ route('job-offers.index') }}" class="btn-p" style="display:inline-flex;">
                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="8"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35"/>
                        </svg>
                        Decouvrir les offres
                    </a>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($savedJobs->hasPages())
            <div style="margin-top:2rem;display:flex;justify-content:center;">
                {{ $savedJobs->links() }}
            </div>
        @endif
    </div>

    {{-- Toast --}}
    <div id="sv-toast" class="sv-toast">
        <svg width="16" height="16" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
        Offre retiree des favoris
    </div>

    <script>
    (function () {
        function showToast(msg) {
            const t = document.getElementById('sv-toast');
            t.childNodes[t.childNodes.length - 1].textContent = ' ' + msg;
            t.classList.add('show');
            setTimeout(() => t.classList.remove('show'), 3000);
        }

        document.querySelectorAll('.unsave-form').forEach(function (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                const card  = this.closest('.uc');
                const token = this.querySelector('[name="_token"]').value;
                fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({})
                })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (data.status === 'removed') {
                        card.style.transition = 'all .3s cubic-bezier(.4,0,.2,1)';
                        card.style.opacity    = '0';
                        card.style.transform  = 'translateY(-8px) scale(.97)';
                        setTimeout(function () {
                            card.remove();
                            const grid = document.getElementById('saved-grid');
                            if (!grid.querySelector('.uc')) {
                                grid.innerHTML = '<div class="empty-box"><div class="empty-ico"><svg width="26" height="26" fill="none" stroke="#4F46E5" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg></div><h3>Aucune offre sauvegardee</h3><p>Parcourez les offres disponibles pour en sauvegarder de nouvelles.</p><a href="{{ route('job-offers.index') }}" class="btn-p" style="display:inline-flex;margin-top:.5rem;">Decouvrir les offres</a></div>';
                            }
                            showToast('Offre retiree des favoris');
                        }, 300);
                    }
                })
                .catch(function () { showToast('Une erreur est survenue'); });
            });
        });
    })();
    </script>
</x-app-layout>
