<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Mes candidatures" subtitle="Suivez l'avancement de toutes vos candidatures" icon="document"
        >
            <span style="display:inline-flex;align-items:center;gap:.35rem;padding:.3rem .85rem;border-radius:99px;background:#4F46E5;color:#fff;font-size:.75rem;font-weight:700;">
                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
                </svg>
                {{ $applications->total() }} candidature{{ $applications->total() > 1 ? 's' : '' }}
            </span>
        </x-page-header>
    </x-slot>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --p:      #2563EB;
            --p-dark: #1D4ED8;
            --p-soft: #EEF2FF;
            --ink:    #4F46E5;
            --ink2:   #4338CA;
            --muted:  #64748B;
            --hint:   #94A3B8;
            --border: #E2E8F0;
            --surface:#F8FAFC;
            --card:   #FFFFFF;
            --r:      14px;
            --r-sm:   8px;
            --t:      .18s cubic-bezier(.4,0,.2,1);
            --font-d: 'Syne', sans-serif;
            --font-b: 'Plus Jakarta Sans', sans-serif;

            --c-pending-bg:  #FFFBEB; --c-pending-text: #92400E; --c-pending-bdr: #FDE68A; --c-pending-dot: #F59E0B;
            --c-accepted-bg: #F0FDF4; --c-accepted-text:#166534; --c-accepted-bdr: #BBF7D0; --c-accepted-dot: #22C55E;
            --c-rejected-bg: #FEF2F2; --c-rejected-text:#991B1B; --c-rejected-bdr: #FECACA; --c-rejected-dot: #EF4444;
        }

        *, *::before, *::after { box-sizing: border-box; }

        .ac-page {
            max-width: 80rem;
            margin: 0 auto;
            padding: 1.75rem 1.5rem 5rem;
            font-family: var(--font-b);
        }
        @media (max-width:640px) { .ac-page { padding: 1rem 1rem 4rem; } }

        /* ── Stats strip ────────────────────────────── */
        .ac-stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: .75rem;
            margin-bottom: 1.5rem;
        }
        @media (max-width:700px) { .ac-stats { grid-template-columns: repeat(2, 1fr); } }

        .ac-stat {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--r);
            padding: .9rem 1rem;
            display: flex;
            align-items: center;
            gap: .75rem;
            cursor: pointer;
            transition: border-color var(--t), box-shadow var(--t), transform var(--t);
        }
        .ac-stat:hover { border-color: #BFDBFE; transform: translateY(-1px); box-shadow: 0 4px 14px rgba(37,99,235,.07); }
        .ac-stat.active { border-color: var(--p); box-shadow: 0 0 0 3px rgba(37,99,235,.1); }

        .ac-stat-icon {
            width: 36px; height: 36px; border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .ac-stat-icon.all      { background: var(--ink); }
        .ac-stat-icon.pending  { background: #FFFBEB; border: 1px solid #FDE68A; }
        .ac-stat-icon.accepted { background: #F0FDF4; border: 1px solid #BBF7D0; }
        .ac-stat-icon.rejected { background: #FEF2F2; border: 1px solid #FECACA; }

        .ac-stat-num {
            font-family: var(--font-b);
            font-size: 1.35rem; font-weight: 800;
            color: var(--ink); line-height: 1;
        }
        .ac-stat-lbl {
            font-size: .7rem; font-weight: 600;
            color: var(--muted); margin-top: .15rem;
            white-space: nowrap;
        }

        /* ── Grid ───────────────────────────────────── */
        .ac-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0,1fr));
            gap: 1.25rem;
        }
        @media (max-width:1100px) { .ac-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width:640px)  { .ac-grid { grid-template-columns: 1fr; } }

        /* ── Card ───────────────────────────────────── */
        @keyframes cardIn {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .ac-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--r);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            animation: cardIn .25s ease both;
            transition: border-color var(--t), box-shadow var(--t), transform var(--t);
            position: relative;
        }
        .ac-card:hover {
            border-color: #BFDBFE;
            box-shadow: 0 6px 22px rgba(37,99,235,.09), 0 1px 4px rgba(0,0,0,.04);
            transform: translateY(-2px);
        }
        .ac-card:nth-child(1) { animation-delay:.03s; }
        .ac-card:nth-child(2) { animation-delay:.07s; }
        .ac-card:nth-child(3) { animation-delay:.11s; }
        .ac-card:nth-child(4) { animation-delay:.15s; }
        .ac-card:nth-child(5) { animation-delay:.19s; }
        .ac-card:nth-child(6) { animation-delay:.23s; }

        /* Status top accent line */
        .ac-card-accent {
            height: 3px;
            width: 100%;
        }
        .ac-card.pending  .ac-card-accent { background: #F59E0B; }
        .ac-card.accepted .ac-card-accent { background: #22C55E; }
        .ac-card.rejected .ac-card-accent { background: #EF4444; }

        /* Card body */
        .ac-card-body { padding: 1.25rem 1.25rem 1rem; flex: 1; }

        /* Header: logo + company + badge */
        .ac-card-header {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .ac-logo {
            width: 42px; height: 42px; border-radius: 10px;
            border: 1px solid var(--border); object-fit: cover;
            background: #fff; flex-shrink: 0;
        }
        .ac-logo-ph {
            width: 42px; height: 42px; border-radius: 10px;
            background: var(--ink);
            display: flex; align-items: center; justify-content: center;
            font-family: var(--font-d); font-size: .9rem; font-weight: 800;
            color: #fff; flex-shrink: 0;
        }

        .ac-company-name {
            font-size: .85rem; font-weight: 700;
            color: var(--p); line-height: 1.3;
        }
        .ac-company-loc {
            display: flex; align-items: center; gap: .3rem;
            font-size: .75rem; color: var(--hint); margin-top: .25rem;
        }

        /* Status badge */
        .ac-badge {
            display: inline-flex; align-items: center; gap: .3rem;
            padding: .2rem .6rem; border-radius: 99px;
            font-size: .65rem; font-weight: 700;
            white-space: nowrap; border: 1px solid; margin-left: auto;
            flex-shrink: 0;
        }
        .ac-badge-dot { width: 5px; height: 5px; border-radius: 50%; }
        .ac-badge.pending  { background: var(--c-pending-bg);  color: var(--c-pending-text);  border-color: var(--c-pending-bdr); }
        .ac-badge.accepted { background: var(--c-accepted-bg); color: var(--c-accepted-text); border-color: var(--c-accepted-bdr); }
        .ac-badge.rejected { background: var(--c-rejected-bg); color: var(--c-rejected-text); border-color: var(--c-rejected-bdr); }
        .ac-badge.pending  .ac-badge-dot { background: var(--c-pending-dot); }
        .ac-badge.accepted .ac-badge-dot { background: var(--c-accepted-dot); }
        .ac-badge.rejected .ac-badge-dot { background: var(--c-rejected-dot); }

        /* Job title */
        .ac-job-title {
            font-family: var(--font-b);
            font-size: 1.05rem; font-weight: 800;
            color: var(--ink); text-decoration: none;
            display: block; line-height: 1.4;
            margin-bottom: .85rem;
            transition: color var(--t);
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .ac-job-title:hover { color: var(--p); }

        /* Meta pills */
        .ac-meta { display: flex; flex-wrap: wrap; gap: .3rem; margin-bottom: .5rem; }
        .ac-pill {
            display: inline-flex; align-items: center; gap: .3rem;
            padding: .25rem .65rem; border-radius: 8px;
            font-size: .75rem; font-weight: 600;
            background: var(--surface); color: var(--ink2);
            border: 1px solid var(--border);
        }
        .ac-pill svg { width: 10px; height: 10px; color: var(--hint); flex-shrink: 0; }
        .ac-pill.salary { background: var(--p-soft); color: var(--p); border-color: #BFDBFE; font-weight: 700; }
        .ac-pill.salary svg { color: var(--p); }

        /* Company comment */
        .ac-comment {
            margin-top: .85rem;
            padding: .8rem 1rem;
            background: #F8FAFC;
            border: 1px solid var(--border);
            border-left: 3px solid var(--p);
            border-radius: 0 var(--r-sm) var(--r-sm) 0;
        }
        .ac-comment-label {
            font-size: .68rem; font-weight: 800; text-transform: uppercase;
            letter-spacing: .08em; color: var(--p); margin-bottom: .35rem;
            display: flex; align-items: center; gap: .4rem;
        }
        .ac-comment-text { font-size: .8rem; color: var(--ink2); line-height: 1.6; }

        /* Status banner */
        .ac-status-banner {
            display: flex; align-items: center; gap: .6rem;
            padding: .6rem 1.25rem;
            font-size: .78rem; font-weight: 600;
            border-top: 1px solid transparent;
        }
        .ac-status-banner.accepted { background: var(--c-accepted-bg); border-color: var(--c-accepted-bdr); color: var(--c-accepted-text); }
        .ac-status-banner.rejected { background: var(--c-rejected-bg); border-color: var(--c-rejected-bdr); color: var(--c-rejected-text); }

        /* Card footer */
        .ac-card-foot {
            display: flex; align-items: center; justify-content: space-between;
            padding: .65rem 1.25rem;
            border-top: 1px solid var(--surface);
            background: var(--surface);
            gap: .5rem;
        }

        .ac-date {
            display: flex; align-items: center; gap: .3rem;
            font-size: .68rem; color: var(--hint); font-weight: 500;
        }

        .ac-btn-view {
            display: inline-flex; align-items: center; gap: .4rem;
            padding: .45rem 1rem; border-radius: var(--r-sm);
            background: var(--ink); color: #fff; border: none;
            font-family: var(--font-b); font-size: .8rem; font-weight: 700;
            text-decoration: none; cursor: pointer;
            transition: background var(--t), transform var(--t);
        }
        .ac-btn-view:hover { background: var(--ink2); transform: translateY(-1px); }

        /* ── Empty state ────────────────────────────── */
        .ac-empty {
            grid-column: 1 / -1;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--r);
            padding: 4rem 2rem;
            text-align: center;
        }
        .ac-empty-icon {
            width: 56px; height: 56px; border-radius: 14px;
            background: var(--ink);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1.25rem;
        }
        .ac-empty h3 { font-family: var(--font-d); font-size: 1.05rem; font-weight: 800; color: var(--ink); margin: 0 0 .4rem; }
        .ac-empty p  { font-size: .8rem; color: var(--muted); margin: 0 auto 1.5rem; max-width: 320px; line-height: 1.6; }
        .ac-empty-btn {
            display: inline-flex; align-items: center; gap: .5rem;
            padding: .8rem 1.5rem; border-radius: 10px;
            background: var(--ink); color: #fff;
            font-family: var(--font-b); font-size: .9rem; font-weight: 800;
            text-decoration: none; transition: background var(--t);
        }
        .ac-empty-btn:hover { background: var(--ink2); }
    </style>

    <div class="ac-page">

        {{-- ── Stats strip / filter ─────────────────── --}}
        @php
            $totalCount    = $applications->total();
            $pendingCount  = $applications->getCollection()->where('status','pending')->count();
            $acceptedCount = $applications->getCollection()->where('status','accepted')->count();
            $rejectedCount = $applications->getCollection()->where('status','rejected')->count();
        @endphp

        <div class="ac-stats">
            <div class="ac-stat active" onclick="filterCards('all', this)" data-filter="all">
                <div class="ac-stat-icon all">
                    <svg width="16" height="16" fill="none" stroke="white" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
                    </svg>
                </div>
                <div>
                    <div class="ac-stat-num">{{ $totalCount }}</div>
                    <div class="ac-stat-lbl">Total</div>
                </div>
            </div>

            <div class="ac-stat" onclick="filterCards('pending', this)" data-filter="pending">
                <div class="ac-stat-icon pending">
                    <svg width="15" height="15" fill="none" stroke="#F59E0B" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <div class="ac-stat-num">{{ $pendingCount }}</div>
                    <div class="ac-stat-lbl">En attente</div>
                </div>
            </div>

            <div class="ac-stat" onclick="filterCards('accepted', this)" data-filter="accepted">
                <div class="ac-stat-icon accepted">
                    <svg width="15" height="15" fill="none" stroke="#22C55E" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div>
                    <div class="ac-stat-num">{{ $acceptedCount }}</div>
                    <div class="ac-stat-lbl">Acceptés</div>
                </div>
            </div>

            <div class="ac-stat" onclick="filterCards('rejected', this)" data-filter="rejected">
                <div class="ac-stat-icon rejected">
                    <svg width="15" height="15" fill="none" stroke="#EF4444" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <div>
                    <div class="ac-stat-num">{{ $rejectedCount }}</div>
                    <div class="ac-stat-lbl">Refusés</div>
                </div>
            </div>
        </div>

        {{-- ── Grid ────────────────────────────────── --}}
        <div class="ac-grid" id="ac-grid">

            @forelse($applications as $application)
                @php
                    $statusMap = [
                        'pending'  => ['label' => 'En attente', 'cls' => 'pending'],
                        'accepted' => ['label' => 'Accepté',    'cls' => 'accepted'],
                        'rejected' => ['label' => 'Refusé',     'cls' => 'rejected'],
                    ];
                    $s = $statusMap[$application->status] ?? ['label' => ucfirst($application->status), 'cls' => 'pending'];
                    $typeMap = ['full-time'=>'Temps plein','part-time'=>'Temps partiel','remote'=>'Télétravail','freelance'=>'Freelance','internship'=>'Stage'];
                @endphp

                <div class="ac-card {{ $s['cls'] }}" data-status="{{ $application->status }}"
                     style="cursor:pointer;"
                     onclick="if(!event.target.closest('a')&&!event.target.closest('button')&&!event.target.closest('form')) window.location.href='{{ route('job-offers.show', $application->jobOffer) }}'">

                    {{-- Top accent --}}
                    <div class="ac-card-accent"></div>

                    <div class="ac-card-body">

                        {{-- Header: logo + company + badge --}}
                        <div class="ac-card-header">
                            <img src="{{ $application->jobOffer->company->logo_path ? $application->jobOffer->company->logo_url : 'https://ui-avatars.com/api/?name='.urlencode($application->jobOffer->company->name).'&background=EEF2FF&color=4F46E5&bold=true' }}"
                                 alt="{{ $application->jobOffer->company->name }}"
                                 class="ac-logo">

                            <div style="flex:1;min-width:0;">
                                <div class="ac-company-name">{{ $application->jobOffer->company->name }}</div>
                                <div class="ac-company-loc">
                                    <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $application->jobOffer->location }}
                                </div>
                            </div>

                            <span class="ac-badge {{ $s['cls'] }}">
                                <span class="ac-badge-dot"></span>
                                {{ $s['label'] }}
                            </span>
                        </div>

                        {{-- Job title --}}
                        <a href="{{ route('job-offers.show', $application->jobOffer) }}" class="ac-job-title">
                            {{ $application->jobOffer->title }}
                        </a>

                        {{-- Meta --}}
                        <div class="ac-meta">
                            <span class="ac-pill">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                {{ $typeMap[$application->jobOffer->type] ?? $application->jobOffer->type }}
                            </span>
                            @if($application->jobOffer->salary)
                                <span class="ac-pill salary">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ number_format($application->jobOffer->salary, 0, ',', ' ') }} TND
                                </span>
                            @endif
                        </div>

                    </div>

                    {{-- Status banner --}}
                    @if($application->status === 'accepted')
                        <div class="ac-status-banner accepted">
                            <svg width="13" height="13" fill="none" stroke="#22C55E" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            Félicitations ! Votre candidature a été acceptée.
                        </div>
                    @elseif($application->status === 'rejected')
                        <div class="ac-status-banner rejected">
                            <svg width="13" height="13" fill="none" stroke="#EF4444" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Cette candidature n'a pas été retenue.
                        </div>
                    @endif

                    {{-- Footer --}}
                    <div class="ac-card-foot">
                        <span class="ac-date">
                            <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" stroke-width="2"/>
                                <line x1="16" y1="2" x2="16" y2="6" stroke-width="2" stroke-linecap="round"/>
                                <line x1="8"  y1="2" x2="8"  y2="6" stroke-width="2" stroke-linecap="round"/>
                                <line x1="3"  y1="10" x2="21" y2="10" stroke-width="2"/>
                            </svg>
                            {{ $application->created_at->format('d/m/Y') }}
                        </span>
                        <a href="{{ route('job-offers.show', $application->jobOffer) }}" class="ac-btn-view">
                            Voir l'offre
                            <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>

            @empty
                <div class="ac-empty">
                    <div class="ac-empty-icon">
                        <svg width="24" height="24" fill="none" stroke="#60A5FA" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <h3>Aucune candidature pour le moment</h3>
                    <p>Explorez les offres disponibles et postulez dès maintenant pour commencer votre parcours.</p>
                    <a href="{{ route('job-offers.index') }}" class="ac-empty-btn">
                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Découvrir les offres
                    </a>
                </div>
            @endforelse

        </div>

        {{-- Pagination --}}
        @if($applications->hasPages())
            {{ $applications->links() }}
        @endif

    </div>

    <script>
        function filterCards(status, el) {
            document.querySelectorAll('.ac-stat').forEach(s => s.classList.remove('active'));
            el.classList.add('active');
            document.querySelectorAll('.ac-card').forEach(card => {
                card.style.display = (status === 'all' || card.dataset.status === status) ? 'flex' : 'none';
            });
        }
    </script>

</x-app-layout>