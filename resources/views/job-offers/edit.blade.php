<x-app-layout>
    <x-slot name="header">
        @php
            $editL0 = session('nav_level0') ?? ['url' => route('job-offers.my-offers'), 'label' => 'Mes offres'];
            $editL1 = session('nav_level1');
            $editBreadcrumbs = [
                ['label' => $editL0['label'], 'url' => $editL0['url']],
            ];
            if ($editL1) {
                $editBreadcrumbs[] = ['label' => Str::limit($jobOffer->title, 30), 'url' => $editL1['url']];
            }
            $editBreadcrumbs[] = ['label' => 'Modifier l\'offre'];
        @endphp
        <x-page-header
            title="Modifier l'offre"
            :subtitle="$jobOffer->title"
            icon="pencil"
            :breadcrumbs="$editBreadcrumbs"
        />
    </x-slot>

    <style>
        :root {
            --p:#4F46E5; --p-dark:#3730A3; --p-light:#EEF2FF;
            --border:#E5E7EB; --card:#FFFFFF; --surface:#F8F9FC;
            --txt:#111827; --muted:#6B7280;
            --r:14px; --shadow:0 1px 3px rgba(0,0,0,.07);
        }

        .cf-page { max-width:80rem; margin:0 auto; padding:2rem 1.5rem; }
        @media(max-width:640px) { .cf-page { padding:1rem; } }

        /* Edit banner */
        .cf-edit-banner {
            display:flex; align-items:center; gap:.75rem;
            padding:.875rem 1.25rem; border-radius:10px;
            background:#FFFBEB; border:1px solid #FDE68A;
            margin-bottom:1.5rem; font-size:.875rem; font-weight:600; color:#92400E;
        }

        /* Main card */
        .cf-card { background:var(--card); border-radius:var(--r); border:1px solid var(--border); box-shadow:var(--shadow); overflow:hidden; }

        .cf-section { padding:1.5rem 2rem; border-bottom:1px solid #F3F4F6; }
        @media(max-width:640px) { .cf-section { padding:1.25rem 1rem; } }

        .cf-section-title {
            display:flex; align-items:center; gap:.5rem;
            font-size:.8rem; font-weight:800; text-transform:uppercase; letter-spacing:.07em;
            color:var(--muted); margin:0 0 1.25rem;
        }
        .cf-section-title span {
            display:flex; align-items:center; justify-content:center;
            width:22px; height:22px; border-radius:6px;
            background:var(--p-light); color:var(--p); font-size:.7rem; font-weight:900;
        }

        /* Grid */
        .cf-grid { display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; }
        @media(max-width:580px) { .cf-grid { grid-template-columns:1fr; } }
        .cf-span-2 { grid-column:1/-1; }

        /* Fields */
        .cf-field {}
        .cf-label {
            display:block; font-size:.75rem; font-weight:700;
            text-transform:uppercase; letter-spacing:.06em; color:var(--muted); margin-bottom:.4rem;
        }
        .cf-label .cf-req { color:#EF4444; }

        .cf-input, .cf-select, .cf-textarea {
            display:block; width:100%; padding:.6rem .875rem;
            border:1.5px solid var(--border); border-radius:8px;
            font-size:.875rem; font-family:inherit; color:var(--txt);
            background-color:#fff; outline:none;
            transition:border-color .15s, box-shadow .15s;
        }
        .cf-select {
            appearance:none; -webkit-appearance:none;
            background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236B7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat:no-repeat;
            background-position:right .6rem center;
            background-size:1rem;
            padding-right:2.2rem;
        }
        .cf-input:focus, .cf-select:focus, .cf-textarea:focus {
            border-color:var(--p); box-shadow:0 0 0 3px rgba(79,70,229,.1);
        }
        .cf-textarea { resize:vertical; }

        /* Input with suffix */
        .cf-input-wrap { display:flex; align-items:stretch; }
        .cf-input-suffix {
            display:flex; align-items:center; padding:0 .75rem;
            background:#F3F4F6; border:1.5px solid var(--border); border-left:none;
            border-radius:0 8px 8px 0;
            font-size:.8rem; font-weight:600; color:var(--muted);
            pointer-events:none; white-space:nowrap; flex-shrink:0;
        }
        .cf-input-wrap .cf-input {
            border-radius:8px 0 0 8px;
            padding-left:.875rem;
        }
        .cf-input-wrap .cf-input:focus {
            border-color:var(--p); box-shadow:0 0 0 3px rgba(79,70,229,.1);
            z-index:1; position:relative;
        }

        /* Hint text */
        .cf-hint { font-size:.7rem; color:#9CA3AF; margin-top:.35rem; }

        /* Radio cards */
        .cf-radio-group { display:flex; flex-wrap:wrap; gap:.6rem; }
        .cf-radio-card {
            display:flex; align-items:center; gap:.5rem;
            padding:.5rem .875rem; border-radius:8px;
            border:1.5px solid var(--border); cursor:pointer;
            font-size:.8rem; font-weight:600; color:var(--muted);
            transition:all .15s;
        }
        .cf-radio-card:has(input:checked) { border-color:var(--p); background:var(--p-light); color:var(--p); }
        .cf-radio-card input { display:none; }

        /* Footer */
        .cf-footer {
            display:flex; align-items:center; justify-content:space-between;
            padding:1.25rem 2rem; gap:1rem; flex-wrap:wrap;
        }
        @media(max-width:640px) { .cf-footer { padding:1rem; } }

        .cf-btn-cancel {
            display:inline-flex; align-items:center; gap:.4rem;
            padding:.6rem 1.25rem; border-radius:8px;
            border:1.5px solid var(--border); background:#fff; color:var(--muted);
            font-size:.875rem; font-weight:600; font-family:inherit;
            text-decoration:none; cursor:pointer; transition:all .15s;
        }
        .cf-btn-cancel:hover { border-color:var(--p); color:var(--p); background:var(--p-light); }

        .cf-btn-submit {
            display:inline-flex; align-items:center; gap:.5rem;
            padding:.65rem 1.75rem; border-radius:8px;
            background:linear-gradient(135deg, var(--p), #818CF8);
            color:#fff; border:none; border-radius:10px;
            font-size:.9rem; font-weight:700; font-family:inherit;
            cursor:pointer; transition:opacity .15s;
            box-shadow:0 4px 12px rgba(79,70,229,.3);
        }
        .cf-btn-submit:hover { opacity:.9; }
    </style>

    <div class="cf-page">

        {{-- Edit warning banner --}}
        <div class="cf-edit-banner">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            Vous modifiez : <strong style="color:#92400E;">{{ $jobOffer->title }}</strong>
        </div>

        <div class="cf-card">
            <form method="POST" action="{{ route('job-offers.update', $jobOffer) }}">
                @csrf
                @method('PATCH')

                {{-- Section 1 : Informations de base --}}
                <div class="cf-section">
                    <p class="cf-section-title">
                        <span>1</span>
                        Informations de base
                    </p>
                    <div class="cf-grid">
                        <div class="cf-field cf-span-2">
                            <label class="cf-label" for="title">Titre de l'offre <span class="cf-req">*</span></label>
                            <input id="title" name="title" type="text" class="cf-input"
                                   value="{{ old('title', $jobOffer->title) }}" required autofocus
                                   placeholder="Ex : Développeur Full Stack Laravel">
                            <x-input-error :messages="$errors->get('title')" class="mt-2"/>
                        </div>

                        <div class="cf-field">
                            <label class="cf-label" for="category_id">Catégorie <span class="cf-req">*</span></label>
                            <select id="category_id" name="category_id" class="cf-select" required>
                                <option value="">Sélectionner une catégorie</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $jobOffer->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category_id')" class="mt-2"/>
                        </div>

                        <div class="cf-field">
                            <label class="cf-label" for="location">Ville <span class="cf-req">*</span></label>
                            <select id="location" name="location" class="cf-select" required>
                                <option value="">Sélectionner une ville</option>
                                @foreach(['Tunis','Ariana','Ben Arous','Manouba','Nabeul','Sousse','Monastir','Mahdia','Sfax','Bizerte','Kairouan','Gafsa','Gabès','Djerba'] as $city)
                                    <option value="{{ $city }}" {{ old('location', $jobOffer->location) == $city ? 'selected' : '' }}>{{ $city }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('location')" class="mt-2"/>
                        </div>
                    </div>
                </div>

                {{-- Section 2 : Contrat & Prérequis --}}
                <div class="cf-section">
                    <p class="cf-section-title">
                        <span>2</span>
                        Contrat &amp; Prérequis
                    </p>
                    <div class="cf-grid">
                        <div class="cf-field cf-span-2">
                            <label class="cf-label">Type de contrat <span class="cf-req">*</span></label>
                            <div class="cf-radio-group">
                                @foreach(['full-time'=>'Temps plein','part-time'=>'Temps partiel','remote'=>'Télétravail'] as $val=>$lbl)
                                    <label class="cf-radio-card">
                                        <input type="radio" name="type" value="{{ $val }}" {{ old('type', $jobOffer->type) == $val ? 'checked' : '' }}>
                                        {{ $lbl }}
                                    </label>
                                @endforeach
                            </div>
                            <x-input-error :messages="$errors->get('type')" class="mt-2"/>
                        </div>

                        <div class="cf-field">
                            <label class="cf-label" for="education_level">Niveau d'étude requis <span class="cf-req">*</span></label>
                            <select id="education_level" name="education_level" class="cf-select" required>
                                <option value="">Sélectionner un niveau</option>
                                @foreach(['Inférieur au baccalauréat','Bac','Bac+3','Bac+5','Plus que Bac +5'] as $lvl)
                                    <option value="{{ $lvl }}" {{ old('education_level', $jobOffer->education_level) == $lvl ? 'selected' : '' }}>{{ $lvl }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('education_level')" class="mt-2"/>
                        </div>

                        <div class="cf-field">
                            <label class="cf-label" for="experience_years">Années d'expérience <span class="cf-req">*</span></label>
                            <input id="experience_years" name="experience_years" type="number" class="cf-input"
                                   value="{{ old('experience_years', $jobOffer->experience_years) }}" required min="0" placeholder="0">
                            <x-input-error :messages="$errors->get('experience_years')" class="mt-2"/>
                        </div>

                        <div class="cf-field">
                            <label class="cf-label" for="salary">Salaire mensuel</label>
                            <div class="cf-input-wrap">
                                <input id="salary" name="salary" type="number" class="cf-input"
                                       value="{{ old('salary', $jobOffer->salary) }}" placeholder="Ex : 1500" min="0">
                                <span class="cf-input-suffix">TND</span>
                            </div>
                            <p class="cf-hint">Laissez vide pour "Non spécifié"</p>
                            <x-input-error :messages="$errors->get('salary')" class="mt-2"/>
                        </div>

                        <div class="cf-field">
                            <label class="cf-label" for="vacancies">Postes vacants <span class="cf-req">*</span></label>
                            <input id="vacancies" name="vacancies" type="number" class="cf-input"
                                   value="{{ old('vacancies', $jobOffer->vacancies) }}" required min="1" placeholder="1">
                            <x-input-error :messages="$errors->get('vacancies')" class="mt-2"/>
                        </div>

                        <div class="cf-field">
                            <label class="cf-label" for="languages">Langues demandées</label>
                            <input id="languages" name="languages" type="text" class="cf-input"
                                   value="{{ old('languages', $jobOffer->languages) }}" placeholder="Français, Anglais...">
                            <x-input-error :messages="$errors->get('languages')" class="mt-2"/>
                        </div>

                        <div class="cf-field">
                            <label class="cf-label" for="expiration_date">Date d'expiration <span class="cf-req">*</span></label>
                            <input id="expiration_date" name="expiration_date" type="date" class="cf-input"
                                   value="{{ old('expiration_date', $jobOffer->expiration_date ? \Carbon\Carbon::parse($jobOffer->expiration_date)->format('Y-m-d') : '') }}" required>
                            <x-input-error :messages="$errors->get('expiration_date')" class="mt-2"/>
                        </div>

                        <div class="cf-field">
                            <label class="cf-label" for="status">Statut de l'offre <span class="cf-req">*</span></label>
                            <select id="status" name="status" class="cf-select" required>
                                @foreach(['open' => 'Ouverte', 'closed' => 'Fermée', 'archived' => 'Archivée'] as $val => $lbl)
                                    <option value="{{ $val }}" {{ old('status', $jobOffer->status) == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2"/>
                        </div>
                    </div>
                </div>

                {{-- Section 3 : Description --}}
                <div class="cf-section">
                    <p class="cf-section-title">
                        <span>3</span>
                        Description &amp; Exigences
                    </p>
                    <div style="display:flex; flex-direction:column; gap:1.25rem;">
                        <div class="cf-field">
                            <label class="cf-label" for="description">Description du poste <span class="cf-req">*</span></label>
                            <textarea id="description" name="description" class="cf-textarea" rows="6"
                                      required placeholder="Décrivez le rôle, les missions, l'environnement de travail...">{{ old('description', $jobOffer->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2"/>
                        </div>

                        <div class="cf-field">
                            <label class="cf-label" for="requirements">Exigences / Compétences <span class="cf-req">*</span></label>
                            <textarea id="requirements" name="requirements" class="cf-textarea" rows="4"
                                      required placeholder="Listez les compétences, diplômes et expériences requises...">{{ old('requirements', $jobOffer->requirements) }}</textarea>
                            <x-input-error :messages="$errors->get('requirements')" class="mt-2"/>
                        </div>

                        <div class="cf-field">
                            <label class="cf-label" for="keywords">Mots-clés</label>
                            <input id="keywords" name="keywords" type="text" class="cf-input"
                                   value="{{ old('keywords', $jobOffer->keywords) }}" placeholder="PHP, Laravel, React, MySQL...">
                            <p class="cf-hint">Séparés par des virgules — aide les candidats à trouver votre offre</p>
                            <x-input-error :messages="$errors->get('keywords')" class="mt-2"/>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="cf-footer">
                    <button type="submit" class="cf-btn-submit">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                        Mettre à jour l'offre
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
