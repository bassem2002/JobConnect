<x-guest-layout>
<style>
:root { --p:#4F46E5; --p-dark:#3730A3; --p-light:#EEF2FF; --border:#E5E7EB; --txt:#111827; --muted:#6B7280; --t:.15s; }
.ve-submit { display:flex; align-items:center; justify-content:center; gap:.5rem; width:100%; padding:.7rem; border-radius:10px; background:linear-gradient(135deg,var(--p),#818CF8); color:#fff; border:none; font-size:.9rem; font-weight:700; font-family:inherit; cursor:pointer; box-shadow:0 4px 12px rgba(79,70,229,.28); transition:opacity var(--t); }
.ve-submit:hover { opacity:.9; }
.ve-logout { display:flex; align-items:center; justify-content:center; gap:.4rem; width:100%; padding:.6rem; border-radius:10px; border:1.5px solid var(--border); background:#fff; color:var(--muted); font-size:.875rem; font-weight:600; font-family:inherit; cursor:pointer; transition:all var(--t); }
.ve-logout:hover { border-color:#9CA3AF; color:var(--txt); }
.ve-ok { display:flex; align-items:flex-start; gap:.5rem; padding:.65rem .875rem; border-radius:8px; margin-bottom:1.25rem; background:#ECFDF5; border:1px solid #A7F3D0; color:#065F46; font-size:.8rem; font-weight:600; line-height:1.4; }
.ve-step { display:flex; align-items:flex-start; gap:.75rem; padding:.6rem 0; }
.ve-step-num { width:22px; height:22px; border-radius:50%; background:var(--p-light); color:var(--p); font-size:.7rem; font-weight:800; display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:1px; }
.ve-step-text { font-size:.8rem; color:var(--muted); line-height:1.5; }
.ve-step-text strong { color:var(--txt); }
</style>

<div style="text-align:center; margin-bottom:1.5rem;">
    <div style="width:64px; height:64px; border-radius:16px; background:var(--p-light); display:flex; align-items:center; justify-content:center; margin:0 auto 1rem;">
        <svg width="28" height="28" fill="none" stroke="#4F46E5" stroke-width="1.8" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
        </svg>
    </div>
    <h2 style="font-size:1.35rem; font-weight:800; color:var(--txt); letter-spacing:-.025em; margin:0 0 .35rem;">
        Vérifiez votre e-mail
    </h2>
    <p style="font-size:.8rem; color:var(--muted); margin:0; line-height:1.55; max-width:28ch; margin-inline:auto;">
        Un lien de vérification vous a été envoyé. Cliquez dessus pour activer votre compte.
    </p>
</div>

@if(session('status') == 'verification-link-sent')
    <div class="ve-ok">
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        Un nouveau lien a été envoyé à votre adresse e-mail.
    </div>
@endif

{{-- Steps guide --}}
<div style="background:#F8F9FC; border:1px solid var(--border); border-radius:10px; padding:.875rem 1rem; margin-bottom:1.5rem;">
    <p style="font-size:.7rem; font-weight:800; text-transform:uppercase; letter-spacing:.08em; color:var(--muted); margin:0 0 .6rem;">Comment ça marche</p>
    <div class="ve-step">
        <span class="ve-step-num">1</span>
        <p class="ve-step-text">Ouvrez votre boîte mail et cherchez un e-mail de <strong>JobConnect</strong>.</p>
    </div>
    <div class="ve-step">
        <span class="ve-step-num">2</span>
        <p class="ve-step-text">Cliquez sur le bouton <strong>Vérifier mon adresse e-mail</strong> dans le message.</p>
    </div>
    <div class="ve-step">
        <span class="ve-step-num">3</span>
        <p class="ve-step-text">Vous serez redirigé automatiquement vers votre tableau de bord.</p>
    </div>
</div>

<div style="display:flex; flex-direction:column; gap:.6rem;">
    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="ve-submit">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            Renvoyer l'e-mail de vérification
        </button>
    </form>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="ve-logout">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
            Se déconnecter
        </button>
    </form>
</div>
</x-guest-layout>
