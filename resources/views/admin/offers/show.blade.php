<x-app-layout>
    <x-slot name="header">
        <x-page-header
            :title="Str::limit($jobOffer->title, 60)"
            :subtitle="$jobOffer->company->name ?? ''"
            icon="briefcase"
            :breadcrumbs="[
                ['label' => session('nav_level0.label', 'Modération des offres'), 'url' => session('nav_level0.url', route('admin.offers.moderation'))],
                ['label' => Str::limit($jobOffer->title, 42)],
            ]"
        />
    </x-slot>

    @php
        $typeMap = [
            'full-time'  => ['label' => 'Temps plein',   'icon' => '🕐', 'cls' => 'show-tag-green'],
            'part-time'  => ['label' => 'Temps partiel', 'icon' => '⏱',  'cls' => 'show-tag-amber'],
            'remote'     => ['label' => 'Télétravail',   'icon' => '🏠',  'cls' => 'show-tag-indigo'],
            'freelance'  => ['label' => 'Freelance',     'icon' => '💼',  'cls' => 'show-tag-purple'],
            'internship' => ['label' => 'Stage',         'icon' => '🎓',  'cls' => 'show-tag-blue'],
        ];
        $tb = $typeMap[$jobOffer->type] ?? ['label' => ucfirst($jobOffer->type), 'icon' => '📋', 'cls' => 'show-tag-gray'];

        $daysLeft   = now()->diffInDays(\Carbon\Carbon::parse($jobOffer->expiration_date), false);
        $expired    = $daysLeft < 0;
        $urgentExpiry = $daysLeft <= 7 && $daysLeft >= 0;

        $categoryName   = $jobOffer->offerCategory->name      ?? null;
        $categoryImg    = $jobOffer->offerCategory->image_url ?? null;
        $companyInitial = strtoupper(substr($jobOffer->company->name, 0, 1));

        $statusColors = [
            'pending_validation' => ['bg'=>'#FFFBEB','color'=>'#92400E','border'=>'#FDE68A','label'=>'En attente de validation'],
            'open'     => ['bg'=>'#F0FDF4','color'=>'#166534','border'=>'#BBF7D0','label'=>'Publiée'],
            'closed'   => ['bg'=>'#F9FAFB','color'=>'#374151','border'=>'#E5E7EB','label'=>'Clôturée'],
            'refused'  => ['bg'=>'#FEF2F2','color'=>'#991B1B','border'=>'#FECACA','label'=>'Refusée'],
            'archived' => ['bg'=>'#F9FAFB','color'=>'#374151','border'=>'#E5E7EB','label'=>'Archivée'],
        ];
        $sc = $statusColors[$jobOffer->status] ?? $statusColors['pending_validation'];

        $totalDays = $jobOffer->created_at->diffInDays(\Carbon\Carbon::parse($jobOffer->expiration_date));
        $usedDays  = $jobOffer->created_at->diffInDays(now());
        $pct       = $totalDays > 0 ? min(100, round(($usedDays / $totalDays) * 100)) : 100;
    @endphp

    <style>
    /* ══ Design tokens ══════════════════════════════════════════ */
    :root {
        --p:       #4F46E5;
        --p-dk:    #3730A3;
        --p-lt:    #EEF2FF;
        --p-mid:   #818CF8;
        --border:  #E5E7EB;
        --border2: #D1D5DB;
        --card:    #FFFFFF;
        --surf:    #F8F9FC;
        --txt:     #111827;
        --muted:   #6B7280;
        --r:       16px;
        --r-sm:    10px;
        --sh:      0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
        --sh-md:   0 4px 20px rgba(79,70,229,.1);
        --tr:      .18s cubic-bezier(.4,0,.2,1);
    }

    /* ══ Page layout ════════════════════════════════════════════ */
    .show-page {
        max-width:80rem; margin:0 auto;
        padding:1.75rem 1.5rem 3rem;
        display:grid; grid-template-columns:1fr 340px;
        gap:1.75rem; align-items:start;
    }
    @media(max-width:960px) { .show-page { grid-template-columns:1fr; padding:1rem; } }

    .show-sidebar { position:sticky; top:5rem; display:flex; flex-direction:column; gap:1.25rem; }

    /* ══ Card base ══════════════════════════════════════════════ */
    .show-card {
        background:var(--card); border:1px solid var(--border);
        border-radius:var(--r); box-shadow:var(--sh); overflow:hidden;
    }
    .show-card + .show-card { margin-top:1.25rem; }
    .show-card-head {
        display:flex; align-items:center; gap:.65rem;
        padding:1rem 1.5rem; border-bottom:1px solid #F3F4F6; background:#FAFBFF;
    }
    .show-card-head-icon { width:28px; height:28px; border-radius:8px; flex-shrink:0; display:flex; align-items:center; justify-content:center; }
    .show-card-head h2 { font-size:.95rem; font-weight:800; color:var(--txt); margin:0; }
    .show-card-body { padding:1.5rem; }

    /* ══ Hero banner ════════════════════════════════════════════ */
    .show-hero { border-radius:var(--r); overflow:hidden; background:var(--card); border:1px solid var(--border); box-shadow:var(--sh); margin-bottom:1.25rem; }
    .show-hero-banner {
        background:linear-gradient(135deg, #1e1b4b 0%, #3730a3 45%, #4f46e5 75%, #818cf8 100%);
        padding:1.75rem 1.75rem 2.75rem; position:relative; overflow:hidden;
    }
    .show-hero-banner::before {
        content:''; position:absolute; inset:0;
        background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='30' cy='30' r='20'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
        pointer-events:none;
    }
    .show-hero-banner::after {
        content:''; position:absolute; bottom:-1px; left:0; right:0; height:32px;
        background:var(--card); clip-path:ellipse(55% 100% at 50% 100%);
    }
    .show-hero-top { display:flex; align-items:flex-start; gap:1rem; position:relative; z-index:1; }
    .show-hero-logo { width:72px; height:72px; border-radius:16px; object-fit:cover; border:3px solid rgba(255,255,255,.25); box-shadow:0 8px 24px rgba(0,0,0,.25); background:white; }
    .show-hero-logo-ph { width:72px; height:72px; border-radius:16px; background:rgba(255,255,255,.15); border:3px solid rgba(255,255,255,.25); display:flex; align-items:center; justify-content:center; font-size:1.75rem; font-weight:900; color:white; box-shadow:0 8px 24px rgba(0,0,0,.2); backdrop-filter:blur(4px); }
    .show-hero-company { font-size:.78rem; font-weight:700; color:rgba(255,255,255,.7); text-transform:uppercase; letter-spacing:.06em; margin-bottom:.35rem; }
    .show-hero-title { font-size:1.5rem; font-weight:900; color:white; margin:0 0 .6rem; line-height:1.2; text-shadow:0 1px 3px rgba(0,0,0,.2); }
    @media(max-width:600px) { .show-hero-title { font-size:1.2rem; } }

    .show-hero-tags { display:flex; flex-wrap:wrap; gap:.45rem; position:relative; z-index:1; margin-top:.5rem; }
    .show-tag { display:inline-flex; align-items:center; gap:.3rem; padding:.28rem .75rem; border-radius:99px; font-size:.72rem; font-weight:700; border:1px solid; white-space:nowrap; }
    .show-tag-green  { color:#065F46; background:#ECFDF5; border-color:#A7F3D0; }
    .show-tag-amber  { color:#92400E; background:#FFFBEB; border-color:#FDE68A; }
    .show-tag-indigo { color:#3730A3; background:#EEF2FF; border-color:#C7D2FE; }
    .show-tag-purple { color:#5B21B6; background:#F5F3FF; border-color:#DDD6FE; }
    .show-tag-blue   { color:#1E40AF; background:#EFF6FF; border-color:#BFDBFE; }
    .show-tag-gray   { color:#374151; background:#F9FAFB; border-color:#E5E7EB; }
    .show-tag-hero   { background:rgba(255,255,255,.15); border-color:rgba(255,255,255,.25); color:white; backdrop-filter:blur(4px); }
    .show-tag-expired { background:#FEF2F2; border-color:#FECACA; color:#991B1B; }
    .show-tag-urgency { background:#FEF3C7; border-color:#FDE68A; color:#92400E; }
    .show-tag-pending { background:#FFFBEB; border-color:#FDE68A; color:#92400E; }
    .show-tag-refused { background:#FEF2F2; border-color:#FECACA; color:#991B1B; }
    .show-tag-open    { background:#F0FDF4; border-color:#BBF7D0; color:#166534; }

    /* ══ Pull-up ════════════════════════════════════════════════ */
    .show-hero-pullup { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:.75rem; padding:1rem 1.75rem 1.25rem; background:white; margin-top:-1px; }
    .show-hero-meta { display:flex; align-items:center; gap:1.25rem; flex-wrap:wrap; }
    .show-hero-meta-item { display:flex; align-items:center; gap:.4rem; font-size:.8rem; color:var(--muted); font-weight:600; }
    .show-hero-meta-item svg { color:var(--p); flex-shrink:0; }

    /* ══ Prose ══════════════════════════════════════════════════ */
    .show-section-title { display:flex; align-items:center; gap:.5rem; font-size:.7rem; font-weight:800; text-transform:uppercase; letter-spacing:.08em; color:var(--muted); margin:0 0 .9rem; }
    .show-section-title::after { content:''; flex:1; height:1px; background:#F3F4F6; }
    .show-section-title svg { flex-shrink:0; color:var(--p); }
    .show-prose { font-size:.9rem; color:#374151; line-height:1.8; white-space:pre-line; }

    /* ══ Details grid ═══════════════════════════════════════════ */
    .show-details-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:.85rem; }
    @media(max-width:700px) { .show-details-grid { grid-template-columns:1fr 1fr; } }
    .show-detail-chip { display:flex; align-items:center; gap:.75rem; background:var(--surf); border:1px solid var(--border); border-radius:var(--r-sm); padding:.75rem 1rem; transition:box-shadow var(--tr),transform var(--tr); }
    .show-detail-chip:hover { box-shadow:var(--sh-md); transform:translateY(-1px); }
    .show-detail-chip-icon { width:36px; height:36px; border-radius:8px; flex-shrink:0; display:flex; align-items:center; justify-content:center; }
    .show-detail-chip-icon.indigo { background:var(--p-lt); } .show-detail-chip-icon.indigo svg { color:var(--p); }
    .show-detail-chip-icon.green  { background:#F0FDF4; }     .show-detail-chip-icon.green  svg { color:#16A34A; }
    .show-detail-chip-icon.blue   { background:#EFF6FF; }     .show-detail-chip-icon.blue   svg { color:#2563EB; }
    .show-detail-chip-icon.purple { background:#FAF5FF; }     .show-detail-chip-icon.purple svg { color:#9333EA; }
    .show-detail-chip-icon.amber  { background:#FFFBEB; }     .show-detail-chip-icon.amber  svg { color:#D97706; }
    .show-detail-chip-icon.red    { background:#FEF2F2; }     .show-detail-chip-icon.red    svg { color:#EF4444; }
    .show-detail-chip-label { font-size:.68rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:var(--muted); margin-bottom:.15rem; }
    .show-detail-chip-value { font-size:.875rem; font-weight:800; color:var(--txt); }
    .show-detail-chip-value.red { color:#DC2626; }

    /* ══ Keywords ═══════════════════════════════════════════════ */
    .show-keywords { display:flex; flex-wrap:wrap; gap:.4rem; }
    .show-keyword { display:inline-flex; align-items:center; padding:.25rem .7rem; border-radius:99px; font-size:.72rem; font-weight:600; background:#f1f5f9; border:1px solid #e2e8f0; color:#475569; }

    /* ══ Company card ═══════════════════════════════════════════ */
    .show-company-logo    { width:56px; height:56px; border-radius:12px; object-fit:cover; border:1.5px solid var(--border); box-shadow:0 2px 8px rgba(0,0,0,.08); background:white; flex-shrink:0; }
    .show-company-logo-ph { width:56px; height:56px; border-radius:12px; flex-shrink:0; background:var(--p-lt); border:1.5px solid #C7D2FE; display:flex; align-items:center; justify-content:center; font-size:1.25rem; font-weight:900; color:var(--p); box-shadow:0 2px 8px rgba(79,70,229,.12); }

    /* ══ Progress bar ═══════════════════════════════════════════ */
    .show-progress-bar  { height:4px; border-radius:99px; background:#E5E7EB; overflow:hidden; }
    .show-progress-fill { height:100%; border-radius:99px; background:linear-gradient(90deg,var(--p),var(--p-mid)); transition:width 1s ease; }

    /* ══ Moderation actions ═════════════════════════════════════ */
    .adm-action-card {
        background:#fff; border:1px solid var(--border); border-radius:var(--r);
        box-shadow:var(--sh); overflow:hidden;
    }
    .adm-action-head {
        padding:1rem 1.5rem; background:#FAFBFF; border-bottom:1px solid #F3F4F6;
        display:flex; align-items:center; gap:.65rem;
    }
    .adm-action-body { padding:1.25rem 1.5rem; display:flex; flex-direction:column; gap:.75rem; }
    .adm-action-info { font-size:.78rem; color:var(--muted); line-height:1.6; padding:.65rem .85rem; background:#F9FAFB; border-radius:8px; border:1px solid #F3F4F6; display:flex; align-items:flex-start; gap:.5rem; }

    .adm-btn-validate {
        display:flex; align-items:center; justify-content:center; gap:.5rem;
        width:100%; padding:.75rem 1rem; border-radius:var(--r-sm);
        background:linear-gradient(135deg,#059669,#34D399);
        color:#fff; border:none; font-size:.875rem; font-weight:800;
        font-family:inherit; cursor:pointer; letter-spacing:.01em;
        box-shadow:0 4px 14px rgba(5,150,105,.3);
        transition:opacity .15s, transform .15s, box-shadow .15s;
    }
    .adm-btn-validate:hover { opacity:.93; transform:translateY(-1px); box-shadow:0 6px 18px rgba(5,150,105,.38); }
    .adm-btn-validate:active { transform:translateY(0); }

    .adm-btn-refuse {
        display:flex; align-items:center; justify-content:center; gap:.5rem;
        width:100%; padding:.75rem 1rem; border-radius:var(--r-sm);
        background:#fff; color:#DC2626;
        border:1.5px solid #FECACA; font-size:.875rem; font-weight:700;
        font-family:inherit; cursor:pointer;
        transition:all .15s;
    }
    .adm-btn-refuse:hover { background:#FEF2F2; border-color:#EF4444; }

    .adm-btn-back {
        display:flex; align-items:center; justify-content:center; gap:.5rem;
        width:100%; padding:.6rem 1rem; border-radius:var(--r-sm);
        background:#fff; color:var(--muted);
        border:1.5px solid var(--border); font-size:.8rem; font-weight:600;
        font-family:inherit; cursor:pointer; text-decoration:none;
        transition:all .15s;
    }
    .adm-btn-back:hover { border-color:#9CA3AF; color:#374151; }

    /* Already-decided banner */
    .adm-decided-banner {
        padding:.875rem 1rem; border-radius:var(--r-sm);
        display:flex; align-items:center; gap:.75rem;
        font-size:.85rem; font-weight:700; border:1.5px solid;
    }

    /* ══ Confirm modal ═════════════════════════════════════════ */
    .modal-backdrop { display:none; position:fixed; inset:0; z-index:500; background:rgba(15,23,42,.45); backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:1rem; }
    .modal-backdrop.open { display:flex; }
    .modal { background:#fff; border-radius:16px; width:100%; max-width:420px; border:1.5px solid var(--border); box-shadow:0 20px 60px rgba(0,0,0,.18); overflow:hidden; }
    .modal-head { display:flex; align-items:flex-start; gap:1rem; padding:1.25rem 1.5rem; border-bottom:1px solid var(--border); }
    .modal-ico { width:42px; height:42px; border-radius:10px; flex-shrink:0; display:flex; align-items:center; justify-content:center; }
    .modal-title { font-size:1rem; font-weight:800; color:var(--txt); margin-bottom:.2rem; }
    .modal-msg { font-size:.85rem; color:var(--muted); line-height:1.55; margin:0; }
    .modal-foot { display:flex; justify-content:flex-end; gap:.75rem; padding:1rem 1.5rem; background:#F9FAFB; }
    .modal-cancel { padding:.53rem 1.2rem; border-radius:8px; border:1.5px solid var(--border); background:#fff; font-size:.85rem; font-weight:600; color:var(--muted); cursor:pointer; transition:all .15s; font-family:inherit; }
    .modal-cancel:hover { border-color:var(--p); color:var(--p); }
    .modal-ok { padding:.53rem 1.2rem; border-radius:8px; border:none; font-size:.85rem; font-weight:700; color:#fff; cursor:pointer; transition:opacity .15s; font-family:inherit; }
    .modal-ok:hover { opacity:.9; }

    /* ══ Animations ═════════════════════════════════════════════ */
    @keyframes fadeUp { from { opacity:0; transform:translateY(14px); } to { opacity:1; transform:none; } }
    .show-anim   { animation:fadeUp .35s ease both; }
    .show-anim-d1 { animation-delay:.05s; }
    .show-anim-d2 { animation-delay:.1s;  }
    .show-anim-d3 { animation-delay:.15s; }
    </style>

    <div class="show-page">

        {{-- ══════════════  LEFT COLUMN  ══════════════ --}}
        <div>

            {{-- Hero --}}
            <div class="show-hero show-anim">
                <div class="show-hero-banner">
                    <div class="show-hero-top">
                        <div>
                            @if($jobOffer->company->logo_path)
                                <img src="{{ $jobOffer->company->logo_url }}" alt="Logo {{ $jobOffer->company->name }}" class="show-hero-logo">
                            @else
                                <div class="show-hero-logo-ph" aria-hidden="true">{{ $companyInitial }}</div>
                            @endif
                        </div>
                        <div style="flex:1; min-width:0;">
                            <div class="show-hero-company">{{ $jobOffer->company->name }}</div>
                            <h1 class="show-hero-title">{{ $jobOffer->title }}</h1>
                        </div>
                    </div>
                    <div class="show-hero-tags">
                        <span class="show-tag show-tag-hero">
                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                            {{ $jobOffer->location ?? 'Non spécifié' }}
                        </span>
                        <span class="show-tag show-tag-hero">{{ $tb['icon'] }} {{ $tb['label'] }}</span>
                        @if($categoryName)
                            <span class="show-tag show-tag-hero">
                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l5 5a2 2 0 01.586 1.414V19a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z"/></svg>
                                {{ $categoryName }}
                            </span>
                        @endif
                        {{-- Status badge --}}
                        @if($jobOffer->status === 'pending_validation')
                            <span class="show-tag show-tag-pending">⏳ En attente de validation</span>
                        @elseif($jobOffer->status === 'refused')
                            <span class="show-tag show-tag-refused">⛔ Refusée</span>
                        @elseif($jobOffer->status === 'open')
                            <span class="show-tag show-tag-open">✅ Publiée</span>
                        @endif
                        @if($expired)
                            <span class="show-tag show-tag-expired">⛔ Expirée</span>
                        @elseif($urgentExpiry)
                            <span class="show-tag show-tag-urgency">⚡ Expire dans {{ $daysLeft }}j</span>
                        @endif
                    </div>
                </div>

                <div class="show-hero-pullup">
                    <div class="show-hero-meta">
                        @if($jobOffer->salary)
                            <div class="show-hero-meta-item">
                                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/></svg>
                                <strong style="color:var(--txt);">{{ number_format($jobOffer->salary, 0, ',', ' ') }} DT</strong>
                                <span>/mois</span>
                            </div>
                        @endif
                        <div class="show-hero-meta-item">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $jobOffer->vacancies }} poste(s) vacant(s)
                        </div>
                        <div class="show-hero-meta-item">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Soumise le {{ $jobOffer->created_at->format('d/m/Y') }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Description & Exigences --}}
            <div class="show-card show-anim show-anim-d1" style="margin-bottom:1.25rem;">
                <div class="show-card-head">
                    <div class="show-card-head-icon" style="background:#EEF2FF;">
                        <svg width="15" height="15" fill="none" stroke="#4F46E5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h2>Description du poste</h2>
                </div>
                <div class="show-card-body">
                    <div class="show-prose" style="margin-bottom:2rem;">{{ $jobOffer->description }}</div>

                    <p class="show-section-title">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        Exigences &amp; Compétences
                    </p>
                    <div class="show-prose">{{ $jobOffer->requirements }}</div>

                    @if($jobOffer->keywords)
                        <p class="show-section-title" style="margin-top:1.75rem;">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                            Mots-clés
                        </p>
                        <div class="show-keywords">
                            @foreach(explode(',', $jobOffer->keywords) as $kw)
                                @if(trim($kw))
                                    <span class="show-keyword"># {{ trim($kw) }}</span>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- Détails chips --}}
            <div class="show-card show-anim show-anim-d2">
                <div class="show-card-head">
                    <div class="show-card-head-icon" style="background:#F0FDF4;">
                        <svg width="15" height="15" fill="none" stroke="#16A34A" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <h2>Détails de l'offre</h2>
                </div>
                <div class="show-card-body">
                    <div class="show-details-grid">
                        @if($categoryName)
                        <div class="show-detail-chip">
                            <div class="show-detail-chip-icon indigo"><svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l5 5a2 2 0 01.586 1.414V19a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z"/></svg></div>
                            <div><div class="show-detail-chip-label">Catégorie</div><div class="show-detail-chip-value">{{ $categoryName }}</div></div>
                        </div>
                        @endif
                        <div class="show-detail-chip">
                            <div class="show-detail-chip-icon blue"><svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg></div>
                            <div><div class="show-detail-chip-label">Niveau d'étude</div><div class="show-detail-chip-value">{{ $jobOffer->education_level }}</div></div>
                        </div>
                        <div class="show-detail-chip">
                            <div class="show-detail-chip-icon green"><svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></div>
                            <div><div class="show-detail-chip-label">Expérience</div><div class="show-detail-chip-value">{{ $jobOffer->experience_years }} an(s)</div></div>
                        </div>
                        <div class="show-detail-chip">
                            <div class="show-detail-chip-icon purple"><svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>
                            <div><div class="show-detail-chip-label">Postes vacants</div><div class="show-detail-chip-value">{{ $jobOffer->vacancies }}</div></div>
                        </div>
                        @if($jobOffer->languages)
                        <div class="show-detail-chip">
                            <div class="show-detail-chip-icon amber"><svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg></div>
                            <div><div class="show-detail-chip-label">Langues</div><div class="show-detail-chip-value">{{ $jobOffer->languages }}</div></div>
                        </div>
                        @endif
                        <div class="show-detail-chip">
                            <div class="show-detail-chip-icon red"><svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                            <div><div class="show-detail-chip-label" style="color:#EF4444;">Expiration</div><div class="show-detail-chip-value red">{{ \Carbon\Carbon::parse($jobOffer->expiration_date)->format('d/m/Y') }}</div></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ══════════════  SIDEBAR  ══════════════ --}}
        <aside class="show-sidebar show-anim show-anim-d2">

            {{-- Moderation actions --}}
            <div class="adm-action-card">
                <div class="adm-action-head">
                    <div style="width:28px;height:28px;border-radius:8px;background:linear-gradient(135deg,#4F46E5,#818CF8);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg width="14" height="14" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <h2 style="font-size:.95rem;font-weight:800;color:#111827;margin:0;">Modération</h2>
                </div>
                <div class="adm-action-body">
                    @if($jobOffer->status === 'pending_validation')
                        {{-- Validate --}}
                        <form method="POST" action="{{ route('admin.offers.status', $jobOffer) }}"
                              class="js-validate-form" data-title="{{ $jobOffer->title }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="open">
                            <button type="submit" class="adm-btn-validate">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                Valider l'offre
                            </button>
                        </form>
                        {{-- Refuse --}}
                        <form method="POST" action="{{ route('admin.offers.status', $jobOffer) }}"
                              class="js-refuse-form" data-title="{{ $jobOffer->title }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="refused">
                            <button type="submit" class="adm-btn-refuse">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                Refuser l'offre
                            </button>
                        </form>
                        <div class="adm-action-info">
                            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            La validation publie l'offre immédiatement et notifie les candidats correspondants.
                        </div>
                    @else
                        <div class="adm-decided-banner" style="background:{{ $sc['bg'] }};color:{{ $sc['color'] }};border-color:{{ $sc['border'] }};">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Décision : {{ $sc['label'] }}
                        </div>
                        {{-- Allow re-moderation if needed --}}
                        @if($jobOffer->status === 'refused')
                        <form method="POST" action="{{ route('admin.offers.status', $jobOffer) }}"
                              class="js-validate-form" data-title="{{ $jobOffer->title }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="open">
                            <button type="submit" class="adm-btn-validate">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                Valider quand même
                            </button>
                        </form>
                        @endif
                    @endif
                    <a href="{{ route('admin.offers.moderation') }}" class="adm-btn-back">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Retour à la liste
                    </a>
                </div>
            </div>

            {{-- Company card --}}
            <div class="show-card">
                <div class="show-card-head">
                    <div class="show-card-head-icon" style="background:#EFF6FF;">
                        <svg width="15" height="15" fill="none" stroke="#2563EB" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <h2>Entreprise</h2>
                </div>
                <div class="show-card-body">
                    <div style="display:flex;align-items:center;gap:.875rem;margin-bottom:1rem;">
                        @if($jobOffer->company->logo_path)
                            <img src="{{ $jobOffer->company->logo_url }}" alt="Logo" class="show-company-logo">
                        @else
                            <div class="show-company-logo-ph">{{ $companyInitial }}</div>
                        @endif
                        <div>
                            <div style="font-size:1rem;font-weight:800;color:var(--p);">{{ $jobOffer->company->name }}</div>
                            @if($jobOffer->company->sector)
                                <div style="font-size:.75rem;color:var(--muted);margin-top:.1rem;">{{ $jobOffer->company->sector }}</div>
                            @endif
                        </div>
                    </div>
                    <div style="display:flex;flex-direction:column;gap:.5rem;">
                        <div style="font-size:.8rem;color:#374151;display:flex;align-items:center;gap:.4rem;">
                            <svg width="13" height="13" fill="none" stroke="#9CA3AF" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            {{ $jobOffer->company->email ?? 'Non renseigné' }}
                        </div>
                        @if($jobOffer->company->phone)
                        <div style="font-size:.8rem;color:#374151;display:flex;align-items:center;gap:.4rem;">
                            <svg width="13" height="13" fill="none" stroke="#9CA3AF" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            {{ $jobOffer->company->phone }}
                        </div>
                        @endif
                        @if($jobOffer->company->website)
                        <div style="font-size:.8rem;color:#374151;display:flex;align-items:center;gap:.4rem;">
                            <svg width="13" height="13" fill="none" stroke="#9CA3AF" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                            {{ $jobOffer->company->website }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Offer summary --}}
            <div class="show-card">
                <div class="show-card-head">
                    <div class="show-card-head-icon" style="background:#F0FDF4;">
                        <svg width="15" height="15" fill="none" stroke="#16A34A" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h2>Résumé de l'offre</h2>
                </div>
                <div class="show-card-body" style="display:flex;flex-direction:column;gap:.85rem;padding-top:1.1rem;">
                    @php
                        $summaryItems = [
                            ['icon'=>'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z','label'=>'Lieu','val'=>$jobOffer->location ?? 'Non spécifié','color'=>'#4F46E5'],
                            ['icon'=>'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z','label'=>'Type','val'=>$tb['label'],'color'=>'#4F46E5'],
                            ['icon'=>'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z','label'=>'Expérience','val'=>$jobOffer->experience_years.' an(s)','color'=>'#16A34A'],
                        ];
                        if($jobOffer->salary) $summaryItems[] = ['icon'=>'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1','label'=>'Salaire','val'=>number_format($jobOffer->salary,0,',',' ').' DT/mois','color'=>'#9333EA'];
                    @endphp
                    @foreach($summaryItems as $item)
                        <div style="display:flex;align-items:center;gap:.6rem;">
                            <div style="width:28px;height:28px;border-radius:7px;background:rgba(79,70,229,.08);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <svg width="13" height="13" fill="none" stroke="{{ $item['color'] }}" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/></svg>
                            </div>
                            <div>
                                <div style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);">{{ $item['label'] }}</div>
                                <div style="font-size:.82rem;font-weight:700;color:var(--txt);">{{ $item['val'] }}</div>
                            </div>
                        </div>
                    @endforeach

                    {{-- Expiry bar --}}
                    <div style="margin-top:.25rem;">
                        <div style="display:flex;justify-content:space-between;font-size:.7rem;font-weight:700;color:var(--muted);margin-bottom:.4rem;">
                            <span>Durée de l'offre</span>
                            <span style="color:{{ $pct >= 85 ? '#DC2626' : ($pct >= 60 ? '#D97706' : '#16A34A') }};">{{ 100 - $pct }}% restant</span>
                        </div>
                        <div class="show-progress-bar">
                            <div class="show-progress-fill" id="expiry-bar"
                                 style="width:0%;background:linear-gradient(90deg,{{ $pct >= 85 ? '#EF4444,#DC2626' : ($pct >= 60 ? '#F59E0B,#D97706' : '#4F46E5,#818CF8') }});">
                            </div>
                        </div>
                        <div style="font-size:.68rem;color:var(--muted);margin-top:.35rem;text-align:right;">
                            @if($expired)
                                <span style="color:#DC2626;">Offre expirée</span>
                            @else
                                Expire le {{ \Carbon\Carbon::parse($jobOffer->expiration_date)->format('d/m/Y') }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </aside>
    </div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const bar = document.getElementById('expiry-bar');
        if (bar) setTimeout(() => { bar.style.width = '{{ $pct }}%'; }, 300);

        document.querySelectorAll('.show-anim').forEach(el => {
            el.style.willChange = 'opacity, transform';
            new IntersectionObserver((entries, obs) => {
                entries.forEach(e => { if(e.isIntersecting) { e.target.style.opacity='1'; e.target.style.transform='none'; obs.unobserve(e.target); } });
            }, {threshold:0.1}).observe(el);
        });
    });
</script>
@endpush

{{-- Confirm modal (identique à admin/offers/moderation) --}}
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
            : "L'offre \"" + offerTitle + "\" sera rejetée et l'entreprise en sera notifiée.";
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
