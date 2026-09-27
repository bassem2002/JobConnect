<x-app-layout>
    <x-slot name="header">
        @php
            $navL0 = session('nav_level0');
            if (!$navL0) {
                $navL0 = (Auth::check() && Auth::user()->isCompany())
                    ? ['url' => route('job-offers.my-offers'), 'label' => 'Mes offres']
                    : ['url' => route('job-offers.index'), 'label' => 'Offres d\'emploi'];
            }
            $showBreadcrumbs = [
                ['label' => $navL0['label'], 'url' => $navL0['url']],
                ['label' => Str::limit($jobOffer->title, 42)],
            ];
        @endphp
        <x-page-header
            :title="Str::limit($jobOffer->title, 60)"
            :subtitle="$jobOffer->company->name ?? ''"
            icon="briefcase"
            :breadcrumbs="$showBreadcrumbs"
        />
    </x-slot>

    @php
        $hasAppliedToCompany = false;
        if (Auth::user() && Auth::user()->isCandidate()) {
            $hasAppliedToCompany = \App\Models\Application::where('user_id', Auth::id())
                ->whereHas('jobOffer', fn($q) => $q->where('user_id', $jobOffer->user_id))
                ->exists();
        }

        $typeMap = [
            'full-time'  => ['label' => 'Temps plein',   'icon' => '🕐', 'cls' => 'show-tag-green'],
            'part-time'  => ['label' => 'Temps partiel', 'icon' => '⏱',  'cls' => 'show-tag-amber'],
            'remote'     => ['label' => 'Télétravail',   'icon' => '🏠',  'cls' => 'show-tag-indigo'],
            'freelance'  => ['label' => 'Freelance',     'icon' => '💼',  'cls' => 'show-tag-purple'],
            'internship' => ['label' => 'Stage',         'icon' => '🎓',  'cls' => 'show-tag-blue'],
        ];
        $tb = $typeMap[$jobOffer->type] ?? ['label' => ucfirst($jobOffer->type), 'icon' => '📋', 'cls' => 'show-tag-gray'];

        $expDate   = \Carbon\Carbon::parse($jobOffer->expiration_date);
        $daysLeft  = now()->diffInDays($expDate, false);
        $urgentExpiry = $daysLeft <= 7 && $daysLeft >= 0;
        $expired = $daysLeft < 0;
        if (!$expired && $urgentExpiry) {
            $daysInt  = (int) floor($daysLeft);
            $hoursInt = (int) floor(now()->diffInHours($expDate, false));
            $minsInt  = (int) floor(now()->diffInMinutes($expDate, false));
            if ($daysInt >= 1)       $expiryText = $daysInt . 'j';
            elseif ($hoursInt >= 1)  $expiryText = $hoursInt . 'h';
            else                     $expiryText = max(0, $minsInt) . 'min';
        }

        $categoryName = $jobOffer->offerCategory->name ?? null;
        $categoryImg  = $jobOffer->offerCategory->image_url ?? null;
        $companyInitial = strtoupper(substr($jobOffer->company->name, 0, 1));
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
        --sh-lg:   0 8px 32px rgba(79,70,229,.14);
        --tr:      .18s cubic-bezier(.4,0,.2,1);
    }

    /* ══ Breadcrumb ═════════════════════════════════════════════ */
    .show-breadcrumb { display:flex; align-items:center; gap:.4rem; flex-wrap:wrap; }
    .show-bc-link {
        display:inline-flex; align-items:center; gap:.3rem;
        font-size:.8rem; font-weight:600; color:var(--muted);
        text-decoration:none; transition:color var(--tr);
    }
    .show-bc-link:hover { color:var(--p); }
    .show-bc-sep { color:#D1D5DB; }
    .show-bc-current {
        font-size:.8rem; font-weight:700; color:var(--txt);
        max-width:40ch; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;
    }

    /* ══ Page layout ════════════════════════════════════════════ */
    .show-page {
        max-width: 80rem;
        margin: 0 auto;
        padding: 1.75rem 1.5rem 3rem;
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 1.75rem;
        align-items: start;
    }
    @media (max-width: 960px) { .show-page { grid-template-columns: 1fr; padding: 1rem; } }

    /* ══ Sticky sidebar ═════════════════════════════════════════ */
    .show-sidebar { position: sticky; top: 5rem; display: flex; flex-direction: column; gap: 1.25rem; }

    /* ══ Card base ══════════════════════════════════════════════ */
    .show-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--r);
        box-shadow: var(--sh);
        overflow: hidden;
    }
    .show-card + .show-card { margin-top: 1.25rem; }
    .show-card-head {
        display: flex; align-items: center; gap: .65rem;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #F3F4F6;
        background: #FAFBFF;
    }
    .show-card-head-icon {
        width:28px; height:28px; border-radius:8px; flex-shrink:0;
        display:flex; align-items:center; justify-content:center;
    }
    .show-card-head h2 {
        font-size:.95rem; font-weight:800; color:var(--txt); margin:0;
        display:flex; align-items:center; gap:.5rem;
    }
    .show-card-body { padding: 1.5rem; }

    /* ══ Hero banner ════════════════════════════════════════════ */
    .show-hero {
        border-radius: var(--r);
        overflow: hidden;
        background: var(--card);
        border: 1px solid var(--border);
        box-shadow: var(--sh);
        margin-bottom: 1.25rem;
    }
    .show-hero-banner {
        background: linear-gradient(135deg, #1e1b4b 0%, #3730a3 45%, #4f46e5 75%, #818cf8 100%);
        padding: 1.75rem 1.75rem 2.75rem;
        position: relative;
        overflow: hidden;
    }
    .show-hero-banner::before {
        content: '';
        position: absolute; inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='30' cy='30' r='20'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
        pointer-events: none;
    }
    .show-hero-banner::after {
        content: '';
        position: absolute; bottom: -1px; left: 0; right: 0; height: 32px;
        background: var(--card);
        clip-path: ellipse(55% 100% at 50% 100%);
    }

    .show-hero-top { display: flex; align-items: flex-start; gap: 1rem; position: relative; z-index: 1; }
    .show-hero-logo-wrap { flex-shrink: 0; }
    .show-hero-logo {
        width: 72px; height: 72px; border-radius: 16px;
        object-fit: cover; border: 3px solid rgba(255,255,255,.25);
        box-shadow: 0 8px 24px rgba(0,0,0,.25);
        background: white;
    }
    .show-hero-logo-ph {
        width: 72px; height: 72px; border-radius: 16px;
        background: rgba(255,255,255,.15);
        border: 3px solid rgba(255,255,255,.25);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.75rem; font-weight: 900; color: white;
        box-shadow: 0 8px 24px rgba(0,0,0,.2);
        backdrop-filter: blur(4px);
    }
    .show-hero-info { flex: 1; min-width: 0; }
    .show-hero-company {
        font-size: .78rem; font-weight: 700; color: rgba(255,255,255,.7);
        text-transform: uppercase; letter-spacing: .06em; margin-bottom: .35rem;
    }
    .show-hero-title {
        font-size: 1.5rem; font-weight: 900; color: white;
        margin: 0 0 .6rem; line-height: 1.2;
        text-shadow: 0 1px 3px rgba(0,0,0,.2);
    }
    @media (max-width: 600px) { .show-hero-title { font-size: 1.2rem; } }

    .show-hero-tags {
        display: flex; flex-wrap: wrap; gap: .45rem;
        position: relative; z-index: 1; margin-top: .5rem;
    }

    /* ══ Tags / Badges ══════════════════════════════════════════ */
    .show-tag {
        display: inline-flex; align-items: center; gap: .3rem;
        padding: .28rem .75rem; border-radius: 99px;
        font-size: .72rem; font-weight: 700; border: 1px solid;
        white-space: nowrap;
    }
    /* Light tags (card context) */
    .show-tag-green  { color:#065F46; background:#ECFDF5; border-color:#A7F3D0; }
    .show-tag-amber  { color:#92400E; background:#FFFBEB; border-color:#FDE68A; }
    .show-tag-indigo { color:#3730A3; background:#EEF2FF; border-color:#C7D2FE; }
    .show-tag-purple { color:#5B21B6; background:#F5F3FF; border-color:#DDD6FE; }
    .show-tag-blue   { color:#1E40AF; background:#EFF6FF; border-color:#BFDBFE; }
    .show-tag-gray   { color:#374151; background:#F9FAFB; border-color:#E5E7EB; }
    /* Dark/glass tags (hero context) */
    .show-tag-hero {
        background: rgba(255,255,255,.15); border-color: rgba(255,255,255,.25);
        color: white; backdrop-filter: blur(4px);
    }
    .show-tag-urgency {
        background: #FEF3C7; border-color: #FDE68A; color: #92400E;
        animation: pulse-urgency 2s infinite;
    }
    @keyframes pulse-urgency { 0%,100%{ box-shadow:0 0 0 0 rgba(245,158,11,.3);} 50%{ box-shadow:0 0 0 4px rgba(245,158,11,0);} }
    .show-tag-expired { background: #FEF2F2; border-color: #FECACA; color: #991B1B; }

    /* ══ Hero pull-up card ══════════════════════════════════════ */
    .show-hero-pullup {
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: .75rem;
        padding: 1rem 1.75rem 1.25rem;
        background: white;
        margin-top: -1px;
    }
    .show-hero-meta { display: flex; align-items: center; gap: 1.25rem; flex-wrap: wrap; }
    .show-hero-meta-item {
        display: flex; align-items: center; gap: .4rem;
        font-size: .8rem; color: var(--muted); font-weight: 600;
    }
    .show-hero-meta-item svg { color: var(--p); flex-shrink: 0; }

    /* ══ Save / share buttons ═══════════════════════════════════ */
    .show-action-row { display: flex; align-items: center; gap: .5rem; flex-shrink: 0; }
    .show-btn-outline {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .45rem .9rem; border-radius: var(--r-sm);
        font-size: .78rem; font-weight: 700;
        border: 1.5px solid var(--border2); background: white;
        color: var(--muted); cursor: pointer; text-decoration: none;
        transition: all var(--tr);
    }
    .show-btn-outline:hover { border-color: var(--p); color: var(--p); background: var(--p-lt); }

    /* ══ Description prose ══════════════════════════════════════ */
    .show-section-title {
        display: flex; align-items: center; gap: .5rem;
        font-size: .7rem; font-weight: 800; text-transform: uppercase; letter-spacing: .08em;
        color: var(--muted); margin: 0 0 .9rem;
    }
    .show-section-title::after { content: ''; flex: 1; height: 1px; background: #F3F4F6; }
    .show-section-title svg { flex-shrink: 0; color: var(--p); }

    .show-prose {
        font-size: .9rem; color: #374151; line-height: 1.8;
        white-space: pre-line;
    }

    /* ══ Details grid ═══════════════════════════════════════════ */
    .show-details-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: .85rem;
    }
    @media (max-width: 700px) { .show-details-grid { grid-template-columns: 1fr 1fr; } }
    @media (max-width: 420px) { .show-details-grid { grid-template-columns: 1fr; } }

    .show-detail-chip {
        display: flex; align-items: center; gap: .75rem;
        background: var(--surf); border: 1px solid var(--border);
        border-radius: var(--r-sm); padding: .75rem 1rem;
        transition: box-shadow var(--tr), transform var(--tr);
    }
    .show-detail-chip:hover { box-shadow: var(--sh-md); transform: translateY(-1px); }
    .show-detail-chip-icon {
        width: 36px; height: 36px; border-radius: 8px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
    }
    .show-detail-chip-icon.indigo { background: var(--p-lt);  }
    .show-detail-chip-icon.indigo svg { color: var(--p); }
    .show-detail-chip-icon.green  { background: #F0FDF4; }
    .show-detail-chip-icon.green  svg { color: #16A34A; }
    .show-detail-chip-icon.blue   { background: #EFF6FF; }
    .show-detail-chip-icon.blue   svg { color: #2563EB; }
    .show-detail-chip-icon.purple { background: #FAF5FF; }
    .show-detail-chip-icon.purple svg { color: #9333EA; }
    .show-detail-chip-icon.amber  { background: #FFFBEB; }
    .show-detail-chip-icon.amber  svg { color: #D97706; }
    .show-detail-chip-icon.red    { background: #FEF2F2; }
    .show-detail-chip-icon.red    svg { color: #EF4444; }
    .show-detail-chip-label { font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: var(--muted); margin-bottom: .15rem; }
    .show-detail-chip-value { font-size: .875rem; font-weight: 800; color: var(--txt); }
    .show-detail-chip-value.red   { color: #DC2626; }

    /* ══ Keywords / tags row ════════════════════════════════════ */
    .show-keywords { display: flex; flex-wrap: wrap; gap: .4rem; }
    .show-keyword {
        display: inline-flex; align-items: center; gap: .25rem;
        padding: .25rem .7rem; border-radius: 99px;
        font-size: .72rem; font-weight: 600;
        background: #f1f5f9; border: 1px solid #e2e8f0; color: #475569;
        transition: background var(--tr), color var(--tr);
    }
    .show-keyword:hover { background: var(--p-lt); color: var(--p); border-color: #c7d2fe; }

    /* ══ Application form ═══════════════════════════════════════ */
    .show-form-group { margin-bottom: 1.25rem; }
    .show-label {
        display: flex; align-items: center; gap: .35rem;
        font-size: .72rem; font-weight: 800; text-transform: uppercase; letter-spacing: .06em;
        color: var(--muted); margin-bottom: .5rem;
    }
    .show-textarea {
        width: 100%; padding: .75rem 1rem;
        border: 1.5px solid var(--border2); border-radius: var(--r-sm);
        font-size: .875rem; font-family: inherit; color: var(--txt);
        resize: vertical; outline: none; background: var(--surf);
        transition: border-color var(--tr), box-shadow var(--tr), background var(--tr);
        min-height: 120px;
    }
    .show-textarea:focus { border-color: var(--p); background: white; box-shadow: 0 0 0 3px rgba(79,70,229,.1); }

    /* Char counter */
    .show-char-counter { font-size: .7rem; color: var(--muted); text-align: right; margin-top: .3rem; }
    .show-char-counter.warn { color: #F59E0B; }
    .show-char-counter.ok   { color: #10B981; }

    /* CV choice cards */
    .show-cv-grid { display: grid; grid-template-columns: 1fr 1fr; gap: .75rem; margin-bottom: .75rem; }
    @media (max-width: 420px) { .show-cv-grid { grid-template-columns: 1fr; } }

    .show-cv-card {
        border: 2px solid var(--border); border-radius: var(--r-sm);
        padding: 1rem; cursor: pointer;
        transition: border-color var(--tr), background var(--tr), box-shadow var(--tr);
        display: flex; flex-direction: column; gap: .3rem;
    }
    .show-cv-card:has(input:checked) {
        border-color: var(--p); background: var(--p-lt);
        box-shadow: 0 0 0 3px rgba(79,70,229,.1);
    }
    .show-cv-card-top { display: flex; justify-content: space-between; align-items: flex-start; }
    .show-cv-card-title { font-size: .85rem; font-weight: 800; color: var(--txt); }
    .show-cv-card-sub   { font-size: .75rem; color: var(--muted); }
    .show-cv-avail { font-size: .72rem; font-weight: 700; color: #059669; display:flex; align-items:center; gap:.2rem; }
    .show-cv-none  { font-size: .72rem; font-weight: 700; color: #DC2626; }

    /* Upload zone */
    .show-upload-zone {
        border: 2px dashed var(--border2); border-radius: var(--r-sm);
        padding: 1.25rem; background: var(--surf); text-align: center;
        font-size: .8rem; color: var(--muted); margin-bottom: .75rem;
        transition: border-color var(--tr), background var(--tr);
    }
    .show-upload-zone:hover { border-color: var(--p); background: var(--p-lt); }

    /* Submit button */
    .show-submit-btn {
        display: flex; align-items: center; justify-content: center; gap: .6rem;
        width: 100%; padding: .85rem 1.5rem;
        background: linear-gradient(135deg, var(--p), var(--p-mid));
        color: white; border: none; border-radius: var(--r-sm);
        font-size: .9rem; font-weight: 800; font-family: inherit; letter-spacing: .02em;
        cursor: pointer;
        box-shadow: 0 4px 14px rgba(79,70,229,.35);
        transition: opacity var(--tr), transform var(--tr), box-shadow var(--tr);
    }
    .show-submit-btn:hover { opacity: .93; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(79,70,229,.4); }
    .show-submit-btn:active { transform: translateY(0); }

    /* ══ Guest CTA card ═════════════════════════════════════════ */
    .show-guest-cta {
        text-align: center; padding: 2.25rem 1.5rem;
    }
    .show-guest-icon {
        width: 56px; height: 56px; border-radius: 16px;
        background: var(--p-lt); border: 1.5px solid #C7D2FE;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 1rem;
    }
    .show-guest-title { font-size: 1.05rem; font-weight: 800; color: var(--txt); margin: 0 0 .4rem; }
    .show-guest-sub   { font-size: .85rem; color: var(--muted); margin: 0 0 1.25rem; line-height: 1.55; }
    .show-guest-actions { display: flex; align-items: center; justify-content: center; gap: .75rem; flex-wrap: wrap; }
    .show-btn-ghost {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .6rem 1.25rem; border-radius: 8px;
        border: 1.5px solid var(--border2); background: white;
        color: #374151; font-size: .875rem; font-weight: 600;
        text-decoration: none; transition: all var(--tr);
    }
    .show-btn-ghost:hover { border-color: var(--p); color: var(--p); }
    .show-btn-solid {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .6rem 1.25rem; border-radius: 8px;
        background: var(--p); color: white;
        font-size: .875rem; font-weight: 700;
        text-decoration: none;
        box-shadow: 0 2px 8px rgba(79,70,229,.25);
        transition: all var(--tr);
    }
    .show-btn-solid:hover { background: var(--p-dk); box-shadow: 0 4px 12px rgba(79,70,229,.35); }

    /* ══ Application status banner ══════════════════════════════ */
    .show-status-banner { border-radius: var(--r-sm); overflow: hidden; border: 1.5px solid; }
    .show-status-head {
        display: flex; align-items: center; gap: .75rem;
        padding: .875rem 1rem; border-bottom: 1px solid rgba(0,0,0,.06);
    }
    .show-status-icon { width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .show-status-body { padding: .875rem 1rem; display: flex; align-items: center; justify-content: space-between; gap: .5rem; flex-wrap: wrap; }
    .show-status-dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; margin-right: .4rem; flex-shrink: 0; }
    .show-status-pill { display: inline-flex; align-items: center; padding: .25rem .85rem; border-radius: 99px; font-size: .75rem; font-weight: 800; border: 1.5px solid; }
    .show-status-foot { display: flex; align-items: center; gap: .4rem; padding: .5rem 1rem; font-size: .75rem; font-weight: 500; border-top: 1px solid rgba(0,0,0,.06); }

    /* ══ Company card sidebar ═══════════════════════════════════ */
    .show-company-logo {
        width: 56px; height: 56px; border-radius: 12px;
        object-fit: cover; border: 1.5px solid var(--border);
        box-shadow: 0 2px 8px rgba(0,0,0,.08); background: white; flex-shrink: 0;
    }
    .show-company-logo-ph {
        width: 56px; height: 56px; border-radius: 12px; flex-shrink: 0;
        background: var(--p-lt); border: 1.5px solid #C7D2FE;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.25rem; font-weight: 900; color: var(--p);
        box-shadow: 0 2px 8px rgba(79,70,229,.12);
    }
    .show-company-name { font-size: 1rem; font-weight: 800; color: var(--p); }
    .show-company-link {
        display: inline-flex; align-items: center; gap: .35rem; margin-top: .85rem;
        font-size: .8rem; font-weight: 700; color: var(--p);
        text-decoration: none; transition: color var(--tr);
        padding: .4rem .85rem; border-radius: 8px; border: 1.5px solid #C7D2FE;
        background: var(--p-lt); width: 100%; justify-content: center;
    }
    .show-company-link:hover { background: var(--p); color: white; border-color: var(--p); }

    /* ══ Progress bar (similar offers) ═════════════════════════ */
    .show-progress-bar { height: 4px; border-radius: 99px; background: #E5E7EB; overflow: hidden; }
    .show-progress-fill { height: 100%; border-radius: 99px; background: linear-gradient(90deg, var(--p), var(--p-mid)); transition: width 1s ease; }

    /* ══ Company actions card ══════════════════════════════════ */
    .show-actions-card {
        background: #fff; border: 1px solid var(--border);
        border-radius: var(--r); box-shadow: var(--sh); overflow: hidden;
    }
    .show-actions-head {
        display: flex; align-items: center; gap: .65rem;
        padding: 1rem 1.5rem; border-bottom: 1px solid #F3F4F6; background: #FAFBFF;
    }
    .show-actions-body { padding: 1rem 1.25rem; display: flex; flex-direction: column; gap: .6rem; }
    .show-act-btn {
        display: inline-flex; align-items: center; justify-content: center; gap: .5rem;
        width: 100%; padding: .55rem 1rem; border-radius: 8px;
        font-size: .82rem; font-weight: 700; font-family: inherit;
        cursor: pointer; text-decoration: none; border: 1.5px solid;
        transition: all var(--tr);
    }
    .show-act-edit {
        background: var(--p-lt); color: var(--p); border-color: #C7D2FE;
    }
    .show-act-edit:hover { background: var(--p); color: white; border-color: var(--p); }
    .show-act-archive {
        background: #FEF2F2; color: #DC2626; border-color: #FECACA;
    }
    .show-act-archive:hover { background: #FEE2E2; border-color: #EF4444; }
    .show-act-unarchive {
        background: #EFF6FF; color: #1D4ED8; border-color: #BFDBFE;
    }
    .show-act-unarchive:hover { background: #DBEAFE; border-color: #3B82F6; }

    /* ══ Modal ══════════════════════════════════════════════════ */
    .modal-backdrop { display:none; position:fixed; inset:0; z-index:500; background:rgba(15,23,42,.45); backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:1rem; }
    .modal-backdrop.open { display:flex; }
    .modal { background:#fff; border-radius:16px; width:100%; max-width:420px; border:1.5px solid var(--border); box-shadow:0 20px 60px rgba(0,0,0,.18); overflow:hidden; }
    .modal-head { display:flex; align-items:flex-start; gap:1rem; padding:1.25rem 1.5rem; border-bottom:1px solid var(--border); }
    .modal-ico { width:42px; height:42px; border-radius:10px; flex-shrink:0; display:flex; align-items:center; justify-content:center; }
    .modal-title { font-size:1rem; font-weight:800; color:var(--txt); margin-bottom:.2rem; }
    .modal-msg { font-size:.85rem; color:var(--muted); line-height:1.55; margin:0; }
    .modal-foot { display:flex; justify-content:flex-end; gap:.75rem; padding:1rem 1.5rem; background:var(--surf); }
    .modal-cancel { padding:.53rem 1.2rem; border-radius:8px; border:1.5px solid var(--border); background:#fff; font-size:.85rem; font-weight:600; color:var(--muted); cursor:pointer; transition:all var(--tr); font-family:inherit; }
    .modal-cancel:hover { border-color:var(--p); color:var(--p); }
    .modal-ok { padding:.53rem 1.2rem; border-radius:8px; border:none; font-size:.85rem; font-weight:700; color:#fff; cursor:pointer; transition:opacity var(--tr); font-family:inherit; }
    .modal-ok:hover { opacity:.9; }

    /* ══ Report card ════════════════════════════════════════════ */
    .show-report-card {
        background: #FEF2F2; border: 1.5px solid #FECACA;
        border-radius: var(--r); padding: 1rem 1.25rem;
        display: flex; align-items: center; justify-content: space-between; gap: .75rem; flex-wrap: wrap;
    }
    .show-report-btn {
        display: inline-flex; align-items: center; gap: .35rem;
        padding: .4rem .875rem; border-radius: 8px;
        font-size: .8rem; font-weight: 700; color: #DC2626;
        border: 1.5px solid #FECACA; background: white;
        text-decoration: none; white-space: nowrap;
        transition: all var(--tr);
    }
    .show-report-btn:hover { background: #FEE2E2; border-color: #EF4444; }

    /* ══ Animations ═════════════════════════════════════════════ */
    @keyframes fadeUp { from { opacity:0; transform:translateY(14px); } to { opacity:1; transform:none; } }
    .show-anim { animation: fadeUp .35s ease both; }
    .show-anim-d1 { animation-delay: .05s; }
    .show-anim-d2 { animation-delay: .1s; }
    .show-anim-d3 { animation-delay: .15s; }
    .show-anim-d4 { animation-delay: .2s; }
    </style>

    <div class="show-page">

        {{-- ══════════════  LEFT COLUMN  ══════════════ --}}
        <div>

            {{-- ── Hero Banner ── --}}
            <div class="show-hero show-anim">
                <div class="show-hero-banner">
                    <div class="show-hero-top">
                        <div class="show-hero-logo-wrap">
                            @if($jobOffer->company->logo_path)
                                <img src="{{ $jobOffer->company->logo_url }}" alt="Logo {{ $jobOffer->company->name }}" class="show-hero-logo">
                            @else
                                <div class="show-hero-logo-ph" aria-hidden="true">{{ $companyInitial }}</div>
                            @endif
                        </div>
                        <div class="show-hero-info">
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
                        @if($expired)
                            <span class="show-tag show-tag-expired">⛔ Expirée</span>
                        @elseif($urgentExpiry)
                            <span class="show-tag show-tag-urgency">⚡ Expire dans {{ $expiryText }}</span>
                        @endif
                    </div>
                </div>

                {{-- Pull-up meta row --}}
                <div class="show-hero-pullup">
                    <div class="show-hero-meta">
                        @if($jobOffer->salary)
                            <div class="show-hero-meta-item">
                                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                </svg>
                                <strong style="color:var(--txt);">{{ number_format($jobOffer->salary, 0, ',', ' ') }} DT</strong>
                                <span>/mois</span>
                            </div>
                        @endif
                        <div class="show-hero-meta-item">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $jobOffer->vacancies }} poste(s) vacant(s)
                        </div>
                        <div class="show-hero-meta-item">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Publiée le {{ $jobOffer->created_at->format('d/m/Y') }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Description & Requirements ── --}}
            <div class="show-card show-anim show-anim-d1" style="margin-bottom:1.25rem;">
                <div class="show-card-head">
                    <div class="show-card-head-icon indigo" style="background:#EEF2FF;">
                        <svg width="15" height="15" fill="none" stroke="#4F46E5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h2>Description du poste</h2>
                </div>
                <div class="show-card-body">
                    <div class="show-prose" style="margin-bottom:2rem;">{{ $jobOffer->description }}</div>

                    <p class="show-section-title">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        Exigences &amp; Compétences
                    </p>
                    <div class="show-prose">{{ $jobOffer->requirements }}</div>

                    @if($jobOffer->keywords)
                        <p class="show-section-title" style="margin-top:1.75rem;">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                            </svg>
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

            {{-- ── Details chips ── --}}
            <div class="show-card show-anim show-anim-d2" style="margin-bottom:1.25rem;">
                <div class="show-card-head">
                    <div class="show-card-head-icon" style="background:#F0FDF4;">
                        <svg width="15" height="15" fill="none" stroke="#16A34A" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <h2>Détails de l'offre</h2>
                </div>
                <div class="show-card-body">
                    <div class="show-details-grid">

                        @if($categoryName)
                        <div class="show-detail-chip">
                            <div class="show-detail-chip-icon indigo">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l5 5a2 2 0 01.586 1.414V19a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="show-detail-chip-label">Catégorie</div>
                                <div class="show-detail-chip-value">{{ $categoryName }}</div>
                            </div>
                        </div>
                        @endif

                        <div class="show-detail-chip">
                            <div class="show-detail-chip-icon blue">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="show-detail-chip-label">Niveau d'étude</div>
                                <div class="show-detail-chip-value">{{ $jobOffer->education_level }}</div>
                            </div>
                        </div>

                        <div class="show-detail-chip">
                            <div class="show-detail-chip-icon green">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="show-detail-chip-label">Expérience</div>
                                <div class="show-detail-chip-value">{{ $jobOffer->experience_years }} an(s)</div>
                            </div>
                        </div>

                        <div class="show-detail-chip">
                            <div class="show-detail-chip-icon purple">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="show-detail-chip-label">Postes vacants</div>
                                <div class="show-detail-chip-value">{{ $jobOffer->vacancies }}</div>
                            </div>
                        </div>

                        @if($jobOffer->languages)
                        <div class="show-detail-chip">
                            <div class="show-detail-chip-icon amber">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                                </svg>
                            </div>
                            <div>
                                <div class="show-detail-chip-label">Langues</div>
                                <div class="show-detail-chip-value">{{ $jobOffer->languages }}</div>
                            </div>
                        </div>
                        @endif

                        <div class="show-detail-chip">
                            <div class="show-detail-chip-icon red">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="show-detail-chip-label" style="color:#EF4444;">Expiration</div>
                                <div class="show-detail-chip-value red">
                                    {{ \Carbon\Carbon::parse($jobOffer->expiration_date)->format('d/m/Y') }}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- ── Application section ── --}}
            @guest
                <div class="show-card show-anim show-anim-d3">
                    <div class="show-card-head">
                        <div class="show-card-head-icon" style="background:#EEF2FF;">
                            <svg width="15" height="15" fill="none" stroke="#4F46E5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                        </div>
                        <h2>Postuler à cette offre</h2>
                    </div>
                    <div class="show-card-body show-guest-cta">
                        <div class="show-guest-icon">
                            <svg width="24" height="24" fill="none" stroke="#4F46E5" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <h3 class="show-guest-title">Connectez-vous pour postuler</h3>
                        <p class="show-guest-sub">Créez un compte candidat gratuit ou connectez-vous pour envoyer votre candidature en quelques clics.</p>
                        <div class="show-guest-actions">
                            <a href="{{ route('login') }}" class="show-btn-ghost">Se connecter</a>
                            <a href="{{ route('register') }}" class="show-btn-solid">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Créer un compte
                            </a>
                        </div>
                    </div>
                </div>
            @endguest

            @if(Auth::check() && Auth::user()->isCandidate())
                <div class="show-card show-anim show-anim-d3">
                    <div class="show-card-head">
                        <div class="show-card-head-icon" style="background:#EEF2FF;">
                            <svg width="15" height="15" fill="none" stroke="#4F46E5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                        </div>
                        <h2>Soumettre ma candidature</h2>
                    </div>
                    <div class="show-card-body">

                        @if(Auth::user()->applications->where('job_offer_id', $jobOffer->id)->count() > 0)
                            @php
                                $application = Auth::user()->applications->where('job_offer_id', $jobOffer->id)->first();
                                $appStatus = $application->status;
                                $sc = [
                                    'pending'  => ['label'=>'En attente',  'desc'=>"L'entreprise examine votre candidature.", 'dot'=>'#F59E0B','bg'=>'#FFFBEB','color'=>'#92400E','border'=>'#FDE68A','icon_bg'=>'#FEF9C3','icon_c'=>'#B45309'],
                                    'accepted' => ['label'=>'Acceptée',    'desc'=>"Félicitations ! L'entreprise souhaite vous rencontrer.", 'dot'=>'#22C55E','bg'=>'#F0FDF4','color'=>'#166534','border'=>'#BBF7D0','icon_bg'=>'#DCFCE7','icon_c'=>'#16A34A'],
                                    'rejected' => ['label'=>'Non retenue', 'desc'=>"L'entreprise n'a pas donné suite à votre profil.", 'dot'=>'#EF4444','bg'=>'#FEF2F2','color'=>'#991B1B','border'=>'#FECACA','icon_bg'=>'#FEE2E2','icon_c'=>'#DC2626'],
                                ][$appStatus] ?? ['label'=>ucfirst($appStatus),'desc'=>'','dot'=>'#9CA3AF','bg'=>'#F9FAFB','color'=>'#374151','border'=>'#E5E7EB','icon_bg'=>'#F3F4F6','icon_c'=>'#9CA3AF'];
                            @endphp

                            <div class="show-status-banner" style="border-color:{{ $sc['border'] }};">
                                <div class="show-status-head" style="background:{{ $sc['icon_bg'] }};">
                                    <div class="show-status-icon" style="background:{{ $sc['icon_bg'] }}; border:1.5px solid {{ $sc['border'] }};">
                                        <svg width="16" height="16" fill="none" stroke="{{ $sc['icon_c'] }}" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div style="font-size:.875rem; font-weight:800; color:#111;">Candidature déjà envoyée</div>
                                        <div style="font-size:.75rem; color:var(--muted); margin-top:2px;">Vous avez soumis votre profil pour cette offre</div>
                                    </div>
                                </div>
                                <div class="show-status-body" style="background:{{ $sc['bg'] }};">
                                    <div style="display:flex; align-items:center;">
                                        <span class="show-status-dot" style="background:{{ $sc['dot'] }};"></span>
                                        <div>
                                            <div style="font-size:.9rem; font-weight:800; color:{{ $sc['color'] }};">{{ $sc['label'] }}</div>
                                            <div style="font-size:.775rem; color:{{ $sc['color'] }}; opacity:.8; margin-top:2px;">{{ $sc['desc'] }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="show-status-foot" style="background:{{ $sc['bg'] }}; color:{{ $sc['color'] }};">
                                    <svg width="12" height="12" fill="none" stroke="{{ $sc['dot'] }}" stroke-width="2" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                                    </svg>
                                    Candidature envoyée le {{ $application->created_at->format('d/m/Y') }}
                                </div>
                            </div>

                        @else
                            <form method="POST" action="{{ route('applications.store', $jobOffer) }}" enctype="multipart/form-data" id="apply-form">
                                @csrf

                                {{-- Motivation letter (file upload) --}}
                                <div class="show-form-group">
                                    <label class="show-label">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        Lettre de motivation
                                        <span style="font-weight:400;font-size:.75rem;color:#9CA3AF;">(optionnelle)</span>
                                    </label>
                                    <div id="cover-letter-zone" class="show-upload-zone"
                                         style="cursor:pointer;"
                                         onclick="document.getElementById('cover_letter').click()">
                                        <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                             style="margin:0 auto .5rem;color:#9CA3AF;display:block;"
                                             id="cover-letter-icon">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                        </svg>
                                        <p id="cover-letter-label"
                                           style="font-size:.82rem;font-weight:600;color:#6B7280;text-align:center;margin:0;">
                                            <span style="color:#4F46E5;">Choisir un fichier</span> ou glisser ici
                                        </p>
                                        <p id="cover-letter-hint"
                                           style="font-size:.7rem;color:#9CA3AF;text-align:center;margin:.35rem 0 0;">
                                            PDF, DOC, DOCX · Max 5 Mo
                                        </p>
                                    </div>
                                    <input type="file" id="cover_letter" name="cover_letter"
                                           accept=".pdf,.doc,.docx" style="display:none;"
                                           onchange="showCoverLetterFile(this)">
                                    <x-input-error :messages="$errors->get('cover_letter')" class="mt-2"/>
                                </div>

                                {{-- CV selection --}}
                                <div class="show-form-group">
                                    <label class="show-label">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        Sélection du CV
                                    </label>
                                    <div class="show-cv-grid">
                                        <label class="show-cv-card">
                                            <div class="show-cv-card-top">
                                                <span class="show-cv-card-title">Mon CV actuel</span>
                                                <input type="radio" name="cv_type" value="existing"
                                                       style="accent-color:#4F46E5; width:16px; height:16px;"
                                                       {{ old('cv_type','existing') == 'existing' ? 'checked' : '' }}
                                                       onchange="toggleNewCv(false)">
                                            </div>
                                            <span class="show-cv-card-sub">
                                                @if(Auth::user()->cv_path)
                                                    <span class="show-cv-avail">
                                                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                        CV disponible
                                                    </span>
                                                    <a href="{{ route('profile.cv.download') }}"
                                                       target="_blank"
                                                       onclick="event.stopPropagation();"
                                                       style="display:inline-flex;align-items:center;gap:.25rem;margin-top:.45rem;font-size:.7rem;font-weight:700;color:#4F46E5;background:#EEF2FF;border:1px solid #C7D2FE;border-radius:5px;padding:.18rem .5rem;text-decoration:none;transition:all .15s;"
                                                       onmouseover="this.style.background='#4F46E5';this.style.color='#fff';"
                                                       onmouseout="this.style.background='#EEF2FF';this.style.color='#4F46E5';">
                                                        <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                                        Consulter mon CV
                                                    </a>
                                                @else
                                                    <span class="show-cv-none">Aucun CV enregistré</span>
                                                @endif
                                            </span>
                                        </label>

                                        <label class="show-cv-card">
                                            <div class="show-cv-card-top">
                                                <span class="show-cv-card-title">Nouveau CV</span>
                                                <input type="radio" name="cv_type" value="new"
                                                       style="accent-color:#4F46E5; width:16px; height:16px;"
                                                       {{ old('cv_type') == 'new' ? 'checked' : '' }}
                                                       onchange="toggleNewCv(true)">
                                            </div>
                                            <span class="show-cv-card-sub">Spécifique pour ce poste</span>
                                        </label>
                                    </div>
                                    <x-input-error :messages="$errors->get('cv_type')" class="mt-2"/>
                                </div>

                                {{-- New CV upload --}}
                                <div id="new_cv_container"
                                     style="{{ old('cv_type') == 'new' ? '' : 'display:none;' }}margin-bottom:.75rem;">
                                    <div id="new-cv-zone" class="show-upload-zone"
                                         style="cursor:pointer;"
                                         onclick="document.getElementById('new_cv').click()">
                                        <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                             style="margin:0 auto .5rem;color:#9CA3AF;display:block;"
                                             id="new-cv-icon">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                        </svg>
                                        <p id="new-cv-label"
                                           style="font-size:.82rem;font-weight:600;color:#6B7280;text-align:center;margin:0;">
                                            <span style="color:#4F46E5;">Choisir un fichier</span> ou glisser ici
                                        </p>
                                        <p id="new-cv-hint"
                                           style="font-size:.7rem;color:#9CA3AF;text-align:center;margin:.35rem 0 0;">
                                            PDF uniquement · Max 5 Mo
                                        </p>
                                    </div>
                                    <input type="file" name="new_cv" id="new_cv" accept=".pdf"
                                           style="display:none;"
                                           onchange="showNewCvFile(this)">
                                    <x-input-error :messages="$errors->get('new_cv')" class="mt-2"/>
                                </div>

                                <button type="submit" class="show-submit-btn" id="apply-btn">
                                    
                                    Envoyer ma candidature
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endif

        </div>

        {{-- ══════════════  SIDEBAR  ══════════════ --}}
        <aside class="show-sidebar show-anim show-anim-d2">

            {{-- Company card --}}
            <div class="show-card">
                <div class="show-card-head">
                    <div class="show-card-head-icon" style="background:#EFF6FF;">
                        <svg width="15" height="15" fill="none" stroke="#2563EB" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <h2>À propos de l'entreprise</h2>
                </div>
                <div class="show-card-body">
                    <div style="display:flex; align-items:center; gap:.875rem; margin-bottom:1rem;">
                        @if($jobOffer->company->logo_path)
                            <img src="{{ $jobOffer->company->logo_url }}" alt="Logo" class="show-company-logo">
                        @else
                            <div class="show-company-logo-ph" aria-hidden="true">{{ $companyInitial }}</div>
                        @endif
                        <div>
                            <div class="show-company-name">{{ $jobOffer->company->name }}</div>
                            @if($jobOffer->company->website)
                                <div style="font-size:.72rem; color:var(--muted); margin-top:.15rem;">{{ parse_url($jobOffer->company->website, PHP_URL_HOST) }}</div>
                            @endif
                        </div>
                    </div>

                    @if($jobOffer->company->bio)
                        <p style="font-size:.83rem; color:var(--muted); line-height:1.7; margin-bottom:.75rem;">{{ $jobOffer->company->bio }}</p>
                    @endif

                    @if($jobOffer->company->website)
                        <a href="{{ $jobOffer->company->website }}" target="_blank" rel="noopener" class="show-company-link">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                            Visiter le site web
                        </a>
                    @endif
                </div>
            </div>

            {{-- Company owner actions card --}}
            @if(Auth::check() && Auth::user()->isCompany() && $jobOffer->user_id === Auth::id())
                @php $isArchived = $jobOffer->status === 'archived'; @endphp
                <div class="show-actions-card">
                    <div class="show-actions-head">
                        <div class="show-card-head-icon" style="background:#F3F4F6;">
                            <svg width="15" height="15" fill="none" stroke="#374151" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                            </svg>
                        </div>
                        <h2 style="font-size:.95rem; font-weight:800; color:var(--txt); margin:0;">Gestion de l'offre</h2>
                    </div>
                    <div class="show-actions-body">
                        <a href="{{ route('job-offers.edit', $jobOffer) }}" class="show-act-btn show-act-edit">
                            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                            Modifier l'offre
                        </a>
                        @if($isArchived)
                            <form method="POST" action="{{ route('job-offers.unarchive', $jobOffer) }}"
                                  class="js-restore-form" data-title="{{ $jobOffer->title }}" style="display:contents;">
                                @csrf
                                <button type="submit" class="show-act-btn show-act-unarchive">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <polyline stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="21 8 21 21 3 21 3 8"/>
                                        <rect x="1" y="3" width="22" height="5" stroke-width="2"/>
                                        <polyline stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="10 12 12 14 14 12"/>
                                        <line x1="12" y1="8" x2="12" y2="14" stroke-width="2"/>
                                    </svg>
                                    Désarchiver
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('job-offers.archive', $jobOffer) }}"
                                  class="js-archive-form" data-title="{{ $jobOffer->title }}" style="display:contents;">
                                @csrf
                                <button type="submit" class="show-act-btn show-act-archive">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <polyline stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="21 8 21 21 3 21 3 8"/>
                                        <rect x="1" y="3" width="22" height="5" stroke-width="2"/>
                                        <line x1="10" y1="12" x2="14" y2="12" stroke-width="2"/>
                                    </svg>
                                    Archiver
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Report card (only if applied to company) --}}
            @if($hasAppliedToCompany)
                <div class="show-report-card">
                    <div>
                        <div style="font-size:.83rem; font-weight:800; color:#991B1B;">Un problème avec cette entreprise ?</div>
                        <div style="font-size:.72rem; color:#DC2626; margin-top:.15rem;">Signalez-le à notre équipe de modération.</div>
                    </div>
                    <a href="{{ route('reports.create', $jobOffer->company) }}" class="show-report-btn">
                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                        </svg>
                        Signaler
                    </a>
                </div>
            @endif

        </aside>

    </div>{{-- /.show-page --}}

{{-- Confirmation modal --}}
@if(Auth::check() && Auth::user()->isCompany() && $jobOffer->user_id === Auth::id())
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
@endif

@push('scripts')
<script>
    function showCoverLetterFile(input) {
        if (!input.files || !input.files[0]) return;
        const file = input.files[0];
        const zone = document.getElementById('cover-letter-zone');
        const lbl  = document.getElementById('cover-letter-label');
        const hint = document.getElementById('cover-letter-hint');
        const icon = document.getElementById('cover-letter-icon');
        const name = file.name.length > 36 ? file.name.substring(0, 33) + '…' : file.name;
        const size = file.size < 1024 * 1024
            ? (file.size / 1024).toFixed(0) + ' Ko'
            : (file.size / 1024 / 1024).toFixed(1) + ' Mo';
        if (icon) { icon.setAttribute('stroke', '#22C55E'); icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'; }
        if (lbl)  lbl.innerHTML = '<strong style="color:#111827;">' + name + '</strong>';
        if (hint) { hint.textContent = size + ' · Cliquez pour changer'; hint.style.color = '#059669'; }
        if (zone) { zone.style.borderColor = '#22C55E'; zone.style.background = '#F0FDF4'; }
    }

    function toggleNewCv(show) {
        const el = document.getElementById('new_cv_container');
        if (el) el.style.display = show ? 'block' : 'none';
    }

    function showNewCvFile(input) {
        if (!input.files || !input.files[0]) return;
        const file = input.files[0];
        const zone = document.getElementById('new-cv-zone');
        const lbl  = document.getElementById('new-cv-label');
        const hint = document.getElementById('new-cv-hint');
        const icon = document.getElementById('new-cv-icon');
        const name = file.name.length > 36 ? file.name.substring(0, 33) + '…' : file.name;
        const size = file.size < 1024 * 1024
            ? (file.size / 1024).toFixed(0) + ' Ko'
            : (file.size / 1024 / 1024).toFixed(1) + ' Mo';
        if (icon) { icon.setAttribute('stroke', '#22C55E'); icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'; }
        if (lbl)  lbl.innerHTML = '<strong style="color:#111827;">' + name + '</strong>';
        if (hint) { hint.textContent = size + ' · Cliquez pour changer'; hint.style.color = '#059669'; }
        if (zone) { zone.style.borderColor = '#22C55E'; zone.style.background = '#F0FDF4'; }
    }

    document.addEventListener('DOMContentLoaded', () => {
        // Animate cards on scroll
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.style.opacity = '1';
                    e.target.style.transform = 'none';
                    observer.unobserve(e.target);
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.show-anim').forEach(el => {
            el.style.willChange = 'opacity, transform';
            observer.observe(el);
        });
    });

    // Archive/restore confirmation modal
    (function () {
        var modal   = document.getElementById('confirm-modal');
        if (!modal) return;
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
                ? "L'offre sera masquée des résultats de recherche. Vous pourrez la restaurer à tout moment."
                : "L'offre sera de nouveau visible par les candidats.";

            btnOk.textContent      = isArchive ? 'Archiver' : 'Restaurer';
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

        document.querySelectorAll('.js-archive-form').forEach(function (f) {
            f.onsubmit = function (e) { e.preventDefault(); open(f, 'archive'); };
        });
        document.querySelectorAll('.js-restore-form').forEach(function (f) {
            f.onsubmit = function (e) { e.preventDefault(); open(f, 'restore'); };
        });
    })();
</script>
@endpush

</x-app-layout>