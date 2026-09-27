<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Rechercher des candidats" subtitle="Trouvez les profils qui correspondent à vos besoins" icon="search"
             />
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
        .flt-sel-wrap { position:relative; }
        .flt-sel-wrap::after { content:''; position:absolute; right:.65rem; top:50%; transform:translateY(-50%); width:10px; height:10px; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' fill='none' stroke='%2394A3B8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' viewBox='0 0 24 24'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E"); background-size:contain; background-repeat:no-repeat; pointer-events:none; }
        .flt-sel, .flt-inp { width:100%; padding:.48rem 1.8rem .48rem .7rem; border:1.5px solid var(--bd); border-radius:8px; font-size:.8rem; font-family:inherit; font-weight:500; color:var(--ink); background:var(--bg); outline:none; appearance:none; cursor:pointer; transition:border-color var(--ease),box-shadow var(--ease),background var(--ease); }
        .flt-inp { appearance:auto; cursor:text; padding-right:.7rem; }
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
        .uc { background:var(--card); border:1px solid var(--bd); border-radius:var(--r); overflow:hidden; display:flex; flex-direction:column; position:relative; transition:border-color var(--ease),box-shadow var(--ease),transform var(--ease); }
        .uc::before { content:''; position:absolute; top:0; left:0; right:0; height:3px; background:linear-gradient(90deg,var(--p),#818CF8); transform:scaleX(0); transition:transform var(--ease); }
        .uc:hover { border-color:#C7D2FE; box-shadow:var(--shd-h); transform:translateY(-3px); }
        .uc:hover::before { transform:scaleX(1); }
        .uc-body { padding:1.125rem 1.25rem; flex:1; }
        .uc-foot { padding:.75rem 1.25rem; border-top:1px solid #F3F4F6; background:var(--bg); display:flex; align-items:center; justify-content:space-between; gap:.5rem; flex-wrap:wrap; }
        .uc-meta { display:flex; flex-wrap:wrap; gap:.35rem; margin-top:.7rem; }
        .uc-pill { display:inline-flex; align-items:center; gap:.28rem; padding:.22rem .6rem; border-radius:7px; font-size:.71rem; font-weight:600; background:var(--bg); color:var(--sub); border:1px solid var(--bd); }
        .uc-pill.hi { background:var(--pl); color:var(--p); border-color:#C7D2FE; font-weight:700; }
        .uc-pill.am { background:#FFFBEB; color:#92400E; border-color:#FDE68A; }
        .btn-p { display:inline-flex; align-items:center; gap:.4rem; padding:.45rem .95rem; background:var(--p); color:#fff; border:none; border-radius:8px; font-family:inherit; font-size:.78rem; font-weight:700; cursor:pointer; text-decoration:none; transition:background var(--ease),transform var(--ease),box-shadow var(--ease); white-space:nowrap; }
        .btn-p:hover { background:var(--pd); transform:translateY(-1px); box-shadow:0 4px 12px var(--pr); }
        .cand-avatar { width:44px; height:44px; border-radius:10px; background:var(--pl); border:1.5px solid #C7D2FE; display:flex; align-items:center; justify-content:center; font-size:1rem; font-weight:800; color:var(--p); flex-shrink:0; }
        .empty-box { grid-column:1/-1; background:var(--card); border:2px dashed var(--bd); border-radius:var(--r); padding:3.5rem 2rem; text-align:center; }
        .empty-ico { width:52px; height:52px; border-radius:14px; background:var(--pl); margin:0 auto .875rem; display:flex; align-items:center; justify-content:center; }
        .empty-box h3 { font-size:1rem; font-weight:800; color:var(--ink); margin-bottom:.4rem; }
        .empty-box p { font-size:.85rem; color:var(--sub); margin-bottom:1.25rem; max-width:300px; margin-left:auto; margin-right:auto; line-height:1.6; }
    </style>

    <div class="pg">
        <div class="pg-2col">

            {{-- Sidebar --}}
            @php
                $activeCount = collect(['city','category_id','education_level','experience_years'])
                    ->filter(fn($k) => request($k))->count();
            @endphp

            <aside class="flt-sidebar">
                <div class="flt-card">
                    <div class="flt-head">
                        <div class="flt-head-l">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 4h18M7 12h10M11 20h2"/>
                            </svg>
                            <span class="flt-head-title">Filtres</span>
                        </div>
                        @if($activeCount > 0)
                            <span class="flt-count on">{{ $activeCount }}</span>
                        @endif
                    </div>

                    @if(request()->hasAny(['city','category_id','education_level','experience_years']))
                        <div class="flt-active-tags">
                            @if(request('city'))
                                <span class="flt-tag">
                                    {{ request('city') }}
                                    <a href="{{ request()->fullUrlWithoutQuery(['city']) }}">x</a>
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
                            @if(request('education_level'))
                                <span class="flt-tag">
                                    {{ request('education_level') }}
                                    <a href="{{ request()->fullUrlWithoutQuery(['education_level']) }}">x</a>
                                </span>
                            @endif
                            @if(request('experience_years'))
                                <span class="flt-tag">
                                    {{ request('experience_years') }} ans min
                                    <a href="{{ request()->fullUrlWithoutQuery(['experience_years']) }}">x</a>
                                </span>
                            @endif
                        </div>
                    @endif

                    <div class="flt-body">
                        <form method="GET" action="{{ route('candidates.index') }}">

                            {{-- City --}}
                            <div class="flt-field">
                                <div class="flt-lbl">
                                    <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    Ville
                                </div>
                                <div class="flt-sel-wrap">
                                <select name="city" class="flt-sel {{ request('city') ? 'on' : '' }}">
                                    <option value="">Toutes les villes</option>
                                    @foreach(['Tunis','Ariana','Ben Arous','Manouba','Nabeul','Sousse','Monastir','Mahdia','Sfax','Bizerte','Kairouan','Gafsa','Gabes','Djerba'] as $city)
                                        <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
                                    @endforeach
                                </select>
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
                                <div class="flt-sel-wrap">
                                <select name="category_id" class="flt-sel {{ request('category_id') ? 'on' : '' }}">
                                    <option value="">Tous les domaines</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
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
                                <div class="flt-sel-wrap">
                                <select name="education_level" class="flt-sel {{ request('education_level') ? 'on' : '' }}">
                                    <option value="">Tous les niveaux</option>
                                    @foreach(['Inférieur au baccalauréat','Bac','Bac+3','Bac+5','Plus que Bac +5'] as $level)
                                        <option value="{{ $level }}" {{ request('education_level') == $level ? 'selected' : '' }}>{{ $level }}</option>
                                    @endforeach
                                </select>
                                </div>
                            </div>

                            {{-- Experience --}}
                            <div class="flt-field">
                                <div class="flt-lbl">
                                    <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Experience min (ans)
                                </div>
                                <input type="number"
                                       name="experience_years"
                                       class="flt-inp {{ request('experience_years') ? 'on' : '' }}"
                                       value="{{ request('experience_years') }}"
                                       placeholder="ex: 2"
                                       step="1" min="0">
                            </div>

                            <div class="flt-acts">
                                <button type="submit" class="flt-apply">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 4h18M7 12h10M11 20h2"/>
                                    </svg>
                                    Appliquer les filtres
                                </button>
                                <a href="{{ route('candidates.index') }}" class="flt-reset">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    Reinitialiser
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </aside>

            {{-- Main --}}
            <div class="pg-main">

                {{-- Results bar --}}
                <div class="res-bar">
                    <p class="res-count">
                        <strong>{{ $candidates->total() }}</strong>
                        candidat{{ $candidates->total() > 1 ? 's' : '' }} trouve{{ $candidates->total() > 1 ? 's' : '' }}
                    </p>

                    @if(request()->hasAny(['city','category_id','education_level','experience_years']))
                        <div class="chip-row">
                            @if(request('city'))
                                <span class="chip-active">
                                    {{ request('city') }}
                                    <a href="{{ request()->fullUrlWithoutQuery(['city']) }}">x</a>
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
                            @if(request('education_level'))
                                <span class="chip-active">
                                    {{ request('education_level') }}
                                    <a href="{{ request()->fullUrlWithoutQuery(['education_level']) }}">x</a>
                                </span>
                            @endif
                            @if(request('experience_years'))
                                <span class="chip-active">
                                    {{ request('experience_years') }} ans min
                                    <a href="{{ request()->fullUrlWithoutQuery(['experience_years']) }}">x</a>
                                </span>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- Cards grid --}}
                <div class="grid-2">
                    @forelse($candidates as $candidate)
                        <div class="uc" style="cursor:pointer;"
                             onclick="if(!event.target.closest('a')&&!event.target.closest('button')&&!event.target.closest('form')) window.location.href='{{ route('candidates.show', $candidate) }}'">
                            <div class="uc-body">
                                <div style="display:flex;align-items:flex-start;gap:.875rem;margin-bottom:.75rem;">
                                    <div class="cand-avatar">
                                        {{ strtoupper(substr($candidate->name, 0, 1)) }}
                                    </div>
                                    <div style="flex:1;min-width:0;">
                                        <a href="{{ route('candidates.show', $candidate) }}"
                                           style="font-size:.9rem;font-weight:800;color:var(--ink);text-decoration:none;display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;transition:color var(--ease);"
                                           onmouseover="this.style.color='var(--p)'" onmouseout="this.style.color='var(--ink)'">
                                            {{ $candidate->name }}
                                        </a>
                                        @if($candidate->domain)
                                            <div style="font-size:.78rem;font-weight:700;color:var(--p);margin-top:.2rem;">
                                                {{ $candidate->domain }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="uc-meta">
                                    @if($candidate->city)
                                        <span class="uc-pill">
                                            <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <circle cx="12" cy="11" r="3"/>
                                            </svg>
                                            {{ $candidate->city }}
                                        </span>
                                    @endif
                                    @if(isset($candidate->experience_years))
                                        <span class="uc-pill hi">
                                            <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ $candidate->experience_years }} ans exp.
                                        </span>
                                    @endif
                                    @if($candidate->education_level)
                                        <span class="uc-pill am">
                                            <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                                                <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                                            </svg>
                                            {{ $candidate->education_level }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="uc-foot">
                                <span></span>
                                <a href="{{ route('candidates.show', $candidate) }}" class="btn-p">
                                    Voir le profil
                                    <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="empty-box">
                            <div class="empty-ico">
                                <svg width="26" height="26" fill="none" stroke="#4F46E5" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                            <h3>Aucun candidat trouve</h3>
                            <p>Aucun candidat ne correspond a vos criteres de recherche.</p>
                            <a href="{{ route('candidates.index') }}" class="btn-p" style="display:inline-flex;">
                                Voir tous les candidats
                            </a>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if($candidates->hasPages())
                    <div style="margin-top:2rem;">
                        {{ $candidates->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
@push('scripts')
<script>
(function () {
    var KEY = 'candidates_index_url';
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
    // Clear on explicit reset link
    document.querySelectorAll('a[href="{{ route('candidates.index') }}"]').forEach(function(a) {
        a.addEventListener('click', function() { sessionStorage.removeItem(KEY); });
    });
})();
</script>
@endpush

</x-app-layout>
