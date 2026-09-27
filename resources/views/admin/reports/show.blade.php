<x-app-layout>
    <x-slot name="header">
        <x-page-header
            :title="'Signalement' "
            :subtitle="'Soumis le ' . $report->created_at->format('d/m/Y à H:i')"
            icon="flag"
            :breadcrumbs="[
                ['label' => session('nav_level0.label', 'Signalements'), 'url' => session('nav_level0.url', route('admin.reports.index'))],
                ['label' => 'Signalement' ],
            ]"
        />
    </x-slot>

    <style>
        :root {
            --p:#4F46E5; --pd:#3730A3; --pl:#EEF2FF;
            --bd:#E5E7EB; --bg:#F9FAFB; --card:#FFFFFF;
            --ink:#111827; --sub:#6B7280; --r:12px;
            --shd:0 1px 3px rgba(0,0,0,.07),0 2px 4px rgba(0,0,0,.04);
            --ease:.18s cubic-bezier(.4,0,.2,1);
        }
        .pg { max-width:80rem; margin:0 auto; padding:1.75rem 1.5rem; }
        @media(max-width:640px){ .pg { padding:1rem; } }

        .grid2 { display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; margin-bottom:1.25rem; }
        @media(max-width:680px){ .grid2 { grid-template-columns:1fr; } }

        .card { background:var(--card); border:1px solid var(--bd); border-radius:var(--r);
                box-shadow:var(--shd); overflow:hidden; }
        .card-head { padding:.85rem 1.25rem; border-bottom:1px solid var(--bd);
                     background:linear-gradient(135deg,#F8FBFF,#EFF6FF);
                     display:flex; align-items:center; gap:.5rem; }
        .card-head-label { font-size:.72rem; font-weight:800; text-transform:uppercase;
                           letter-spacing:.08em; color:var(--p); }
        .card-body { padding:1.25rem; }

        .profile-row { display:flex; align-items:center; gap:1rem; margin-bottom:1.1rem; }
        .profile-av { width:52px; height:52px; border-radius:12px; display:flex; align-items:center;
                      justify-content:center; font-size:1.2rem; font-weight:800; flex-shrink:0; }
        .profile-logo { width:52px; height:52px; border-radius:12px; object-fit:cover;
                        border:1.5px solid var(--bd); flex-shrink:0; }
        .profile-name { font-size:1rem; font-weight:800; color:var(--ink); }
        .profile-email { font-size:.78rem; color:var(--sub); margin-top:.1rem; }

        .meta-row { display:flex; align-items:center; justify-content:space-between;
                    padding:.5rem 0; border-bottom:1px solid #F3F4F6; font-size:.82rem; }
        .meta-row:last-child { border-bottom:none; padding-bottom:0; }
        .meta-label { color:var(--sub); font-weight:600; }
        .meta-val { color:var(--ink); font-weight:700; text-align:right; }

        .stb { display:inline-flex; align-items:center; gap:.35rem; padding:.25rem .7rem;
               border-radius:99px; font-size:.72rem; font-weight:700; border:1.5px solid; }
        .stb-dot { width:6px; height:6px; border-radius:50%; flex-shrink:0; }

        .reason-box { background:#F9FAFB; border:1.5px solid var(--bd); border-radius:10px;
                      padding:1.25rem; font-size:.9rem; color:var(--ink); line-height:1.65;
                      white-space:pre-wrap; word-break:break-word; }

        .action-strip { display:flex; gap:.75rem; align-items:center; flex-wrap:wrap;
                        padding:1.1rem 1.25rem; background:#F8FAFC; border-top:1px solid var(--bd); }
        .action-label { font-size:.78rem; color:var(--sub); font-weight:600; flex:1; }

        .btn-ok { display:inline-flex; align-items:center; gap:.45rem; padding:.55rem 1.25rem;
                  background:#F0FDF4; color:#166534; border:1.5px solid #A7F3D0; border-radius:9px;
                  font-family:inherit; font-size:.82rem; font-weight:700; cursor:pointer;
                  transition:all var(--ease); white-space:nowrap; }
        .btn-ok:hover { background:#DCFCE7; border-color:#4ADE80; }
        .btn-rd { display:inline-flex; align-items:center; gap:.45rem; padding:.55rem 1.25rem;
                  background:#FEF2F2; color:#991B1B; border:1.5px solid #FECACA; border-radius:9px;
                  font-family:inherit; font-size:.82rem; font-weight:700; cursor:pointer;
                  transition:all var(--ease); white-space:nowrap; }
        .btn-rd:hover { background:#FEE2E2; border-color:#F87171; }
        .btn-user { display:inline-flex; align-items:center; gap:.45rem; padding:.55rem 1.25rem;
                    background:var(--pl); color:var(--p); border:1.5px solid #A5B4FC; border-radius:9px;
                    font-family:inherit; font-size:.82rem; font-weight:700; cursor:pointer;
                    text-decoration:none; transition:all var(--ease); white-space:nowrap; }
        .btn-user:hover { background:#E0E7FF; border-color:var(--p); }

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

        {{-- Two profile cards --}}
        <div class="grid2">

            {{-- Reporter card --}}
            <div class="card">
                <div class="card-head">
                    <svg width="14" height="14" fill="none" stroke="#4F46E5" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="card-head-label">Signalé par</span>
                </div>
                <div class="card-body">
                    @php
                        $reporter = $report->user;
                        $repBg  = $reporter->isCompany() ? '#EFF6FF' : '#EEF2FF';
                        $repClr = $reporter->isCompany() ? '#1D4ED8' : '#4F46E5';
                    @endphp
                    <div class="profile-row">
                        @if($reporter->isCompany() && $reporter->logo_path)
                            <img src="{{ $reporter->logo_url }}" alt="" class="profile-logo">
                        @else
                            <div class="profile-av" style="background:{{ $repBg }};color:{{ $repClr }};">
                                {{ strtoupper(substr($reporter->name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <div class="profile-name">{{ $reporter->name }}</div>
                            <div class="profile-email">{{ $reporter->email }}</div>
                        </div>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Rôle</span>
                        <span class="meta-val">
                            @if($reporter->isCompany())
                                <span class="stb" style="background:#EFF6FF;color:#1D4ED8;border-color:#BFDBFE;">
                                    <span class="stb-dot" style="background:#3B82F6;"></span>Entreprise
                                </span>
                            @else
                                <span class="stb" style="background:#EEF2FF;color:#4F46E5;border-color:#C7D2FE;">
                                    <span class="stb-dot" style="background:#4F46E5;"></span>Candidat
                                </span>
                            @endif
                        </span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Inscrit le</span>
                        <span class="meta-val">{{ $reporter->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Statut</span>
                        <span class="meta-val">
                            @if($reporter->is_blocked)
                                <span class="stb" style="background:#FEF2F2;color:#991B1B;border-color:#FECACA;">
                                    <span class="stb-dot" style="background:#DC2626;"></span>Bloqué
                                </span>
                            @else
                                <span class="stb" style="background:#F0FDF4;color:#166534;border-color:#A7F3D0;">
                                    <span class="stb-dot" style="background:#22C55E;"></span>Actif
                                </span>
                            @endif
                        </span>
                    </div>
                </div>
                <div class="action-strip">
                    <span class="action-label"></span>
                    <a href="{{ route('admin.users.show', $reporter) }}" class="btn-user">
                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Voir le profil
                    </a>
                </div>
            </div>

            {{-- Reported user card --}}
            <div class="card">
                <div class="card-head">
                    <svg width="14" height="14" fill="none" stroke="#DC2626" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6H11l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                    </svg>
                    <span class="card-head-label" style="color:#DC2626;">Utilisateur signalé</span>
                </div>
                <div class="card-body">
                    @php
                        $reported = $report->reportedUser;
                        $rpdBg  = $reported->isCompany() ? '#EFF6FF' : '#EEF2FF';
                        $rpdClr = $reported->isCompany() ? '#1D4ED8' : '#4F46E5';
                    @endphp
                    <div class="profile-row">
                        @if($reported->isCompany() && $reported->logo_path)
                            <img src="{{ $reported->logo_url }}" alt="" class="profile-logo">
                        @else
                            <div class="profile-av" style="background:{{ $rpdBg }};color:{{ $rpdClr }};">
                                {{ strtoupper(substr($reported->name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <div class="profile-name">{{ $reported->name }}</div>
                            <div class="profile-email">{{ $reported->email }}</div>
                        </div>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Rôle</span>
                        <span class="meta-val">
                            @if($reported->isCompany())
                                <span class="stb" style="background:#EFF6FF;color:#1D4ED8;border-color:#BFDBFE;">
                                    <span class="stb-dot" style="background:#3B82F6;"></span>Entreprise
                                </span>
                            @else
                                <span class="stb" style="background:#EEF2FF;color:#4F46E5;border-color:#C7D2FE;">
                                    <span class="stb-dot" style="background:#4F46E5;"></span>Candidat
                                </span>
                            @endif
                        </span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Inscrit le</span>
                        <span class="meta-val">{{ $reported->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Statut actuel</span>
                        <span class="meta-val">
                            @if($reported->is_blocked)
                                <span class="stb" style="background:#FEF2F2;color:#991B1B;border-color:#FECACA;">
                                    <span class="stb-dot" style="background:#DC2626;"></span>Bloqué
                                </span>
                            @else
                                <span class="stb" style="background:#F0FDF4;color:#166534;border-color:#A7F3D0;">
                                    <span class="stb-dot" style="background:#22C55E;"></span>Actif
                                </span>
                            @endif
                        </span>
                    </div>
                </div>

                {{-- Action strip --}}
                <div class="action-strip">
                    <span class="action-label">Action de modération :</span>
                    <a href="{{ route('admin.users.show', $reported) }}" class="btn-user">
                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Voir le profil
                    </a>

                    @if(!$reported->is_blocked)
                        <form method="POST" action="{{ route('admin.users.status', $reported) }}"
                              id="block-form" style="display:contents;">
                            @csrf @method('PATCH')
                            <input type="hidden" name="is_blocked" value="1">
                            <button type="submit" class="btn-rd" id="btn-block">
                                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                </svg>
                                Bloquer ce compte
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.users.status', $reported) }}"
                              id="unblock-form" style="display:contents;">
                            @csrf @method('PATCH')
                            <input type="hidden" name="is_blocked" value="0">
                            <button type="submit" class="btn-ok" id="btn-unblock">
                                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                Débloquer ce compte
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        {{-- Full reason --}}
        @php
            $reasonParts = explode("\n\n", $report->reason, 2);
            $reasonType  = $reasonParts[0] ?? '';
            $reasonMsg   = $reasonParts[1] ?? '';
        @endphp
        <div class="card">
            <div class="card-head">
                <svg width="14" height="14" fill="none" stroke="#4F46E5" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span class="card-head-label">Raison du signalement</span>
            </div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:1rem;">
                {{-- Type badge --}}
                <div style="display:flex;align-items:center;gap:.65rem;">
                    <span style="font-size:.72rem;font-weight:800;text-transform:uppercase;letter-spacing:.07em;color:#6B7280;white-space:nowrap;">Motif du signalement</span>
                    <span style="display:inline-flex;align-items:center;gap:.4rem;padding:.3rem .85rem;border-radius:99px;background:#FEF2F2;color:#991B1B;border:1.5px solid #FECACA;font-size:.78rem;font-weight:700;">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6H11l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                        </svg>
                        {{ $reasonType ?: '—' }}
                    </span>
                </div>
                {{-- Message --}}
                <div>
                    <div style="font-size:.72rem;font-weight:800;text-transform:uppercase;letter-spacing:.07em;color:#6B7280;margin-bottom:.5rem;">Détails</div>
                    @if($reasonMsg)
                        <div class="reason-box">{{ $reasonMsg }}</div>
                    @else
                        <div style="font-size:.85rem;color:#9CA3AF;font-style:italic;padding:.75rem 1rem;background:#F9FAFB;border:1.5px dashed #E5E7EB;border-radius:10px;">
                            Aucun message complémentaire fourni.
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    {{-- Confirm modal --}}
    <div id="rpt-modal" class="modal-backdrop" role="dialog" aria-modal="true">
        <div class="modal">
            <div class="modal-head">
                <div class="modal-ico" id="rpt-modal-ico"></div>
                <div>
                    <h3 class="modal-title" id="rpt-modal-title"></h3>
                    <p class="modal-msg"  id="rpt-modal-msg"></p>
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" class="modal-cancel" id="rpt-modal-cancel">Annuler</button>
                <button type="button" class="modal-ok"     id="rpt-modal-ok"></button>
            </div>
        </div>
    </div>

    <script>
    (function(){
        var modal   = document.getElementById('rpt-modal');
        var ico     = document.getElementById('rpt-modal-ico');
        var title   = document.getElementById('rpt-modal-title');
        var msg     = document.getElementById('rpt-modal-msg');
        var btnOk   = document.getElementById('rpt-modal-ok');
        var btnNo   = document.getElementById('rpt-modal-cancel');
        var pending = null;

        var cfgs = {
            block: {
                color:'#DC2626', bg:'#FEF2F2',
                icon:'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>',
                titleText:'Bloquer ce compte ?',
                msgText:'Cet utilisateur ne pourra plus se connecter à la plateforme.',
                btnText:'Bloquer'
            },
            unblock: {
                color:'#166534', bg:'#F0FDF4',
                icon:'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>',
                titleText:'Débloquer ce compte ?',
                msgText:"Cet utilisateur pourra de nouveau accéder à la plateforme.",
                btnText:'Débloquer'
            }
        };

        function open(form, variant){
            pending = form;
            var c = cfgs[variant];
            ico.style.background = c.bg;
            ico.innerHTML = '<svg width="20" height="20" fill="none" stroke="'+c.color+'" stroke-width="2" viewBox="0 0 24 24">'+c.icon+'</svg>';
            title.textContent = c.titleText;
            msg.textContent   = c.msgText;
            btnOk.textContent = c.btnText;
            btnOk.style.background = c.color;
            modal.classList.add('open');
            document.body.style.overflow = 'hidden';
        }
        function close(){
            pending = null;
            modal.classList.remove('open');
            document.body.style.overflow = '';
        }

        btnNo.onclick = close;
        modal.onclick = function(e){ if(e.target===modal) close(); };
        btnOk.onclick = function(){ if(pending) pending.submit(); };
        document.addEventListener('keydown', function(e){ if(e.key==='Escape') close(); });

        var bf = document.getElementById('block-form');
        var uf = document.getElementById('unblock-form');
        if(bf) bf.onsubmit = function(e){ e.preventDefault(); open(bf,'block'); };
        if(uf) uf.onsubmit = function(e){ e.preventDefault(); open(uf,'unblock'); };
    })();
    </script>
</x-app-layout>
