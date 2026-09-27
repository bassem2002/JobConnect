<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Signalements" subtitle="Gérez les signalements et les comptes signalés" icon="flag"
        >
            <span style="display:inline-flex;align-items:center;gap:.35rem;padding:.3rem .85rem;border-radius:99px;background:#EEF2FF;color:#4F46E5;border:1.5px solid #C7D2FE;font-size:.75rem;font-weight:700;">
                {{ $totalReports }} signalement{{ $totalReports > 1 ? 's' : '' }}
            </span>
        </x-page-header>
    </x-slot>

    <style>
        :root {
            --p:    #4F46E5; --pd:   #3730A3; --pl:   #EEF2FF;
            --bd:   #E5E7EB; --bg:   #F9FAFB; --card: #FFFFFF;
            --ink:  #111827; --sub:  #6B7280; --r:    12px;
            --shd:  0 1px 3px rgba(0,0,0,.07),0 2px 4px rgba(0,0,0,.04);
            --ease: .18s cubic-bezier(.4,0,.2,1);
        }
        .pg { max-width:80rem; margin:0 auto; padding:1.75rem 1.5rem; }
        @media(max-width:640px){ .pg { padding:1rem; } }

        /* Stats strip */
        .st-strip { display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; margin-bottom:1.5rem; }
        @media(max-width:600px){ .st-strip { grid-template-columns:1fr; } }
        .st-card { background:var(--card); border:1px solid var(--bd); border-radius:var(--r);
                   padding:1rem 1.25rem; box-shadow:var(--shd); display:flex; align-items:center; gap:.85rem; }
        .st-ico { width:40px; height:40px; border-radius:10px; flex-shrink:0;
                  display:flex; align-items:center; justify-content:center; }
        .st-val { font-size:1.5rem; font-weight:800; color:var(--ink); line-height:1; }
        .st-lbl { font-size:.75rem; color:var(--sub); margin-top:.2rem; }

        /* Sortable headers */
        .th-sort { display:inline-flex; align-items:center; gap:.35rem; cursor:pointer;
                   text-decoration:none; color:inherit; white-space:nowrap; user-select:none; }
        .th-sort:hover { color:var(--pd); }
        .th-sort svg { opacity:.5; transition:opacity var(--ease); flex-shrink:0; }
        .th-sort:hover svg, .th-sort.active svg { opacity:1; }

        /* Table card */
        .tbl-card { background:var(--card); border-radius:var(--r); border:1px solid var(--bd); box-shadow:var(--shd); overflow:hidden; }
        .tbl-wrap { overflow-x:auto; }
        .tbl { width:100%; border-collapse:collapse; min-width:800px; }
        .tbl thead tr { border-bottom:1px solid var(--bd); background:#EFF6FF; }
        .tbl th { padding:.85rem 1rem; text-align:left; font-size:.72rem; font-weight:800;
                  text-transform:uppercase; letter-spacing:.08em; color:var(--p); white-space:nowrap; }
        .tbl th:last-child { text-align:right; }
        .tbl tbody tr { border-bottom:1px solid var(--bd); transition:background var(--ease); cursor:pointer; }
        .tbl tbody tr:hover { background:#EFF6FF; }
        .tbl tbody tr:last-child { border-bottom:none; }
        .tbl td { padding:.9rem 1rem; vertical-align:middle; font-size:.875rem; color:var(--ink); }
        .tbl td:last-child { text-align:right; }

        .u-cell { display:flex; align-items:center; gap:.65rem; }
        .u-av { width:32px; height:32px; border-radius:8px; display:flex; align-items:center;
                justify-content:center; font-size:.78rem; font-weight:800; flex-shrink:0; }
        .u-logo { width:32px; height:32px; border-radius:8px; object-fit:cover;
                  border:1.5px solid var(--bd); flex-shrink:0; }
        .u-name { font-weight:700; color:var(--ink); font-size:.875rem; }
        .u-role { font-size:.72rem; color:var(--sub); margin-top:.1rem; }

        .reason-preview { max-width:260px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;
                          font-size:.82rem; color:var(--sub); cursor:help; }

        .stb { display:inline-flex; align-items:center; gap:.35rem; padding:.25rem .7rem;
               border-radius:99px; font-size:.72rem; font-weight:700; border:1.5px solid; white-space:nowrap; }
        .stb-dot { width:6px; height:6px; border-radius:50%; flex-shrink:0; }

        .actions { display:flex; align-items:center; gap:.5rem; justify-content:flex-end; flex-wrap:wrap; }
        .btn-view { display:inline-flex; align-items:center; gap:.35rem; padding:.38rem .85rem;
                    background:var(--pl); color:var(--p); border:1.5px solid #A5B4FC; border-radius:8px;
                    font-family:inherit; font-size:.75rem; font-weight:700; cursor:pointer;
                    text-decoration:none; transition:all var(--ease); white-space:nowrap; }
        .btn-view:hover { background:#E0E7FF; border-color:var(--p); }
        .btn-ok  { display:inline-flex; align-items:center; gap:.35rem; padding:.38rem .85rem;
                   background:#F0FDF4; color:#166534; border:1.5px solid #A7F3D0; border-radius:8px;
                   font-family:inherit; font-size:.75rem; font-weight:700; cursor:pointer;
                   transition:all var(--ease); white-space:nowrap; }
        .btn-ok:hover  { background:#DCFCE7; border-color:#4ADE80; }
        .btn-rd  { display:inline-flex; align-items:center; gap:.35rem; padding:.38rem .85rem;
                   background:#FEF2F2; color:#991B1B; border:1.5px solid #FECACA; border-radius:8px;
                   font-family:inherit; font-size:.75rem; font-weight:700; cursor:pointer;
                   transition:all var(--ease); white-space:nowrap; }
        .btn-rd:hover  { background:#FEE2E2; border-color:#F87171; }

        .pgn { padding:1.25rem 1.5rem; border-top:1px solid var(--bd); }

        /* Empty state */
        .empty { display:flex; flex-direction:column; align-items:center; justify-content:center;
                 padding:4rem 2rem; gap:.75rem; }
        .empty-ico { width:56px; height:56px; border-radius:14px; background:#F3F4F6;
                     display:flex; align-items:center; justify-content:center; }
        .empty-txt { font-size:.95rem; font-weight:700; color:var(--ink); }
        .empty-sub { font-size:.82rem; color:var(--sub); text-align:center; }

        /* Modal */
        .modal-backdrop { display:none; position:fixed; inset:0; z-index:500;
                          background:rgba(15,23,42,.45); backdrop-filter:blur(4px);
                          align-items:center; justify-content:center; padding:1rem; }
        .modal-backdrop.open { display:flex; }
        .modal { background:#fff; border-radius:16px; width:100%; max-width:420px;
                 border:1.5px solid var(--bd); box-shadow:0 20px 60px rgba(0,0,0,.18); overflow:hidden; }
        .modal-head { display:flex; align-items:flex-start; gap:1rem; padding:1.25rem 1.5rem; border-bottom:1px solid var(--bd); }
        .modal-ico  { width:42px; height:42px; border-radius:10px; flex-shrink:0; display:flex; align-items:center; justify-content:center; }
        .modal-title { font-size:1rem; font-weight:800; color:var(--ink); margin-bottom:.2rem; }
        .modal-msg  { font-size:.85rem; color:var(--sub); line-height:1.55; margin:0; }
        .modal-foot { display:flex; justify-content:flex-end; gap:.75rem; padding:1rem 1.5rem; background:var(--bg); }
        .modal-cancel { padding:.53rem 1.2rem; border-radius:8px; border:1.5px solid var(--bd);
                        background:#fff; font-size:.85rem; font-weight:600; color:var(--sub);
                        cursor:pointer; transition:all var(--ease); font-family:inherit; }
        .modal-cancel:hover { border-color:var(--p); color:var(--p); }
        .modal-ok   { padding:.53rem 1.2rem; border-radius:8px; border:none; font-size:.85rem;
                      font-weight:700; color:#fff; cursor:pointer; transition:opacity var(--ease); font-family:inherit; }
        .modal-ok:hover { opacity:.9; }
    </style>

    <div class="pg">

        {{-- Flash --}}
        @if(session('success'))
            <div style="display:flex;align-items:center;gap:.65rem;padding:.85rem 1.1rem;background:#F0FDF4;
                        border:1.5px solid #A7F3D0;border-radius:10px;color:#166534;font-size:.875rem;
                        font-weight:600;margin-bottom:1.25rem;">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Stats strip --}}
        <div class="st-strip">
            <div class="st-card">
                <div class="st-ico" style="background:#EEF2FF;">
                    <svg width="20" height="20" fill="none" stroke="#4F46E5" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6H11l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                    </svg>
                </div>
                <div>
                    <div class="st-val">{{ $totalReports }}</div>
                    <div class="st-lbl">Total signalements</div>
                </div>
            </div>
            <div class="st-card">
                <div class="st-ico" style="background:#FEF2F2;">
                    <svg width="20" height="20" fill="none" stroke="#DC2626" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                    </svg>
                </div>
                <div>
                    <div class="st-val">{{ $blockedCount }}</div>
                    <div class="st-lbl">Comptes bloqués</div>
                </div>
            </div>
            <div class="st-card">
                <div class="st-ico" style="background:#F0FDF4;">
                    <svg width="20" height="20" fill="none" stroke="#16A34A" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <div class="st-val">{{ $totalReports - $blockedCount }}</div>
                    <div class="st-lbl">Comptes actifs signalés</div>
                </div>
            </div>
        </div>

        {{-- Table card --}}
        <div class="tbl-card">
            <div class="tbl-wrap">
                <table class="tbl">
                    <thead>
                        @php
                            $nextDir = fn($col) => ($sort === $col && $dir === 'asc') ? 'desc' : 'asc';
                            $sortUrl = fn($col) => route('admin.reports.index', ['sort' => $col, 'dir' => $nextDir($col)]);
                            $sortIco = function($col) use ($sort, $dir) {
                                if ($sort !== $col) {
                                    return '<svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4M17 8v12m0 0l4-4m-4 4l-4-4"/></svg>';
                                }
                                return $dir === 'asc'
                                    ? '<svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/></svg>'
                                    : '<svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>';
                            };
                        @endphp
                        <tr>
                            <th>
                                <a href="{{ $sortUrl('reporter') }}" class="th-sort {{ $sort === 'reporter' ? 'active' : '' }}">
                                    Signalé par {!! $sortIco('reporter') !!}
                                </a>
                            </th>
                            <th>
                                <a href="{{ $sortUrl('reported') }}" class="th-sort {{ $sort === 'reported' ? 'active' : '' }}">
                                    Utilisateur signalé {!! $sortIco('reported') !!}
                                </a>
                            </th>
                            <th>
                                <a href="{{ $sortUrl('date') }}" class="th-sort {{ $sort === 'date' ? 'active' : '' }}">
                                    Date {!! $sortIco('date') !!}
                                </a>
                            </th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                            @php
                                $reporter = $report->user;
                                $reported = $report->reportedUser;
                                $repBg  = $reporter->isCompany() ? '#EFF6FF' : '#EEF2FF';
                                $repClr = $reporter->isCompany() ? '#1D4ED8' : '#4F46E5';
                                $rpdBg  = $reported->isCompany() ? '#EFF6FF' : '#EEF2FF';
                                $rpdClr = $reported->isCompany() ? '#1D4ED8' : '#4F46E5';
                            @endphp
                            <tr onclick="if(!event.target.closest('a')&&!event.target.closest('button')&&!event.target.closest('form')) window.location.href='{{ route('admin.reports.show', $report) }}'">
                                {{-- Reporter --}}
                                <td>
                                    <div class="u-cell">
                                        @if($reporter->isCompany() && $reporter->logo_path)
                                            <img src="{{ $reporter->logo_url }}" alt="" class="u-logo">
                                        @else
                                            <div class="u-av" style="background:{{ $repBg }};color:{{ $repClr }};">
                                                {{ strtoupper(substr($reporter->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="u-name">{{ $reporter->name }}</div>
                                            <div class="u-role">{{ $reporter->isCompany() ? 'Entreprise' : 'Candidat' }}</div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Reported user --}}
                                <td>
                                    <div class="u-cell">
                                        @if($reported->isCompany() && $reported->logo_path)
                                            <img src="{{ $reported->logo_url }}" alt="" class="u-logo">
                                        @else
                                            <div class="u-av" style="background:{{ $rpdBg }};color:{{ $rpdClr }};">
                                                {{ strtoupper(substr($reported->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="u-name">{{ $reported->name }}</div>
                                            <div class="u-role">{{ $reported->isCompany() ? 'Entreprise' : 'Candidat' }}</div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Date --}}
                                <td>
                                    <span style="font-size:.78rem;color:var(--sub);">
                                        {{ $report->created_at->format('d/m/Y') }}<br>
                                        <span style="font-size:.7rem;">{{ $report->created_at->format('H:i') }}</span>
                                    </span>
                                </td>

                                {{-- Actions --}}
                                <td>
                                    <div class="actions">
                                        <a href="{{ route('admin.reports.show', $report) }}" class="btn-view">
                                            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Voir
                                        </a>

                                        @if(!$reported->is_blocked)
                                            <form method="POST" action="{{ route('admin.users.status', $reported) }}"
                                                  class="js-block-form" data-name="{{ $reported->name }}"
                                                  style="display:contents;">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="is_blocked" value="1">
                                                <button type="submit" class="btn-rd">
                                                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                                    </svg>
                                                    Bloquer
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('admin.users.status', $reported) }}"
                                                  class="js-unblock-form" data-name="{{ $reported->name }}"
                                                  style="display:contents;">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="is_blocked" value="0">
                                                <button type="submit" class="btn-ok">
                                                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    Débloquer
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="empty">
                                        <div class="empty-ico">
                                            <svg width="26" height="26" fill="none" stroke="#9CA3AF" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6H11l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                                            </svg>
                                        </div>
                                        <p class="empty-txt">Aucun signalement trouvé</p>
                                        <p class="empty-sub">Aucun signalement n'a été soumis pour le moment.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($reports->hasPages())
                <div class="pgn">{{ $reports->links() }}</div>
            @endif
        </div>
    </div>

    {{-- Confirm modal --}}
    <div id="rpt-modal" class="modal-backdrop" role="dialog" aria-modal="true">
        <div class="modal">
            <div class="modal-head">
                <div class="modal-ico" id="rpt-modal-ico"></div>
                <div>
                    <h3 class="modal-title" id="rpt-modal-title"></h3>
                    <p class="modal-msg" id="rpt-modal-msg"></p>
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" class="modal-cancel" id="rpt-modal-cancel">Annuler</button>
                <button type="button" class="modal-ok"    id="rpt-modal-ok"></button>
            </div>
        </div>
    </div>

    <script>
    (function () {
        var modal   = document.getElementById('rpt-modal');
        var ico     = document.getElementById('rpt-modal-ico');
        var title   = document.getElementById('rpt-modal-title');
        var msg     = document.getElementById('rpt-modal-msg');
        var btnOk   = document.getElementById('rpt-modal-ok');
        var btnNo   = document.getElementById('rpt-modal-cancel');
        var pending = null;

        var configs = {
            block: {
                color:'#DC2626', bg:'#FEF2F2',
                icon:'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>',
                titleFn: function(n){ return 'Bloquer "' + n + '" ?'; },
                msgText: 'Cet utilisateur ne pourra plus se connecter à la plateforme.',
                btnText: 'Bloquer'
            },
            unblock: {
                color:'#166534', bg:'#F0FDF4',
                icon:'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>',
                titleFn: function(n){ return 'Débloquer "' + n + '" ?'; },
                msgText: 'Cet utilisateur pourra de nouveau accéder à la plateforme.',
                btnText: 'Débloquer'
            }
        };

        function open(form, variant) {
            pending = form;
            var c = configs[variant];
            var name = form.dataset.name || '';
            ico.style.background = c.bg;
            ico.innerHTML = '<svg width="20" height="20" fill="none" stroke="' + c.color + '" stroke-width="2" viewBox="0 0 24 24">' + c.icon + '</svg>';
            title.textContent      = c.titleFn(name);
            msg.textContent        = c.msgText;
            btnOk.textContent      = c.btnText;
            btnOk.style.background = c.color;
            modal.classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function close() {
            pending = null;
            modal.classList.remove('open');
            document.body.style.overflow = '';
        }

        btnNo.onclick = close;
        modal.onclick = function(e){ if(e.target === modal) close(); };
        btnOk.onclick = function(){ if(pending) pending.submit(); };
        document.addEventListener('keydown', function(e){ if(e.key === 'Escape') close(); });

        document.querySelectorAll('.js-block-form').forEach(function(f){
            f.onsubmit = function(e){ e.preventDefault(); open(f, 'block'); };
        });
        document.querySelectorAll('.js-unblock-form').forEach(function(f){
            f.onsubmit = function(e){ e.preventDefault(); open(f, 'unblock'); };
        });
    })();
    </script>
</x-app-layout>
