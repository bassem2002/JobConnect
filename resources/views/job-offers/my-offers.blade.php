<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Mes offres d'emploi" subtitle="Gérez vos offres publiées" icon="briefcase"
        >
            <a href="{{ route('job-offers.create') }}"
               style="display:inline-flex;align-items:center;gap:.4rem;padding:.5rem 1.1rem;background:#4F46E5;color:#fff;border-radius:8px;font-size:.82rem;font-weight:700;text-decoration:none;transition:background .15s;"
               onmouseover="this.style.background='#3730A3'" onmouseout="this.style.background='#4F46E5'">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Publier une offre
            </a>
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
        .stats-row { display:grid; grid-template-columns:repeat(auto-fit,minmax(160px,1fr)); gap:1rem; margin-bottom:1.5rem; }
        .stat-c { background:var(--card); border:1px solid var(--bd); border-radius:var(--r); padding:1.25rem 1.5rem; box-shadow:var(--shd); }
        .stat-l { font-size:.68rem; font-weight:800; text-transform:uppercase; letter-spacing:.07em; color:var(--sub); margin-bottom:.3rem; }
        .stat-v { font-size:1.75rem; font-weight:800; color:var(--p); line-height:1; }
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
        .stb { display:inline-flex; align-items:center; gap:.35rem; padding:.25rem .75rem; border-radius:99px; font-size:.72rem; font-weight:700; border:1.5px solid; white-space:nowrap; }
        .stb-dot { width:6px; height:6px; border-radius:50%; flex-shrink:0; }
        .co-logo { width:34px;height:34px;border-radius:9px;border:1.5px solid var(--bd);object-fit:cover;flex-shrink:0;background:#fff; }
        .co-logo-ph { width:34px;height:34px;border-radius:9px;background:var(--pl);border:1.5px solid #C7D2FE;display:flex;align-items:center;justify-content:center;font-size:.9rem;font-weight:800;color:var(--p);flex-shrink:0; }
        .btn-p { display:inline-flex; align-items:center; gap:.4rem; padding:.45rem .95rem; background:var(--p); color:#fff; border:none; border-radius:8px; font-family:inherit; font-size:.78rem; font-weight:700; cursor:pointer; text-decoration:none; transition:background var(--ease),transform var(--ease),box-shadow var(--ease); white-space:nowrap; }
        .btn-p:hover { background:var(--pd); transform:translateY(-1px); box-shadow:0 4px 12px var(--pr); }
        .btn-i { display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; border:1.5px solid var(--bd); background:#fff; color:var(--sub); cursor:pointer; transition:all var(--ease); text-decoration:none; }
        .btn-i:hover { border-color:var(--p); color:var(--p); background:var(--pl); }
        .btn-i.dg:hover { border-color:#FECACA; color:#DC2626; background:#FEF2F2; }
        .btn-i.ok { border-color:#BFDBFE; color:#1D4ED8; background:#EFF6FF; }
        .btn-i.ok:hover { border-color:#3B82F6; color:#1D4ED8; background:#DBEAFE; }
        .pgn { padding:1.25rem 1.5rem; border-top:1px solid var(--bd); }
        .empty-box { padding:4rem 2rem; text-align:center; }
        .empty-ico { width:52px; height:52px; border-radius:14px; background:var(--pl); margin:0 auto .875rem; display:flex; align-items:center; justify-content:center; }
        .empty-box h3 { font-size:1rem; font-weight:800; color:var(--ink); margin-bottom:.4rem; }
        .empty-box p { font-size:.85rem; color:var(--sub); margin-bottom:1.25rem; max-width:300px; margin-left:auto; margin-right:auto; line-height:1.6; }
        .uc-cat { display:inline-flex;align-items:center;gap:.3rem;padding:.12rem .55rem .12rem .2rem;border-radius:99px;font-size:.66rem;font-weight:700;background:var(--pl);color:var(--p);border:1px solid #C7D2FE;letter-spacing:.02em; }
        .uc-cat-img { width:18px;height:18px;border-radius:50%;object-fit:cover;flex-shrink:0;border:1.5px solid #C7D2FE;background:#fff; }
        .uc-cat-ico { flex-shrink:0; }

        /* app badge */
        .app-badge { display:inline-flex; align-items:center; gap:.3rem; padding:.25rem .75rem; border-radius:99px; font-size:.75rem; font-weight:700; background:var(--pl); color:var(--p); border:1px solid #C7D2FE; text-decoration:none; transition:all var(--ease); white-space:nowrap; }
        .app-badge:hover { background:var(--p); color:#fff; border-color:var(--p); }

        /* Sortable headers */
        .th-sort { display:inline-flex; align-items:center; gap:.35rem; cursor:pointer; text-decoration:none; color:inherit; white-space:nowrap; user-select:none; }
        .th-sort:hover { color:var(--p); }
        .th-sort svg { opacity:.45; transition:opacity var(--ease); flex-shrink:0; }
        .th-sort:hover svg, .th-sort.active svg { opacity:1; }

        /* Status dropdown */
        .th-status { position:relative; }
        .th-status-btn { display:inline-flex; align-items:center; gap:.35rem; cursor:pointer; background:none; border:none; font:inherit; font-size:.68rem; font-weight:800; text-transform:uppercase; letter-spacing:.07em; color:var(--sub); padding:0; user-select:none; white-space:nowrap; }
        .th-status-btn:hover { color:var(--p); }
        .th-status-btn svg { opacity:.45; transition:opacity var(--ease); }
        .th-status-btn:hover svg, .th-status-btn.active svg { opacity:1; }
        .status-drop { display:none; position:absolute; top:calc(100% + 4px); right:0; z-index:200; background:#fff; border:1.5px solid var(--bd); border-radius:10px; box-shadow:0 8px 24px rgba(0,0,0,.12); min-width:160px; overflow:hidden; }
        .status-drop.open { display:block; }
        .status-drop a { display:flex; align-items:center; gap:.55rem; padding:.58rem .9rem; font-size:.8rem; font-weight:600; color:var(--ink); text-decoration:none; transition:background var(--ease); white-space:nowrap; }
        .status-drop a:hover { background:var(--bg); }
        .status-drop a.active { background:var(--pl); color:var(--p); font-weight:700; }
        .status-drop-sep { height:1px; background:var(--bd); }
        .sd-dot { width:8px; height:8px; border-radius:50%; flex-shrink:0; }

        /* Modal */
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
    </style>

    <div class="pg">

        {{-- Stats --}}
        <div class="stats-row">
            <div class="stat-c">
                <div class="stat-l">Offres actives</div>
                <div class="stat-v">{{ $summaryStats['active_count'] }}</div>
            </div>
            <div class="stat-c">
                <div class="stat-l">Candidatures recues</div>
                <div class="stat-v">{{ $summaryStats['applications_total'] }}</div>
            </div>
        </div>

        {{-- Table card --}}
        <div class="tbl-card">
            <div class="tbl-card-head">
                <p class="tbl-card-count">
                    <strong>{{ $jobOffers->total() }}</strong>
                    offre{{ $jobOffers->total() > 1 ? 's' : '' }} publiee{{ $jobOffers->total() > 1 ? 's' : '' }}
                </p>
            </div>

            <div class="tbl-wrap">
                <table class="tbl">
                    <thead>
                        @php
                            $nextDir  = fn($col) => ($sort === $col && $dir === 'asc') ? 'desc' : 'asc';
                            $sortUrl  = fn($col) => route('job-offers.my-offers', array_filter(['sort' => $col, 'dir' => $nextDir($col), 'status' => $status]));
                            $sortIco  = function($col) use ($sort, $dir) {
                                if ($sort !== $col) return '<svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4M17 8v12m0 0l4-4m-4 4l-4-4"/></svg>';
                                return $dir === 'asc'
                                    ? '<svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/></svg>'
                                    : '<svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>';
                            };
                            $statusUrl = fn($s) => route('job-offers.my-offers', array_filter(['sort' => $sort, 'dir' => $dir, 'status' => $s === $status ? null : $s]));
                            $statusOptions = [
                                null              => ['label' => 'Tous',       'dot' => '#9CA3AF'],
                                'pending_validation' => ['label' => 'En attente', 'dot' => '#F59E0B'],
                                'refused'         => ['label' => 'Refusée',    'dot' => '#EF4444'],
                                'open'            => ['label' => 'Ouverte',    'dot' => '#22C55E'],
                                'archived'        => ['label' => 'Archivée',   'dot' => '#9CA3AF'],
                            ];
                        @endphp
                        <tr>
                            <th>
                                <a href="{{ $sortUrl('title') }}" class="th-sort {{ $sort === 'title' ? 'active' : '' }}">
                                    Titre de l'offre {!! $sortIco('title') !!}
                                </a>
                            </th>
                            <th>
                                <a href="{{ $sortUrl('date') }}" class="th-sort {{ $sort === 'date' ? 'active' : '' }}">
                                    Publication {!! $sortIco('date') !!}
                                </a>
                            </th>
                            <th>
                                <a href="{{ $sortUrl('applications') }}" class="th-sort {{ $sort === 'applications' ? 'active' : '' }}">
                                    Candidatures {!! $sortIco('applications') !!}
                                </a>
                            </th>
                            <th class="th-status">
                                <button type="button" class="th-status-btn {{ $status ? 'active' : '' }}" onclick="document.getElementById('status-drop').classList.toggle('open');event.stopPropagation();">
                                    Statut
                                    @if($status)
                                        <span class="sd-dot" style="background:{{ $statusOptions[$status]['dot'] ?? '#9CA3AF' }};"></span>
                                    @endif
                                    <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div class="status-drop" id="status-drop">
                                    @foreach($statusOptions as $val => $opt)
                                        @if(!is_null($val))
                                            <div class="status-drop-sep"></div>
                                        @endif
                                        <a href="{{ $statusUrl($val) }}" class="{{ $status === $val || ($val === null && !$status) ? 'active' : '' }}">
                                            <span class="sd-dot" style="background:{{ $opt['dot'] }};"></span>
                                            {{ $opt['label'] }}
                                        </a>
                                    @endforeach
                                </div>
                            </th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jobOffers as $offer)
                            @php
                                $sc = [
                                    'open'               => ['label'=>'Ouverte',    'dot'=>'#22C55E', 'bg'=>'#F0FDF4', 'color'=>'#166534', 'bd'=>'#A7F3D0'],
                                    'closed'             => ['label'=>'Cloturee',   'dot'=>'#EF4444', 'bg'=>'#FEF2F2', 'color'=>'#991B1B', 'bd'=>'#FECACA'],
                                    'archived'           => ['label'=>'Archivee',   'dot'=>'#9CA3AF', 'bg'=>'#F9FAFB', 'color'=>'#374151', 'bd'=>'#E5E7EB'],
                                    'pending_validation' => ['label'=>'En attente', 'dot'=>'#F59E0B', 'bg'=>'#FFFBEB', 'color'=>'#92400E', 'bd'=>'#FDE68A'],
                                    'refused'            => ['label'=>'Refusee',    'dot'=>'#EF4444', 'bg'=>'#FEF2F2', 'color'=>'#991B1B', 'bd'=>'#FECACA'],
                                ][$offer->status] ?? ['label'=>ucfirst($offer->status),'dot'=>'#9CA3AF','bg'=>'#F9FAFB','color'=>'#374151','bd'=>'#E5E7EB'];
                                $isArchived = $offer->status === 'archived';
                            @endphp
                            <tr style="cursor:pointer;"
                                onclick="if(!event.target.closest('a')&&!event.target.closest('button')&&!event.target.closest('form')) window.location.href='{{ route('applications.index', ['job_offer_id' => $offer->id]) }}'">
                                <td>
                                    <div style="display:flex;align-items:flex-start;gap:.75rem;">
                                        {{-- Logo entreprise --}}
                                        <img src="{{ $offer->company->logo_path ? $offer->company->logo_url : 'https://ui-avatars.com/api/?name='.urlencode($offer->company->name).'&background=EEF2FF&color=4F46E5&bold=true' }}"
                                             alt="{{ $offer->company->name }}"
                                             class="co-logo" style="margin-top:.1rem;">
                                        <div>
                                            <a href="{{ route('job-offers.show', $offer) }}"
                                               class="tbl-title">{{ $offer->title }}</a>
                                            <div class="tbl-sub">
                                                <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                                {{ $offer->location }}
                                            </div>
                                            @if(optional($offer->offerCategory)->name)
                                                <span class="uc-cat" style="margin-top:.3rem;">
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
                                </td>

                                <td>
                                    <span style="font-size:.82rem;color:var(--sub);font-weight:500;">
                                        {{ $offer->created_at->format('d/m/Y') }}
                                    </span>
                                </td>

                                <td>
                                    <a href="{{ route('applications.index', ['job_offer_id' => $offer->id]) }}"
                                       class="app-badge">
                                        <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                            <circle cx="9" cy="7" r="4"/>
                                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                        </svg>
                                        {{ $offer->applications_count }} recue{{ $offer->applications_count > 1 ? 's' : '' }}
                                    </a>
                                </td>

                                <td>
                                    <span class="stb"
                                          style="background:{{ $sc['bg'] }};color:{{ $sc['color'] }};border-color:{{ $sc['bd'] }};">
                                        <span class="stb-dot" style="background:{{ $sc['dot'] }};"></span>
                                        {{ $sc['label'] }}
                                    </span>
                                </td>

                                <td>
                                    <div style="display:inline-flex;align-items:center;gap:.4rem;">
                                        <a href="{{ route('job-offers.show', $offer) }}"
                                           class="btn-i" title="Voir l'offre">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>

                                        <a href="{{ route('job-offers.edit', $offer) }}"
                                           class="btn-i" title="Modifier">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                            </svg>
                                        </a>

                                        @if($isArchived)
                                            <form method="POST" action="{{ route('job-offers.unarchive', $offer) }}"
                                                  class="js-restore-form" data-title="{{ $offer->title }}" style="display:contents;">
                                                @csrf
                                                <button type="submit" class="btn-i ok" title="Desarchiver">
                                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <polyline stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="21 8 21 21 3 21 3 8"/>
                                                        <rect x="1" y="3" width="22" height="5" stroke-width="2"/>
                                                        <polyline stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="10 12 12 14 14 12"/>
                                                        <line x1="12" y1="8" x2="12" y2="14" stroke-width="2"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('job-offers.archive', $offer) }}"
                                                  class="js-archive-form" data-title="{{ $offer->title }}" style="display:contents;">
                                                @csrf
                                                <button type="submit" class="btn-i dg" title="Archiver">
                                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <polyline stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="21 8 21 21 3 21 3 8"/>
                                                        <rect x="1" y="3" width="22" height="5" stroke-width="2"/>
                                                        <line x1="10" y1="12" x2="14" y2="12" stroke-width="2"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-box">
                                        <div class="empty-ico">
                                            <svg width="26" height="26" fill="none" stroke="#4F46E5" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        @if($status)
                                            <h3>Aucune offre ne correspond aux filtres sélectionnés</h3>
                                        @else
                                            <h3>Aucune offre publiée</h3>
                                            <p>Commencez par créer votre première offre d'emploi.</p>
                                            <a href="{{ route('job-offers.create') }}" class="btn-p" style="display:inline-flex;">
                                                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                                </svg>
                                                Créer ma première offre
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($jobOffers->hasPages())
                {{ $jobOffers->links() }}
            @endif
        </div>
    </div>

    {{-- Confirmation modal --}}
    <div id="confirm-modal" class="modal-backdrop" role="dialog" aria-modal="true">
        <div class="modal">
            <div class="modal-head">
                <div class="modal-ico" id="modal-ico"></div>
                <div>
                    <h3 class="modal-title" id="modal-title"></h3>
                    <p class="modal-msg" id="modal-msg"></p>
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" class="modal-cancel" id="modal-cancel">Annuler</button>
                <button type="button" class="modal-ok" id="modal-ok"></button>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('click', function() {
        var d = document.getElementById('status-drop');
        if (d) d.classList.remove('open');
    });
    </script>
    <script>
    (function () {
        var modal   = document.getElementById('confirm-modal');
        var ico     = document.getElementById('modal-ico');
        var title   = document.getElementById('modal-title');
        var msg     = document.getElementById('modal-msg');
        var btnOk   = document.getElementById('modal-ok');
        var btnNo   = document.getElementById('modal-cancel');
        var pending = null;

        function open(form, variant) {
            pending = form;
            var isArchive = variant === 'archive';
            var color = isArchive ? '#DC2626' : '#4F46E5';
            var bg    = isArchive ? '#FEF2F2' : '#EEF2FF';

            ico.style.background = bg;
            ico.innerHTML = isArchive
                ? '<svg width="20" height="20" fill="none" stroke="' + color + '" stroke-width="2" viewBox="0 0 24 24"><polyline stroke-linecap="round" stroke-linejoin="round" points="21 8 21 21 3 21 3 8"/><rect x="1" y="3" width="22" height="5"/><line x1="10" y1="12" x2="14" y2="12"/></svg>'
                : '<svg width="20" height="20" fill="none" stroke="' + color + '" stroke-width="2" viewBox="0 0 24 24"><polyline stroke-linecap="round" stroke-linejoin="round" points="21 8 21 21 3 21 3 8"/><rect x="1" y="3" width="22" height="5"/><polyline stroke-linecap="round" stroke-linejoin="round" points="10 12 12 14 14 12"/><line x1="12" y1="8" x2="12" y2="14"/></svg>';

            title.textContent = isArchive ? 'Archiver cette offre ?' : 'Restaurer cette offre ?';
            msg.textContent   = isArchive
                ? "L'offre sera masquee des resultats de recherche. Vous pourrez la restaurer a tout moment."
                : "L'offre sera de nouveau visible par les candidats.";

            btnOk.textContent       = isArchive ? 'Archiver' : 'Restaurer';
            btnOk.style.background  = color;
            modal.classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function close() {
            pending = null;
            modal.classList.remove('open');
            document.body.style.overflow = '';
        }

        btnNo.onclick  = close;
        modal.onclick  = function (e) { if (e.target === modal) close(); };
        btnOk.onclick  = function () { if (pending) pending.submit(); };
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape') close(); });

        document.querySelectorAll('.js-archive-form').forEach(function (f) {
            f.onsubmit = function (e) { e.preventDefault(); open(f, 'archive'); };
        });
        document.querySelectorAll('.js-restore-form').forEach(function (f) {
            f.onsubmit = function (e) { e.preventDefault(); open(f, 'restore'); };
        });
    })();
    </script>
    <script>
    (function () {
        var KEY = 'my_offers_url';
        if (window.location.search) {
            sessionStorage.setItem(KEY, window.location.href);
        } else {
            var referrerPath = null;
            try { referrerPath = document.referrer ? new URL(document.referrer).pathname : null; } catch(e) {}
            if (referrerPath && referrerPath !== window.location.pathname) {
                var saved = sessionStorage.getItem(KEY);
                if (saved) { try { if (new URL(saved).pathname === window.location.pathname) window.location.replace(saved); } catch(e) {} }
            }
        }
    })();
    </script>
</x-app-layout>
