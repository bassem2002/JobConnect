<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>JobConnect - Votre avenir commence ici</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --primary: #4F46E5;
            --primary-dark: #3730A3;
            --secondary: #10B981;
            --text-main: #111827;
            --text-muted: #6B7280;
            --surface: #F9FAFB;
        }
        body { font-family: 'Instrument Sans', sans-serif; color: var(--text-main); background: #fff; overflow-x: hidden; }
        
        /* Navigation */
        .nav-fixed { position: fixed; top: 0; width: 100%; z-index: 50; background: rgba(255,255,255,0.8); backdrop-filter: blur(12px); border-bottom: 1px solid #f3f4f6; }
        .nav-container { max-width: 80rem; margin: 0 auto; padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: center; }
        .logo { font-size: 1.5rem; font-weight: 800; color: var(--primary); letter-spacing: -0.02em; text-decoration: none; }
        .nav-links { display: flex; gap: 2rem; align-items: center; }
        .nav-link { font-weight: 600; font-size: 0.95rem; color: var(--text-muted); text-decoration: none; transition: 0.2s; }
        .nav-link:hover { color: var(--primary); }
        .btn-nav { background: var(--primary); color: white; padding: 0.6rem 1.5rem; border-radius: 12px; font-weight: 700; text-decoration: none; transition: 0.2s; }
        .btn-nav:hover { background: var(--primary-dark); transform: translateY(-1px); }

        /* Hero */
        .hero { padding: 10rem 1.5rem 6rem; background: radial-gradient(circle at 90% 10%, #eef2ff 0%, transparent 40%), radial-gradient(circle at 10% 90%, #f0fdf4 0%, transparent 40%); position: relative; }
        .hero-container { max-width: 80rem; margin: 0 auto; display: flex; align-items: center; gap: 4rem; }
        @media (max-width: 1024px) { .hero-container { flex-direction: column; text-align: center; } }
        .hero-content { flex: 1; }
        .hero-title { font-size: 4rem; font-weight: 800; line-height: 1.1; letter-spacing: -0.04em; margin-bottom: 1.5rem; }
        .hero-title span { color: var(--primary); }
        .hero-desc { font-size: 1.25rem; color: var(--text-muted); margin-bottom: 2.5rem; line-height: 1.6; }
        .hero-btns { display: flex; gap: 1rem; }
        @media (max-width: 1024px) { .hero-btns { justify-content: center; } }
        
        /* Search Box */
        .hero-search { background: white; padding: 0.75rem; border-radius: 24px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.1); display: flex; gap: 0.5rem; margin-top: 3rem; border: 1px solid #f3f4f6; }
        @media (max-width: 640px) { .hero-search { flex-direction: column; } }
        .search-input { flex: 1; display: flex; align-items: center; gap: 0.75rem; padding: 0 1.25rem; border-right: 1px solid #f3f4f6; }
        @media (max-width: 640px) { .search-input { border-right: none; border-bottom: 1px solid #f3f4f6; padding: 1rem 0; } }
        .search-input input { border: none; outline: none; width: 100%; font-size: 1rem; }
        .btn-search { background: var(--primary); color: white; border: none; padding: 1rem 2.5rem; border-radius: 16px; font-weight: 700; cursor: pointer; transition: 0.2s; }
        .btn-search:hover { background: var(--primary-dark); }

        /* Features */
        .features { padding: 6rem 1.5rem; background: white; }
        .section-tag { display: inline-block; padding: 0.4rem 1rem; background: #eef2ff; color: var(--primary); border-radius: 99px; font-size: 0.85rem; font-weight: 800; text-transform: uppercase; margin-bottom: 1rem; }
        .section-title { font-size: 2.5rem; font-weight: 800; margin-bottom: 4rem; letter-spacing: -0.02em; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2.5rem; max-width: 80rem; margin: 0 auto; }
        .feat-card { padding: 2.5rem; border-radius: 32px; background: var(--surface); transition: 0.3s; border: 1px solid transparent; }
        .feat-card:hover { transform: translateY(-10px); background: white; border-color: #eef2ff; box-shadow: 0 20px 40px rgba(0,0,0,0.05); }
        .feat-icon { width: 64px; height: 64px; border-radius: 20px; background: white; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
        .feat-card h3 { font-size: 1.5rem; font-weight: 700; margin-bottom: 1rem; }
        .feat-card p { color: var(--text-muted); line-height: 1.6; }

        /* CTA Section */
        .cta { padding: 6rem 1.5rem; }
        .cta-box { max-width: 80rem; margin: 0 auto; background: var(--primary); border-radius: 48px; padding: 5rem; color: white; text-align: center; position: relative; overflow: hidden; }
        .cta-box::before { content: ''; position: absolute; inset: 0; background: linear-gradient(45deg, rgba(255,255,255,0.1) 0%, transparent 100%); }
        .cta-title { font-size: 3rem; font-weight: 800; margin-bottom: 1.5rem; }
        .cta-desc { font-size: 1.25rem; opacity: 0.9; margin-bottom: 3rem; max-width: 600px; margin-inline: auto; }
        .btn-white { background: white; color: var(--primary); padding: 1rem 3rem; border-radius: 16px; font-weight: 800; text-decoration: none; display: inline-block; transition: 0.2s; }
        .btn-white:hover { transform: scale(1.05); box-shadow: 0 20px 40px rgba(0,0,0,0.2); }

        footer { padding: 4rem 1.5rem; border-top: 1px solid #f3f4f6; text-align: center; color: var(--text-muted); font-weight: 500; }
    </style>
</head>
<body>
    <nav class="nav-fixed">
        <div class="nav-container">
            <a href="/" class="logo">Job<span>Connect</span></a>
            <div class="nav-links">
                <a href="{{ route('job-offers.index') }}" class="nav-link">Parcourir les offres</a>
                @guest
                    <a href="{{ route('login') }}" class="nav-link">Connexion</a>
                    <a href="{{ route('register') }}" class="btn-nav">S'inscrire</a>
                @else
                    <a href="{{ url('/dashboard') }}" class="btn-nav">Mon Tableau de bord</a>
                @endguest
            </div>
        </div>
    </nav>

    <section class="hero">
        <div class="hero-container">
            <div class="hero-content">
                <span class="section-tag">Plateforme N°1 en Tunisie</span>
                <h1 class="hero-title">Trouvez le job qui <span>vous ressemble</span>.</h1>
                <p class="hero-desc">La plateforme de recrutement moderne pour connecter les talents tunisiens aux meilleures entreprises.</p>
                
                <form action="{{ route('job-offers.index') }}" method="GET" class="hero-search">
                    <div class="search-input" style="border-right:none">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" name="search" placeholder="Métier, entreprise, ville...">
                    </div>
                    <button type="submit" class="btn-search">Rechercher</button>
                </form>
            </div>
            <div style="flex: 1; display: flex; justify-content: flex-end;">
                <img src="https://illustrations.popsy.co/amber/working-from-home.svg" alt="Illustration" style="width: 100%; max-width: 500px;">
            </div>
        </div>
    </section>

    <section class="features">
        <div style="text-align: center; max-width: 80rem; margin: 0 auto;">
            <span class="section-tag">Pourquoi nous ?</span>
            <h2 class="section-title">Une expérience conçue pour vous</h2>
            
            <div class="grid">
                <div class="feat-card">
                    <div class="feat-icon">🎯</div>
                    <h3>Candidatures Simplifiées</h3>
                    <p>Postulez en un clic et suivez l'état de vos candidatures en temps réel depuis votre tableau de bord personnel.</p>
                </div>
                <div class="feat-card">
                    <div class="feat-icon">🏢</div>
                    <h3>Espace Entreprise</h3>
                    <p>Gérez vos offres d'emploi, examinez les profils des candidats et recrutez les meilleurs talents pour votre équipe.</p>
                </div>
                <div class="feat-card">
                    <div class="feat-icon">🛡️</div>
                    <h3>Modération Active</h3>
                    <p>Toutes nos offres sont vérifiées par nos administrateurs pour vous garantir un environnement sûr et fiable.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="cta">
        <div class="cta-box">
            <h2 class="cta-title">Prêt à franchir une nouvelle étape ?</h2>
            <p class="cta-desc">Rejoignez des milliers de professionnels et d'entreprises qui nous font déjà confiance pour leur recrutement.</p>
            <div style="display: flex; gap: 1.5rem; justify-content: center;">
                <a href="{{ route('register') }}" class="btn-white">Créer mon compte</a>
                <a href="{{ route('job-offers.index') }}" style="color: white; font-weight: 700; text-decoration: none; padding: 1rem 2rem; border: 2px solid white; border-radius: 16px;">Voir les offres</a>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; {{ date('Y') }} JobConnect. Fait avec ❤️ pour le marché tunisien.</p>
    </footer>
</body>
</html>
