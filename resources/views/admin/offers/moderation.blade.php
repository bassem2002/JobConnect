<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Modération des offres" subtitle="Gérez l'ensemble des offres publiées sur la plateforme" icon="briefcase">
            <span style="display:inline-flex;align-items:center;gap:.35rem;padding:.3rem .85rem;border-radius:99px;background:#FFFBEB;color:#92400E;border:1.5px solid #FDE68A;font-size:.75rem;font-weight:700;">
                <span style="width:6px;height:6px;border-radius:50%;background:#F59E0B;flex-shrink:0;"></span>
                {{ $pendingCount }} en attente
            </span>
        </x-page-header>
    </x-slot>

    <style>
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
            --ease: .18s cubic-bezier(.4,0,.2,1);
        }
        .pg { max-width:80rem; margin:0 auto; padding:2rem 1.5rem; }
        @media(max-width:640px){ .pg { padding:1rem; } }

        .tbl-card { background:var(--card); border-radius:var(--r); border:1px solid var(--bd); box-shadow:var(--shd); overflow:hidden; }

        /* ── toolbar ── */
        .tbl-toolbar {
            display:flex; align-items:center; justify-content:space-between;
            padding:.85rem 1.25rem; border-bottom:1px solid var(--bd);
            background:linear-gradient(135deg,#EFF6FF 0%,#F8FBFF 100%);
            flex-wrap:wrap; gap:.65rem;
        }
        .tbl-count { font-size:.92rem; font-weight:800; color:var(--ink); white-space:nowrap; }
        .tbl-count strong { color:var(--p); }

        /* column dropdown */
        .th-col { position:relative; }
        .th-col-btn { display:inline-flex; align-items:center; gap:.35rem; cursor:pointer; background:none; border:none; font:inherit; font-size:.72rem; font-weight:800; text-transform:uppercase; letter-spacing:.08em; color:var(--p); padding:0; user-select:none; white-space:nowrap; }
        .th-col-btn:hover { color:var(--pd); }
        .th-col-btn svg { opacity:.5; transition:opacity var(--ease); }
        .th-col-btn:hover svg, .th-col-btn.active svg { opacity:1; }
        .col-drop { display:none; position:absolute; top:calc(100% + 4px); left:0; z-index:200; background:#fff; border:1.5px solid var(--bd); border-radius:10px; box-shadow:0 8px 24px rgba(0,0,0,.12); min-width:160px; overflow:hidden; }
        .col-drop.open { display:block; }
        .col-drop a { display:flex; align-items:center; gap:.55rem; padding:.6rem .9rem; font-size:.8rem; font-weight:600; color:var(--ink); text-decoration:none; transition:background var(--ease); }
        .col-drop a:hover { background:var(--bg); }
        .col-drop a.active { background:var(--pl); color:var(--p); font-weight:700; }
        .col-drop .rd-dot { width:8px; height:8px; border-radius:50%; flex-shrink:0; }
        .col-drop-sep { height:1px; background:var(--bd); margin:0; }

        /* ── table ── */
        .tbl-wrap { overflow-x:auto; }
        .tbl { width:100%; border-collapse:collapse; min-width:760px; }
        .tbl thead tr { border-bottom:1px solid var(--bd); background:#EFF6FF; }
        .tbl th { padding:.85rem 1rem; text-align:left; font-size:.72rem; font-weight:800; text-transform:uppercase; letter-spacing:.08em; color:var(--p); white-space:nowrap; }
        .tbl th:last-child { text-align:right; }
        .tbl tbody tr { border-bottom:1px solid var(--bd); transition:background var(--ease); cursor:pointer; }
        .tbl tbody tr:hover { background:#EFF6FF; }
        .tbl tbody tr:last-child { border-bottom:none; }
        .tbl td { padding:.9rem 1rem; vertical-align:middle; font-size:.9rem; color:var(--ink); }
        .tbl td:last-child { text-align:right; }

        /* sortable column header */
        .th-sort {
            display:inline-flex; align-items:center; gap:.35rem;
            text-decoration:none; color:var(--p);
            font-size:.72rem; font-weight:800; text-transform:uppercase; letter-spacing:.08em;
            transition:opacity var(--ease);
        }
        .th-sort:hover { opacity:.75; }
        .th-sort svg { flex-shrink:0; }

        .tbl-title { font-weight:800; color:var(--ink); text-decoration:none; display:block; transition:color var(--ease); font-size:.95rem; }
        .tbl-title:hover { color:var(--pd); }
        .tbl-sub { font-size:.78rem; color:var(--sub); margin-top:.2rem; display:flex; align-items:center; gap:.3rem; }

        .stb { display:inline-flex; align-items:center; gap:.35rem; padding:.32rem .85rem; border-radius:99px; font-size:.72rem; font-weight:700; border:1.5px solid; white-space:nowrap; }
        .stb-dot { width:6px; height:6px; border-radius:50%; flex-shrink:0; }
        .co-logo { width:34px;height:34px;border-radius:9px;border:1.5px solid var(--bd);object-fit:cover;flex-shrink:0;background:#fff; }

        .btn-p { display:inline-flex; align-items:center; gap:.4rem; padding:.45rem .95rem; background:var(--p); color:#fff; border:none; border-radius:8px; font-family:inherit; font-size:.78rem; font-weight:700; cursor:pointer; text-decoration:none; transition:background var(--ease),transform var(--ease),box-shadow var(--ease); white-space:nowrap; }
        .btn-p:hover { background:var(--pd); transform:translateY(-1px); box-shadow:0 4px 12px var(--pr); }
        .btn-ok { display:inline-flex; align-items:center; gap:.35rem; padding:.38rem .85rem; background:#F0FDF4; color:#166534; border:1.5px solid #A7F3D0; border-radius:8px; font-family:inherit; font-size:.75rem; font-weight:700; cursor:pointer; transition:all var(--ease); white-space:nowrap; }
        .btn-ok:hover { background:#DCFCE7; border-color:#4ADE80; }
        .btn-rd { display:inline-flex; align-items:center; gap:.35rem; padding:.38rem .85rem; background:#FEF2F2; color:#991B1B; border:1.5px solid #FECACA; border-radius:8px; font-family:inherit; font-size:.75rem; font-weight:700; cursor:pointer; transition:all var(--ease); white-space:nowrap; }
        .btn-rd:hover { background:#FEE2E2; border-color:#F87171; }
        .btn-g { display:inline-flex; align-items:center; gap:.4rem; padding:.43rem .875rem; background:#fff; color:var(--sub); border:1.5px solid var(--bd); border-radius:8px; font-family:inherit; font-size:.78rem; font-weight:600; cursor:pointer; text-decoration:none; transition:all var(--ease); white-space:nowrap; }
        .btn-g:hover { border-color:var(--p); color:var(--p); background:var(--pl); }

        .uc-cat { display:inline-flex;align-items:center;gap:.3rem;padding:.12rem .55rem .12rem .2rem;border-radius:99px;font-size:.66rem;font-weight:700;background:var(--pl);color:var(--p);border:1px solid #C7D2FE;letter-spacing:.02em; }
        .uc-cat-img { width:18px;height:18px;border-radius:50%;object-fit:cover;flex-shrink:0;border:1.5px solid #C7D2FE;background:#fff; }
        .uc-cat-ico { flex-shrink:0; }

        .empty-box { padding:4rem 2rem; text-align:center; }
        .empty-ico { width:52px;height:52px;border-radius:14px;background:var(--pl);margin:0 auto .875rem;display:flex;align-items:center;justify-content:center; }
        .empty-box h3 { font-size:1rem;font-weight:800;color:var(--ink);margin-bottom:.4rem; }
        .empty-box p { font-size:.85rem;color:var(--sub);max-width:300px;margin:0 auto 1.25rem;line-height:1.6; }

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

    @php
        /* helpers to build sort URLs */
        function sortUrl(string $col, string $currentSort, string $currentDir, string $currentStatus): string {
            $newDir = ($currentSort === $col && $currentDir === 'asc') ? 'desc' : 'asc';
            $params = array_filter(['sort' => $col, 'dir' => $newDir, 'status' => $currentStatus]);
            return request()->url() . '?' . http_build_query($params);
        }
        function sortIcon(string $col, string $currentSort, string $currentDir): string {
            if ($currentSort !== $col) {
                return '<svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="opacity:.45"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16V4m0 0L3 8m4-4l4 4M17 8v12m0 0l4-4m-4 4l-4-4"/></svg>';
            }
            if ($currentDir === 'asc') {
                return '<svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>';
            }
            return '<svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>';
        }
        function statusFilterUrl(string $statusVal, string $currentSort, string $currentDir): string {
            $params = array_filter(['sort' => $currentSort !== 'date' ? $currentSort : null, 'dir' => $currentDir !== 'desc' ? $currentDir : null, 'status' => $statusVal ?: null]);
            return request()->url() . ($params ? '?' . http_build_query($params) : '');
        }

        $statusLabels = [
            'pending_validation' => ['label' => 'En attente',  'bg' => '#FFFBEB', 'color' => '#92400E', 'border' => '#FDE68A', 'dot' => '#F59E0B'],
            'open'               => ['label' => 'Validée',     'bg' => '#F0FDF4', 'color' => '#166534', 'border' => '#A7F3D0', 'dot' => '#22C55E'],
            'refused'            => ['label' => 'Refusée',     'bg' => '#FEF2F2', 'color' => '#991B1B', 'border' => '#FECACA', 'dot' => '#DC2626'],
            'closed'             => ['label' => 'Fermée',      'bg' => '#F1F5F9', 'color' => '#475569', 'border' => '#CBD5E1', 'dot' => '#94A3B8'],
        ];
    @endphp

    <div class="pg">

        @if(session('success'))
            <div style="display:flex;align-items:center;gap:.65rem;padding:.85rem 1.1rem;background:#F0FDF4;border:1.5px solid #A7F3D0;border-radius:10px;color:#166534;font-size:.875rem;font-weight:600;margin-bottom:1.25rem;">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="tbl-card">

            {{-- toolbar --}}
            <div class="tbl-toolbar">
                <p class="tbl-count">
                    <strong>{{ $offers->total() }}</strong>
                    offre{{ $offers->total() > 1 ? 's' : '' }}
                    @if($status)
                        @php $stLbl = $statusLabels[$status] ?? null; @endphp
                        @if($stLbl)
                            · <span style="color:{{ $stLbl['color'] }};font-weight:700;">{{ $stLbl['label'] }}</span>
                        @endif
                    @endif
                </p>
            </div>

            <div class="tbl-wrap">
                <table class="tbl">
                    <thead>
                        <tr>
                            <th>
                                <a href="{{ sortUrl('title', $sort, $dir, $status) }}" class="th-sort">
                                    Titre de l'offre
                                    {!! sortIcon('title', $sort, $dir) !!}
                                </a>
                            </th>
                            <th>
                                <a href="{{ sortUrl('company', $sort, $dir, $status) }}" class="th-sort">
                                    Entreprise
                                    {!! sortIcon('company', $sort, $dir) !!}
                                </a>
                            </th>
                            <th class="th-col">
                                <button type="button" class="th-col-btn {{ $status ? 'active' : '' }}" onclick="document.getElementById('status-drop').classList.toggle('open');event.stopPropagation();">
                                    Statut
                                    @if($status && isset($statusLabels[$status]))
                                        <span style="width:6px;height:6px;border-radius:50%;background:{{ $statusLabels[$status]['dot'] }};display:inline-block;"></span>
                                    @endif
                                    <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div class="col-drop" id="status-drop">
                                    <a href="{{ statusFilterUrl('', $sort, $dir) }}" class="{{ $status === '' ? 'active' : '' }}">
                                        <span class="rd-dot" style="background:#9CA3AF;"></span> Tous
                                    </a>
                                    <div class="col-drop-sep"></div>
                                    <a href="{{ statusFilterUrl('pending_validation', $sort, $dir) }}" class="{{ $status === 'pending_validation' ? 'active' : '' }}">
                                        <span class="rd-dot" style="background:#F59E0B;"></span> En attente
                                    </a>
                                    <div class="col-drop-sep"></div>
                                    <a href="{{ statusFilterUrl('open', $sort, $dir) }}" class="{{ $status === 'open' ? 'active' : '' }}">
                                        <span class="rd-dot" style="background:#22C55E;"></span> Validée
                                    </a>
                                    <div class="col-drop-sep"></div>
                                    <a href="{{ statusFilterUrl('refused', $sort, $dir) }}" class="{{ $status === 'refused' ? 'active' : '' }}">
                                        <span class="rd-dot" style="background:#DC2626;"></span> Refusée
                                    </a>
                                </div>
                            </th>
                            <th>
                                <a href="{{ sortUrl('date', $sort, $dir, $status) }}" class="th-sort">
                                    Date
                                    {!! sortIcon('date', $sort, $dir) !!}
                                </a>
                            </th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($offers as $offer)
                            @php
                                $stCfg = $statusLabels[$offer->status] ?? ['label' => $offer->status, 'bg' => '#F1F5F9', 'color' => '#475569', 'border' => '#CBD5E1', 'dot' => '#94A3B8'];
                                $isPending = $offer->status === 'pending_validation';
                            @endphp
                            <tr onclick="if(!event.target.closest('a')&&!event.target.closest('button')&&!event.target.closest('form')) window.location.href='{{ route('admin.offers.show', $offer) }}'">

                                {{-- Titre --}}
                                <td>
                                    <a href="{{ route('admin.offers.show', $offer) }}" class="tbl-title">
                                        {{ $offer->title }}
                                    </a>
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
                                </td>

                                {{-- Entreprise --}}
                                <td>
                                    <div style="display:flex;align-items:center;gap:.6rem;">
                                        <img src="{{ $offer->company->logo_path ? $offer->company->logo_url : 'https://ui-avatars.com/api/?name='.urlencode($offer->company->name).'&background=EEF2FF&color=4F46E5&bold=true' }}"
                                             alt="{{ $offer->company->name }}"
                                             class="co-logo">
                                        <div>
                                            <div style="font-size:.85rem;font-weight:700;color:var(--ink);line-height:1.2;">{{ $offer->company->name }}</div>
                                            @if($offer->company->city)
                                                <div style="font-size:.72rem;color:var(--sub);margin-top:.1rem;">{{ $offer->company->city }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Statut --}}
                                <td>
                                    <span class="stb" style="background:{{ $stCfg['bg'] }};color:{{ $stCfg['color'] }};border-color:{{ $stCfg['border'] }};">
                                        <span class="stb-dot" style="background:{{ $stCfg['dot'] }};"></span>
                                        {{ $stCfg['label'] }}
                                    </span>
                                </td>

                                {{-- Date --}}
                                <td>
                                    <span style="font-size:.82rem;color:var(--sub);font-weight:500;">
                                        {{ $offer->created_at->format('d/m/Y') }}
                                    </span>
                                </td>

                                {{-- Actions --}}
                                <td>
                                    <div style="display:inline-flex;align-items:center;gap:.5rem;justify-content:flex-end;">
                                        <a href="{{ route('admin.offers.show', $offer) }}" class="btn-g">
                                            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                                <circle cx="12" cy="12" r="3"/>
                                            </svg>
                                            Voir
                                        </a>

                                        @if($isPending)
                                            <form method="POST" action="{{ route('admin.offers.status', $offer) }}"
                                                  class="js-validate-form"
                                                  data-variant="validate"
                                                  data-title="{{ $offer->title }}"
                                                  style="display:contents;">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="open">
                                                <button type="submit" class="btn-ok">
                                                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    Valider
                                                </button>
                                            </form>

                                            <form method="POST" action="{{ route('admin.offers.status', $offer) }}"
                                                  class="js-refuse-form"
                                                  data-variant="refuse"
                                                  data-title="{{ $offer->title }}"
                                                  style="display:contents;">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="refused">
                                                <button type="submit" class="btn-rd">
                                                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                    Refuser
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
                                                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <h3>Aucune offre trouvée</h3>
                                        <p>Aucune offre ne correspond aux critères sélectionnés.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($offers->hasPages())
                <div style="padding:1.25rem 1.5rem; border-top:1px solid var(--bd);">
                    {{ $offers->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Confirm modal --}}
    <div id="mod-modal" class="modal-backdrop" role="dialog" aria-modal="true">
        <div class="modal">
            <div class="modal-head">
                <div class="modal-ico" id="mod-modal-ico"></div>
                <div>
                    <h3 class="modal-title" id="mod-modal-title"></h3>
                    <p class="modal-msg" id="mod-modal-msg"></p>
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" class="modal-cancel" id="mod-modal-cancel">Annuler</button>
                <button type="button" class="modal-ok" id="mod-modal-ok"></button>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('click', function() {
        document.querySelectorAll('.col-drop').forEach(function(d){ d.classList.remove('open'); });
    });
    </script>
    <script>
    (function () {
        var modal   = document.getElementById('mod-modal');
        var ico     = document.getElementById('mod-modal-ico');
        var title   = document.getElementById('mod-modal-title');
        var msg     = document.getElementById('mod-modal-msg');
        var btnOk   = document.getElementById('mod-modal-ok');
        var btnNo   = document.getElementById('mod-modal-cancel');
        var pending = null;

        function open(form, variant, offerTitle) {
            pending = form;
            var isValidate = variant === 'validate';
            var color = isValidate ? '#166534' : '#DC2626';
            var bg    = isValidate ? '#F0FDF4' : '#FEF2F2';
            ico.style.background = bg;
            ico.innerHTML = isValidate
                ? '<svg width="20" height="20" fill="none" stroke="' + color + '" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>'
                : '<svg width="20" height="20" fill="none" stroke="' + color + '" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>';
            title.textContent = isValidate ? 'Valider cette offre ?' : 'Refuser cette offre ?';
            msg.textContent   = isValidate
                ? "L'offre \"" + offerTitle + "\" sera rendue visible aux candidats."
                : "L'offre \"" + offerTitle + "\" sera rejetee et l'entreprise en sera notifiee.";
            btnOk.textContent      = isValidate ? 'Valider' : 'Refuser';
            btnOk.style.background = color;
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

        document.querySelectorAll('.js-validate-form').forEach(function (f) {
            f.onsubmit = function (e) { e.preventDefault(); open(f, 'validate', f.dataset.title); };
        });
        document.querySelectorAll('.js-refuse-form').forEach(function (f) {
            f.onsubmit = function (e) { e.preventDefault(); open(f, 'refuse', f.dataset.title); };
        });
    })();
    </script>
</x-app-layout>
