<x-app-layout>
    @php
        $ciL0 = session('nav_level0') ?? ['url' => route('job-offers.my-offers'), 'label' => 'Mes offres'];
        $ciBreadcrumbs = [
            ['url' => route('job-offers.my-offers'), 'label' => 'Mes offres'],
            ['label' => $jobOffer ? Str::limit($jobOffer->title, 30) : 'Candidatures reçues'],
        ];
    @endphp
    <x-slot name="header">
        <x-page-header
            title="Candidatures reçues"
            :subtitle="$jobOffer ? 'Pour l\'offre : ' . $jobOffer->title : 'Gérez et suivez toutes les candidatures'"
            :breadcrumbs="$ciBreadcrumbs"
            icon="inbox"
        >
            <span style="display:inline-flex;align-items:center;gap:.35rem;padding:.3rem .85rem;border-radius:99px;background:#FFFBEB;color:#92400E;border:1.5px solid #FDE68A;font-size:.75rem;font-weight:700;">
                <span style="width:6px;height:6px;border-radius:50%;background:#F59E0B;flex-shrink:0;"></span>
                <span id="stat-count-pending">{{ $stats['pending'] }}</span> en attente
            </span>
            <span style="display:inline-flex;align-items:center;gap:.35rem;padding:.3rem .85rem;border-radius:99px;background:#F0FDF4;color:#166534;border:1.5px solid #A7F3D0;font-size:.75rem;font-weight:700;">
                <span style="width:6px;height:6px;border-radius:50%;background:#22C55E;flex-shrink:0;"></span>
                <span id="stat-count-accepted">{{ $stats['accepted'] }}</span> acceptés
            </span>
            <span style="display:inline-flex;align-items:center;gap:.35rem;padding:.3rem .85rem;border-radius:99px;background:#FEF2F2;color:#991B1B;border:1.5px solid #FECACA;font-size:.75rem;font-weight:700;">
                <span style="width:6px;height:6px;border-radius:50%;background:#EF4444;flex-shrink:0;"></span>
                <span id="stat-count-rejected">{{ $stats['rejected'] }}</span> refusés
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

        /* Vacancies bar */
        .vac-bar { background:var(--card); border:1px solid var(--bd); border-radius:var(--r); padding:.875rem 1.25rem; margin-bottom:1.25rem; display:flex; align-items:center; gap:.75rem; box-shadow:var(--shd); }
        .vac-label { font-size:.82rem; font-weight:600; color:var(--mid); }
        .vac-count { font-size:1.1rem; font-weight:800; color:var(--p); }

        /* Layout */
        .app-layout { display:flex; gap:1.25rem; align-items:flex-start; }
        .app-list { flex:1; min-width:0; display:flex; flex-direction:column; gap:.5rem; transition:all .25s var(--ease); }
        .app-list.narrow { width:420px; flex:none; }
        @media(max-width:900px) { .app-layout { flex-direction:column; } .app-list.narrow { width:100%; } }

        /* Application card */
        .app-card {
            background:var(--card); border:1px solid var(--bd); border-radius:var(--r);
            border-left:3px solid var(--bd); padding:1rem 1.1rem;
            transition:border-color var(--ease), box-shadow var(--ease);
        }
        .app-card:hover { border-color:#C7D2FE; box-shadow:var(--shd-h); }
        .app-card.selected { border-color:var(--p); box-shadow:0 0 0 3px var(--pr); background:#FAFBFF; }
        .app-card.status-pending,
        .app-card.status-accepted,
        .app-card.status-rejected { border-left-color:var(--p); }

        /* Card info row */
        .app-card-info { display:flex; align-items:center; gap:.75rem; cursor:pointer; }
        .app-avatar { width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:.875rem; font-weight:700; flex-shrink:0; }
        .app-name { font-size:.9rem; font-weight:800; color:var(--ink); line-height:1.25; }
        .app-job { font-size:.75rem; font-weight:600; color:var(--p); margin-top:.1rem; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
        .app-right { display:flex; align-items:center; gap:.4rem; flex-shrink:0; margin-left:auto; }
        .app-date { font-size:.72rem; color:var(--sub); white-space:nowrap; }

        /* Status badge */
        .stb { display:inline-flex; align-items:center; gap:.3rem; padding:.22rem .65rem; border-radius:99px; font-size:.68rem; font-weight:700; border:1.5px solid; white-space:nowrap; }
        .stb-dot { width:5px; height:5px; border-radius:50%; flex-shrink:0; }

        /* Action buttons row */
        .app-actions { display:flex; align-items:center; gap:.35rem; flex-wrap:wrap; padding-top:.625rem; margin-top:.625rem; border-top:1px solid #F3F4F6; }
        .btn-act { display:inline-flex; align-items:center; gap:.3rem; padding:.32rem .75rem; border-radius:7px; font-family:inherit; font-size:.73rem; font-weight:700; cursor:pointer; text-decoration:none; border:1.5px solid; transition:all var(--ease); white-space:nowrap; }
        .btn-act-ghost { background:#fff; color:var(--sub); border-color:var(--bd); }
        .btn-act-ghost:hover { border-color:var(--p); color:var(--p); background:var(--pl); }
        .btn-act-p { background:var(--pl); color:var(--p); border-color:#C7D2FE; }
        .btn-act-p:hover { background:var(--p); color:#fff; border-color:var(--p); }
        .btn-act-gr { background:#F0FDF4; color:#166534; border-color:#A7F3D0; }
        .btn-act-gr:hover { background:#DCFCE7; border-color:#4ADE80; }
        .btn-act-rd { background:#FEF2F2; color:#991B1B; border-color:#FECACA; }
        .btn-act-rd:hover { background:#FEE2E2; border-color:#F87171; }

        /* Detail panel */
        .app-detail { flex:1; min-width:0; background:var(--card); border:1px solid var(--bd); border-radius:var(--r); box-shadow:var(--shd); overflow:hidden; position:sticky; top:80px; display:none; max-height:calc(100vh - 100px); overflow-y:auto; }
        @media(max-width:900px) { .app-detail { width:100%; position:static; max-height:none; } }

        .dp-head { padding:1.125rem 1.25rem; border-bottom:1px solid var(--bd); background:var(--bg); }
        .dp-section { padding:.875rem 1.25rem; border-bottom:1px solid #F3F4F6; }
        .dp-section-title { font-size:.63rem; font-weight:800; text-transform:uppercase; letter-spacing:.08em; color:var(--sub); margin-bottom:.625rem; }
        .dp-label { display:block; font-size:.7rem; font-weight:700; color:var(--mid); margin-bottom:.3rem; }
        .dp-sel { width:100%; padding:.5rem .7rem; border:1.5px solid var(--bd); border-radius:8px; font-size:.82rem; font-family:inherit; color:var(--ink); background:var(--bg); outline:none; appearance:none; cursor:pointer; transition:border-color var(--ease),box-shadow var(--ease); }
        .dp-sel:focus { border-color:var(--p); box-shadow:0 0 0 3px var(--pr); }
        .dp-textarea { width:100%; padding:.6rem .75rem; border:1.5px solid var(--bd); border-radius:8px; font-size:.82rem; font-family:inherit; color:var(--ink); background:#fff; outline:none; resize:vertical; min-height:70px; transition:border-color var(--ease),box-shadow var(--ease); box-sizing:border-box; }
        .dp-textarea:focus { border-color:var(--p); box-shadow:0 0 0 3px var(--pr); }
        .dp-save { display:flex; align-items:center; justify-content:center; gap:.4rem; width:100%; padding:.6rem; background:var(--p); color:#fff; border:none; border-radius:8px; font-family:inherit; font-size:.82rem; font-weight:700; cursor:pointer; transition:background var(--ease),transform var(--ease); margin-top:.75rem; }
        .dp-save:hover { background:var(--pd); transform:translateY(-1px); }
        .dp-name-link:hover { color:var(--p) !important; }
        .dp-info-row { display:flex; align-items:center; gap:.6rem; padding:.3rem 0; }
        .dp-info-icon { width:22px; height:22px; border-radius:5px; background:var(--bg); border:1px solid var(--bd); display:flex; align-items:center; justify-content:center; flex-shrink:0; color:#9CA3AF; }
        .dp-info-label { font-size:.7rem; font-weight:700; color:var(--sub); margin-bottom:.05rem; }
        .dp-info-val { font-size:.82rem; font-weight:600; color:var(--ink); }
        .dp-pill { display:inline-flex; align-items:center; gap:.25rem; padding:.2rem .6rem; border-radius:99px; font-size:.72rem; font-weight:600; border:1px solid; }

        /* Detail empty state */
        .dp-empty { display:flex; flex-direction:column; align-items:center; justify-content:center; height:280px; color:var(--sub); gap:.5rem; }

        /* Empty page state */
        .empty-box { background:var(--card); border:2px dashed var(--bd); border-radius:var(--r); padding:3.5rem 2rem; text-align:center; }
        .empty-ico { width:52px; height:52px; border-radius:14px; background:var(--pl); margin:0 auto .875rem; display:flex; align-items:center; justify-content:center; }
        .empty-box h3 { font-size:1rem; font-weight:800; color:var(--ink); margin-bottom:.4rem; }
        .empty-box p { font-size:.85rem; color:var(--sub); max-width:300px; margin:0 auto; line-height:1.6; }

        /* Pagination */
        .pgn { margin-top:1.25rem; display:flex; justify-content:center; }

        /* Modal */
        .modal-bg { display:none; position:fixed; inset:0; z-index:500; background:rgba(15,23,42,.45); backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:1rem; }
        .modal-bg.open { display:flex; }
        .modal-box { background:#fff; border-radius:16px; width:100%; max-width:420px; border:1.5px solid var(--bd); box-shadow:0 20px 60px rgba(0,0,0,.18); overflow:hidden; }
        .modal-hd { display:flex; align-items:flex-start; gap:1rem; padding:1.25rem 1.5rem; border-bottom:1px solid var(--bd); }
        .modal-ico { width:42px; height:42px; border-radius:10px; flex-shrink:0; display:flex; align-items:center; justify-content:center; }
        .modal-title { font-size:1rem; font-weight:800; color:var(--ink); margin-bottom:.2rem; }
        .modal-msg { font-size:.85rem; color:var(--sub); line-height:1.55; margin:0; }
        .modal-ft { display:flex; justify-content:flex-end; gap:.75rem; padding:1rem 1.5rem; background:var(--bg); }
        .modal-cancel { padding:.53rem 1.2rem; border-radius:8px; border:1.5px solid var(--bd); background:#fff; font-size:.85rem; font-weight:600; color:var(--sub); cursor:pointer; transition:all var(--ease); font-family:inherit; }
        .modal-cancel:hover { border-color:var(--p); color:var(--p); }
        .modal-ok { padding:.53rem 1.2rem; border-radius:8px; border:none; font-size:.85rem; font-weight:700; color:#fff; cursor:pointer; transition:opacity var(--ease); font-family:inherit; }
        .modal-ok:hover { opacity:.9; }
    </style>

    <div class="pg">

        @if($jobOffer)
        <div class="vac-bar">
            <svg width="16" height="16" fill="none" stroke="#4F46E5" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <span class="vac-label">Postes vacants :</span>
            <span id="vacanciesCount" class="vac-count">{{ $jobOffer->vacancies }}</span>
        </div>
        @endif

        @if($applications->isEmpty())
            <div class="empty-box">
                <div class="empty-ico">
                    <svg width="26" height="26" fill="none" stroke="#4F46E5" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3>Aucune candidature reçue</h3>
                <p>Les candidatures apparaîtront ici dès réception.</p>
            </div>
        @else

        <div class="app-layout">

            {{-- ── Liste des candidatures ── --}}
            <div class="app-list" id="appList">

                @foreach($applications as $application)
                    @php
                        $sc = [
                            'pending'  => ['bg'=>'#FFFBEB','color'=>'#92400E','border'=>'#FDE68A','dot'=>'#F59E0B','label'=>'En attente'],
                            'accepted' => ['bg'=>'#F0FDF4','color'=>'#166534','border'=>'#A7F3D0','dot'=>'#22C55E','label'=>'Accepté'],
                            'rejected' => ['bg'=>'#FEF2F2','color'=>'#991B1B','border'=>'#FECACA','dot'=>'#EF4444','label'=>'Refusé'],
                        ];
                        $s = $sc[$application->status] ?? ['bg'=>'#F9FAFB','color'=>'#374151','border'=>'#E5E7EB','dot'=>'#9CA3AF','label'=>ucfirst($application->status)];
                        $initials = strtoupper(substr($application->user->name, 0, 2));
                        $avColors = [['#EEF2FF','#4338CA'],['#F5F3FF','#6D28D9'],['#E0F2FE','#0369A1'],['#F0FDF4','#166534']];
                        $av = $avColors[ord($initials[0]) % count($avColors)];
                        $cv = $application->cv_path ?? $application->user->cv_path ?? null;
                        $cl = $application->cover_letter_path ?? null;
                    @endphp

                    <div class="app-card status-{{ $application->status }}"
                         data-id="{{ $application->id }}">

                        {{-- Clickable info row --}}
                        <div class="app-card-info"
                             onclick="selectApp(this.closest('.app-card'), {{ $application->id }})">
                            <div class="app-avatar"
                                 style="background:{{ $av[0] }};color:{{ $av[1] }};">
                                {{ $initials }}
                            </div>
                            <div style="flex:1;min-width:0;">
                                <div class="app-name">{{ $application->user->name }}</div>
                                <div class="app-job">{{ $application->jobOffer->title }}</div>
                            </div>
                            <div class="app-right">
                                <span class="app-date">{{ $application->created_at->format('d/m/Y') }}</span>
                                <span class="stb"
                                      data-app-badge="{{ $application->id }}"
                                      style="background:{{ $s['bg'] }};color:{{ $s['color'] }};border-color:{{ $s['border'] }};">
                                    <span class="stb-dot" data-status-dot style="background:{{ $s['dot'] }};"></span>
                                    <span data-status-label>{{ $s['label'] }}</span>
                                </span>
                            </div>
                        </div>

                        {{-- ── Action Buttons (always visible) ── --}}
                        <div class="app-actions">

                            {{-- View application --}}
                            <button type="button"
                                    class="btn-act btn-act-p"
                                    onclick="selectApp(this.closest('.app-card'), {{ $application->id }})">
                                <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                                Voir la candidature
                            </button>

                            {{-- Download CV --}}
                            @if($cv)
                            <a href="{{ route('applications.download-cv', $application) }}"
                               class="btn-act btn-act-ghost">
                                <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                                    <polyline stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="7 10 12 15 17 10"/>
                                    <line x1="12" y1="15" x2="12" y2="3" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                                CV
                            </a>
                            @endif

                            {{-- Download Cover Letter --}}
                            @if($cl)
                            <a href="{{ route('applications.download-cover-letter', $application) }}"
                               class="btn-act btn-act-ghost">
                                <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Lettre
                            </a>
                            @endif

                            {{-- View profile --}}
                            <a href="{{ route('candidates.show', $application->user) }}"
                               class="btn-act btn-act-ghost">
                                <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Profil
                            </a>

                            {{-- Accept / Reject — pending only --}}
                            @if($application->status === 'pending')
                            <form class="js-status-quick-form"
                                  method="POST" action="{{ route('applications.update', $application) }}"
                                  data-app-id="{{ $application->id }}"
                                  data-confirm-variant="accepted"
                                  data-confirm-message="Accepter la candidature de {{ $application->user->name }} ?"
                                  style="display:contents;">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="accepted">
                                <button type="submit" class="btn-act btn-act-gr">
                                    <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Accepter
                                </button>
                            </form>
                            <form class="js-status-quick-form"
                                  method="POST" action="{{ route('applications.update', $application) }}"
                                  data-app-id="{{ $application->id }}"
                                  data-confirm-variant="rejected"
                                  data-confirm-message="Refuser la candidature de {{ $application->user->name }} ?"
                                  style="display:contents;">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="btn-act btn-act-rd">
                                    <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Refuser
                                </button>
                            </form>
                            @endif

                        </div>
                    </div>
                @endforeach

                @if($applications->hasPages())
                    {{ $applications->links() }}
                @endif
            </div>

            {{-- ── Panneau détail ── --}}
            <div id="detailPanel" class="app-detail">

                {{-- État vide --}}
                <div id="detailEmpty" class="dp-empty">
                    <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <span style="font-size:.85rem;">Sélectionnez une candidature</span>
                </div>

                {{-- Contenu dynamique (caché par défaut) --}}
                @foreach($applications as $application)
                    @php
                        $statusConfig = [
                            'pending'  => ['pill' => '#FFFBEB', 'color' => '#92400E', 'border' => '#FDE68A', 'dot' => '#F59E0B', 'label' => 'En attente'],
                            'accepted' => ['pill' => '#F0FDF4', 'color' => '#166534', 'border' => '#A7F3D0', 'dot' => '#22C55E', 'label' => 'Accepté'],
                            'rejected' => ['pill' => '#FEF2F2', 'color' => '#991B1B', 'border' => '#FECACA', 'dot' => '#EF4444', 'label' => 'Refusé'],
                        ];
                        $s = $statusConfig[$application->status] ?? ['pill' => '#F9FAFB', 'color' => '#374151', 'border' => '#E5E7EB', 'dot' => '#9CA3AF', 'label' => ucfirst($application->status)];
                        $initials = strtoupper(substr($application->user->name, 0, 2));
                        $avColors = [
                            ['#EEF2FF','#4338CA'], ['#F5F3FF','#6D28D9'],
                            ['#E0F2FE','#0369A1'], ['#F0FDF4','#166534'],
                        ];
                        $av = $avColors[ord($initials[0]) % count($avColors)];
                        $cv = $application->cv_path ?? $application->user->cv_path ?? null;
                        $cl = $application->cover_letter_path ?? null;
                    @endphp

                    <div id="detail-{{ $application->id }}" class="app-detail-body" style="display:none;">

                        {{-- ── Header ── --}}
                        <div class="dp-head">
                            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:.75rem;">
                                <div class="app-avatar"
                                     style="width:48px;height:48px;font-size:1.1rem;background:{{ $av[0] }};color:{{ $av[1] }};">
                                    {{ $initials }}
                                </div>
                                <span data-detail-header-badge="{{ $application->id }}"
                                      class="stb"
                                      style="background:{{ $s['pill'] }};color:{{ $s['color'] }};border-color:{{ $s['border'] }};">
                                    <span data-status-dot class="stb-dot" style="background:{{ $s['dot'] }};"></span>
                                    <span data-status-label>{{ $s['label'] }}</span>
                                </span>
                            </div>
                            <a href="{{ route('candidates.show', $application->user) }}"
                               class="dp-name-link"
                               style="display:block;font-size:1rem;font-weight:800;color:var(--ink);text-decoration:none;margin-bottom:.15rem;line-height:1.25;transition:color var(--ease);">
                                {{ $application->user->name }}
                            </a>
                            @if($application->user->domain)
                            <div style="font-size:.78rem;font-weight:600;color:var(--p);">{{ $application->user->domain }}</div>
                            @endif
                        </div>

                        {{-- ── Quick action buttons ── --}}
                        <div class="dp-section" style="display:flex;gap:.4rem;flex-wrap:wrap;">
                            @if($cv)
                            <a href="{{ route('applications.download-cv', $application) }}" class="btn-act btn-act-p">
                                <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                    <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                                    <polyline points="7 10 12 15 17 10"/>
                                    <line x1="12" y1="15" x2="12" y2="3"/>
                                </svg>
                                CV
                            </a>
                            @endif
                            @if($cl)
                            <a href="{{ route('applications.download-cover-letter', $application) }}"
                               class="btn-act btn-act-p">
                                <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                    <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Lettre de motivation
                            </a>
                            @endif
                            <a href="{{ route('candidates.show', $application->user) }}" class="btn-act btn-act-ghost">
                                <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                                Voir profil
                            </a>
                            <a href="mailto:{{ $application->user->email }}" class="btn-act btn-act-ghost">
                                <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                    <polyline points="22,6 12,13 2,6"/>
                                </svg>
                                Contacter
                            </a>
                        </div>

                        {{-- ── Informations ── --}}
                        <div class="dp-section">
                            <div class="dp-section-title">Informations</div>

                            <div class="dp-info-row">
                                <div class="dp-info-icon">
                                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                        <polyline points="22,6 12,13 2,6"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="dp-info-label">Email</div>
                                    <div class="dp-info-val">{{ $application->user->email }}</div>
                                </div>
                            </div>

                            @if($application->user->phone)
                            <div class="dp-info-row">
                                <div class="dp-info-icon">
                                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.8 19.79 19.79 0 01.22 1.18 2 2 0 012.18 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 7.09a16 16 0 006 6z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="dp-info-label">Téléphone</div>
                                    <div class="dp-info-val">{{ $application->user->phone }}</div>
                                </div>
                            </div>
                            @endif

                            <div class="dp-info-row">
                                <div class="dp-info-icon">
                                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <rect x="3" y="4" width="18" height="18" rx="2"/>
                                        <line x1="3" y1="10" x2="21" y2="10"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="dp-info-label">Candidature déposée</div>
                                    <div class="dp-info-val">{{ $application->created_at->format('d/m/Y à H:i') }}</div>
                                </div>
                            </div>

                            @if($application->user->city || $application->user->experience_years || $application->user->education_level)
                            <div style="display:flex;flex-wrap:wrap;gap:.35rem;margin-top:.75rem;">
                                @if($application->user->city)
                                <span class="dp-pill" style="background:#F9FAFB;color:#374151;border-color:#E5E7EB;">
                                    <svg width="9" height="9" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <circle cx="12" cy="11" r="3"/>
                                    </svg>
                                    {{ $application->user->city }}
                                </span>
                                @endif
                                @if($application->user->experience_years)
                                <span class="dp-pill" style="background:#EEF2FF;color:#4338CA;border-color:#C7D2FE;">
                                    {{ $application->user->experience_years }} ans d'exp.
                                </span>
                                @endif
                                @if($application->user->education_level)
                                <span class="dp-pill" style="background:#F0FDF4;color:#166534;border-color:#A7F3D0;">
                                    {{ $application->user->education_level }}
                                </span>
                                @endif
                            </div>
                            @endif
                        </div>

                        {{-- ── Gérer la candidature ── --}}
                        <div class="dp-section">
                            <div class="dp-section-title">Gérer la candidature</div>

                            <div style="margin-bottom:.75rem;">
                                <label class="dp-label">Statut</label>
                                <div id="status-field-{{ $application->id }}"
                                     data-mode="{{ $application->status === 'pending' ? 'select' : 'readonly' }}">
                                    @if($application->status === 'pending')
                                        <select name="status" form="status-form-{{ $application->id }}" class="dp-sel">
                                            <option value="pending" selected>En attente</option>
                                            <option value="accepted">Accepté</option>
                                            <option value="rejected">Refusé</option>
                                        </select>
                                    @else
                                        <div class="stb" style="background:{{ $s['pill'] }};color:{{ $s['color'] }};border-color:{{ $s['border'] }};display:inline-flex;">
                                            <span class="stb-dot" style="background:{{ $s['dot'] }};"></span>
                                            {{ $s['label'] }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Saved comment display --}}
                            <div id="comment-display-{{ $application->id }}"
                                 style="{{ $application->company_comment ? '' : 'display:none;' }}margin-bottom:.6rem;">
                                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.4rem;">
                                    <label class="dp-label" style="margin:0;">Commentaire enregistré</label>
                                    <div style="display:flex;gap:.4rem;">
                                        <button type="button"
                                                onclick="editComment({{ $application->id }})"
                                                class="btn-act"
                                                style="font-size:.7rem;padding:.22rem .55rem;">
                                            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                                <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                                <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                            </svg>
                                            Modifier
                                        </button>
                                        <button type="button"
                                                onclick="deleteComment({{ $application->id }}, '{{ route('applications.update', $application) }}')"
                                                class="btn-act btn-act-rd"
                                                style="font-size:.7rem;padding:.22rem .55rem;">
                                            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                                <polyline points="3 6 5 6 21 6"/>
                                                <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                                                <path d="M10 11v6M14 11v6"/>
                                            </svg>
                                            Supprimer
                                        </button>
                                    </div>
                                </div>
                                <div class="comment-content"
                                     style="background:#F8FAFC;border:1.5px solid #E2E8F0;border-radius:8px;padding:.65rem .75rem;font-size:.82rem;color:#334155;line-height:1.55;white-space:pre-wrap;">{{ $application->company_comment }}</div>
                            </div>

                            {{-- Comment + save form --}}
                            <form id="status-form-{{ $application->id }}"
                                  class="js-status-form"
                                  method="POST"
                                  action="{{ route('applications.update', $application) }}"
                                  data-app-id="{{ $application->id }}"
                                  data-prev-status="{{ $application->status }}">
                                @csrf @method('PATCH')
                                <div id="comment-form-wrap-{{ $application->id }}"
                                     style="{{ $application->company_comment ? 'display:none;' : '' }}">
                                    <label class="dp-label">Commentaire</label>
                                    <textarea name="company_comment"
                                              class="dp-textarea"
                                              placeholder="Ajouter ou modifier le commentaire...">{{ $application->company_comment }}</textarea>
                                    <button type="submit" class="dp-save">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                            <path d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Enregistrer les modifications
                                    </button>
                                </div>
                            </form>
                        </div>

                        {{-- ── Signalement ── --}}
                        <div class="dp-section" style="border-bottom:none;">
                            <div class="dp-section-title">Signalement</div>
                            <div style="display:flex;align-items:center;justify-content:space-between;gap:.75rem;padding:.5rem .75rem;background:#F9FAFB;border:1.5px solid var(--bd);border-left:3px solid #D1D5DB;border-radius:8px;">
                                <div>
                                    <div style="font-size:.82rem;font-weight:700;color:var(--ink);margin-bottom:.15rem;">Un problème avec ce profil ?</div>
                                    <div style="font-size:.72rem;color:var(--sub);">Vous pouvez signaler un problème si nécessaire</div>
                                </div>
                                <a href="{{ route('reports.create', $application->user) }}" class="btn-act btn-act-rd" style="flex-shrink:0;">
                                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                        <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                                        <line x1="12" y1="9" x2="12" y2="13"/>
                                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                                    </svg>
                                    Signaler
                                </a>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        @endif

    </div>

    {{-- ── Confirmation modal ── --}}
    <div id="appConfirmModal"
         style="display:none;position:fixed;inset:0;z-index:100;align-items:center;justify-content:center;padding:16px;"
         aria-modal="true" role="dialog" aria-labelledby="appConfirmTitle">
        <div id="appConfirmBackdrop"
             style="position:absolute;inset:0;background:rgba(15,23,42,.45);backdrop-filter:blur(4px);"></div>
        <div id="appConfirmContent"
             style="position:relative;width:100%;max-width:420px;background:#fff;border-radius:16px;border:1.5px solid var(--bd);box-shadow:0 20px 60px rgba(0,0,0,.18);overflow:hidden;">
            <div style="padding:1.25rem 1.5rem;display:flex;align-items:flex-start;gap:1rem;border-bottom:1px solid var(--bd);">
                <div id="appConfirmIconWrap"
                     style="width:42px;height:42px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;background:#EEF2FF;border:1px solid #C7D2FE;">
                    <svg id="appConfirmIcon" width="20" height="20" fill="none" stroke="#4F46E5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M12 16v-4M12 8h.01"/>
                    </svg>
                </div>
                <div>
                    <h3 id="appConfirmTitle" style="font-size:1rem;font-weight:800;color:var(--ink);margin:0 0 .2rem;">Confirmation</h3>
                    <p id="appConfirmMessage" style="font-size:.85rem;color:var(--sub);line-height:1.55;margin:0;"></p>
                </div>
            </div>
            <div style="display:flex;justify-content:flex-end;gap:.75rem;padding:1rem 1.5rem;background:var(--bg);">
                <button type="button" id="appConfirmCancel"
                        style="padding:.53rem 1.2rem;border-radius:8px;border:1.5px solid var(--bd);background:#fff;font-size:.85rem;font-weight:600;color:var(--sub);cursor:pointer;font-family:inherit;">
                    Annuler
                </button>
                <button type="button" id="appConfirmOk"
                        style="padding:.53rem 1.2rem;border-radius:8px;border:none;font-size:.85rem;font-weight:700;color:#fff;background:var(--p);cursor:pointer;font-family:inherit;">
                    Confirmer
                </button>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            let activeCard = null;
            let selectedId = null;

            function applyBadge(el, b) {
                if (!el || !b) return;
                el.style.background   = b.pill || b.bg || '';
                el.style.color        = b.color || '';
                el.style.borderColor  = b.border || '';
                const dot = el.querySelector('[data-status-dot]');
                if (dot) dot.style.background = b.dot || '';
                const lbl = el.querySelector('[data-status-label]');
                if (lbl) lbl.textContent = b.label || '';
            }

            const confirmModal    = document.getElementById('appConfirmModal');
            const confirmBackdrop = document.getElementById('appConfirmBackdrop');
            const confirmContent  = document.getElementById('appConfirmContent');
            const confirmMessage  = document.getElementById('appConfirmMessage');
            const confirmOk       = document.getElementById('appConfirmOk');
            const confirmCancel   = document.getElementById('appConfirmCancel');
            const confirmIconWrap = document.getElementById('appConfirmIconWrap');
            const confirmIcon     = document.getElementById('appConfirmIcon');
            let confirmResolve = null;

            function closeConfirmModal() {
                if (!confirmModal) return;
                confirmModal.style.display = 'none';
                document.body.style.overflow = '';
                if (confirmCancel) confirmCancel.style.display = '';
                if (confirmResolve) { confirmResolve(false); confirmResolve = null; }
            }

            function openConfirmModal(message, variant) {
                return new Promise(function (resolve) {
                    confirmResolve = resolve;
                    if (confirmMessage) confirmMessage.textContent = message;
                    if (confirmModal) confirmModal.style.display = 'flex';
                    document.body.style.overflow = 'hidden';
                    if (confirmCancel) confirmCancel.style.display = 'inline-block';

                    confirmOk.textContent       = 'Confirmer';
                    confirmOk.style.background  = '#4F46E5';
                    confirmContent.style.borderColor = '#E5E7EB';

                    if (variant === 'accepted' || variant === 'accept') {
                        confirmOk.textContent = 'Accepter';
                        confirmOk.style.background = '#16A34A';
                        confirmContent.style.borderColor = '#4ADE80';
                        if (confirmIconWrap) { confirmIconWrap.style.background = '#F0FDF4'; confirmIconWrap.style.borderColor = '#A7F3D0'; }
                        if (confirmIcon) { confirmIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>'; confirmIcon.setAttribute('stroke','#16A34A'); }
                    } else if (variant === 'rejected' || variant === 'reject') {
                        confirmOk.textContent = 'Refuser';
                        confirmOk.style.background = '#DC2626';
                        confirmContent.style.borderColor = '#FECACA';
                        if (confirmIconWrap) { confirmIconWrap.style.background = '#FEF2F2'; confirmIconWrap.style.borderColor = '#FECACA'; }
                        if (confirmIcon) { confirmIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>'; confirmIcon.setAttribute('stroke','#DC2626'); }
                    } else if (variant === 'delete') {
                        confirmOk.textContent = 'Supprimer';
                        confirmOk.style.background = '#DC2626';
                        confirmContent.style.borderColor = '#FECACA';
                        if (confirmIconWrap) { confirmIconWrap.style.background = '#FEF2F2'; confirmIconWrap.style.borderColor = '#FECACA'; }
                        if (confirmIcon) { confirmIcon.innerHTML = '<polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/>'; confirmIcon.setAttribute('stroke','#DC2626'); }
                    } else if (variant === 'pending') {
                        confirmOk.textContent = 'Remettre en attente';
                        confirmOk.style.background = '#F59E0B';
                        confirmContent.style.borderColor = '#FDE68A';
                        if (confirmIconWrap) { confirmIconWrap.style.background = '#FFFBEB'; confirmIconWrap.style.borderColor = '#FDE68A'; }
                        if (confirmIcon) { confirmIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>'; confirmIcon.setAttribute('stroke','#F59E0B'); }
                    }
                });
            }

            function showStatusInfoModal(message, variant) {
                if (!confirmModal) return;
                if (confirmCancel) confirmCancel.style.display = 'none';
                if (confirmMessage) confirmMessage.textContent = message;
                if (variant === 'rejected') {
                    confirmOk.textContent = 'OK'; confirmOk.style.background = '#DC2626';
                    confirmContent.style.borderColor = '#FECACA';
                } else {
                    confirmOk.textContent = 'OK'; confirmOk.style.background = '#4F46E5';
                }
                confirmOk.onclick = function () { closeConfirmModal(); confirmOk.onclick = null; };
                confirmModal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }

            if (confirmBackdrop) confirmBackdrop.addEventListener('click', closeConfirmModal);
            if (confirmCancel) confirmCancel.addEventListener('click', closeConfirmModal);
            if (confirmOk) {
                confirmOk.addEventListener('click', function () {
                    if (confirmResolve) { const r = confirmResolve; confirmResolve = null; r(true); }
                    if (confirmModal) confirmModal.style.display = 'none';
                    document.body.style.overflow = '';
                });
            }
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && confirmModal && confirmModal.style.display === 'flex') closeConfirmModal();
            });

            window.deleteComment = async function(appId, url) {
                const ok = await openConfirmModal('Voulez-vous vraiment supprimer ce commentaire ?', 'delete');
                if (!ok) return;
                await patchApplicationStatus(url, null, appId, '');
                const panel = document.getElementById('detail-' + appId);
                if (panel) {
                    const txt = panel.querySelector('textarea[name="company_comment"]');
                    if (txt) txt.value = '';
                }
                const formWrap = document.getElementById('comment-form-wrap-' + appId);
                if (formWrap) formWrap.style.display = 'block';
            };

            window.editComment = function(appId) {
                const display = document.getElementById('comment-display-' + appId);
                const formWrap = document.getElementById('comment-form-wrap-' + appId);
                if (display) display.style.display = 'none';
                if (formWrap) {
                    formWrap.style.display = 'block';
                    const ta = formWrap.querySelector('textarea');
                    if (ta) {
                        const content = display ? display.querySelector('.comment-content') : null;
                        if (content && content.textContent.trim()) ta.value = content.textContent.trim();
                        ta.focus();
                        ta.setSelectionRange(ta.value.length, ta.value.length);
                    }
                }
            };

            async function patchApplicationStatus(url, status, appId, comment) {
                try {
                    let finalStatus = status;
                    if (finalStatus === null) {
                        const panel = document.getElementById('detail-' + appId);
                        const sel = panel ? panel.querySelector('select[name="status"]') : null;
                        finalStatus = sel ? sel.value : 'pending';
                    }
                    const res = await fetch(url, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrf || '',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify({ status: finalStatus, company_comment: comment }),
                    });
                    const data = await res.json().catch(() => ({}));
                    if (!res.ok) {
                        const msg = data.message || (data.errors && Object.values(data.errors).flat().join(' ')) || 'Erreur';
                        if (res.status === 422 && msg.includes('vacants')) {
                            showStatusInfoModal(msg, 'rejected');
                        } else {
                            window.alert(msg);
                        }
                        return null;
                    }

                    const b = data.badge;
                    if (b) {
                        applyBadge(document.querySelector('[data-app-badge="' + appId + '"]'), b);
                        applyBadge(document.querySelector('[data-detail-header-badge="' + appId + '"]'), b);
                    }

                    // Update card border color based on new status
                    const card = document.querySelector('.app-card[data-id="' + appId + '"]');
                    if (card && data.status) {
                        card.classList.remove('status-pending','status-accepted','status-rejected');
                        card.classList.add('status-' + data.status);
                        // Show/hide Accept/Reject buttons based on new status
                        updateCardButtons(card, data.status);
                    }

                    updateStatusField(appId, data.badge, data.status);

                    const panel = document.getElementById('detail-' + appId);
                    if (panel) {
                        const form = panel.querySelector('.js-status-form');
                        if (form) form.setAttribute('data-prev-status', data.status);
                        const displayZone = document.getElementById('comment-display-' + appId);
                        const formWrap = document.getElementById('comment-form-wrap-' + appId);
                        if (displayZone) {
                            if (data.company_comment) {
                                displayZone.style.display = 'block';
                                const content = displayZone.querySelector('.comment-content');
                                if (content) content.textContent = data.company_comment;
                            } else {
                                displayZone.style.display = 'none';
                            }
                        }
                        if (formWrap) formWrap.style.display = data.company_comment ? 'none' : 'block';
                    }

                    if (data.stats) {
                        const sp = document.getElementById('stat-count-pending');
                        const sa = document.getElementById('stat-count-accepted');
                        const sr = document.getElementById('stat-count-rejected');
                        if (sp) sp.textContent = data.stats.pending;
                        if (sa) sa.textContent = data.stats.accepted;
                        if (sr) sr.textContent = data.stats.rejected;
                    }
                    const vacCount = document.getElementById('vacanciesCount');
                    if (vacCount && data.vacancies !== undefined) vacCount.textContent = data.vacancies;

                    return data;
                } catch (e) {
                    window.alert('Erreur réseau');
                }
            }

            function updateCardButtons(card, status) {
                card.querySelectorAll('.js-status-quick-form').forEach(function(form) {
                    form.style.display = status === 'pending' ? 'contents' : 'none';
                });
            }

            function updateStatusField(appId, badgeData, newStatus) {
                const statusField = document.getElementById('status-field-' + appId);
                if (!statusField) return;
                const b = badgeData;
                if (newStatus === 'pending') {
                    statusField.setAttribute('data-mode', 'select');
                    statusField.innerHTML =
                        '<select name="status" form="status-form-' + appId + '" class="dp-sel">' +
                        '<option value="pending" selected>En attente</option>' +
                        '<option value="accepted">Accepté</option>' +
                        '<option value="rejected">Refusé</option>' +
                        '</select>';
                } else {
                    const bg     = b ? (b.pill || b.bg || '#F9FAFB') : '#F9FAFB';
                    const color  = b ? (b.color  || '#374151') : '#374151';
                    const border = b ? (b.border  || '#E5E7EB') : '#E5E7EB';
                    const dot    = b ? (b.dot    || '#9CA3AF') : '#9CA3AF';
                    const label  = b ? (b.label  || newStatus) : newStatus;
                    statusField.setAttribute('data-mode', 'readonly');
                    statusField.innerHTML =
                        '<div class="stb" style="background:' + bg + ';color:' + color + ';border-color:' + border + ';display:inline-flex;">' +
                        '<span class="stb-dot" style="background:' + dot + ';"></span>' +
                        label + '</div>';
                }
            }

            window.selectApp = function (el, id) {
                const detailPanel = document.getElementById('detailPanel');
                const detailEmpty = document.getElementById('detailEmpty');
                const appList     = document.getElementById('appList');
                const sid = String(id);

                if (activeCard === el && selectedId === sid) {
                    if (activeCard) { activeCard.classList.remove('selected'); }
                    activeCard = null; selectedId = null;
                    document.querySelectorAll('.app-detail-body').forEach(function (d) { d.style.display = 'none'; });
                    if (detailEmpty) detailEmpty.style.display = 'flex';
                    if (detailPanel) detailPanel.style.display = 'none';
                    if (appList) { appList.style.width = ''; appList.classList.remove('narrow'); }
                    return;
                }

                if (activeCard) activeCard.classList.remove('selected');
                el.classList.add('selected');
                activeCard = el; selectedId = sid;

                if (detailPanel) detailPanel.style.display = 'block';
                if (detailEmpty) detailEmpty.style.display = 'none';
                if (appList) appList.classList.add('narrow');

                document.querySelectorAll('.app-detail-body').forEach(function (d) { d.style.display = 'none'; });
                const target = document.getElementById('detail-' + id);
                if (target) target.style.display = 'block';
            };

            document.querySelectorAll('.js-status-form').forEach(function (form) {
                form.addEventListener('submit', async function (e) {
                    e.preventDefault();
                    const appId       = form.getAttribute('data-app-id');
                    const prevStatus  = form.getAttribute('data-prev-status');
                    const select      = document.querySelector('select[name="status"][form="' + form.id + '"]');
                    const commentArea = form.querySelector('textarea[name="company_comment"]');
                    const status  = select ? select.value : prevStatus;
                    const comment = commentArea ? commentArea.value : null;
                    if (select && status !== prevStatus) {
                        const label = select.options[select.selectedIndex].text;
                        const ok = await openConfirmModal('Enregistrer les modifications pour : ' + label + ' ?', status);
                        if (!ok) return;
                    }
                    const res = await patchApplicationStatus(form.action, status, appId, comment);
                    if (res && commentArea) commentArea.value = '';
                });
            });

            document.querySelectorAll('.js-status-quick-form').forEach(function (form) {
                form.addEventListener('submit', async function (e) {
                    e.preventDefault();
                    const msg     = form.getAttribute('data-confirm-message');
                    const variant = form.getAttribute('data-confirm-variant') || 'accept';
                    if (msg) {
                        const ok = await openConfirmModal(msg, variant);
                        if (!ok) return;
                    }
                    const appId  = form.getAttribute('data-app-id');
                    const status = form.querySelector('input[name="status"]').value;
                    await patchApplicationStatus(form.action, status, appId, null);
                });
            });

        })();
    </script>
</x-app-layout>
