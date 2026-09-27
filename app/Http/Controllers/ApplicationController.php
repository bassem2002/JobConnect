<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\JobOffer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Notifications\ApplicationStatusUpdated;
use App\Notifications\NewApplication;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->isCandidate()) {
            $user->update(['my_apps_seen_at' => now()]);

            $applications = $user
                ->applications()
                ->with('jobOffer.company')
                ->latest()
                ->paginate(10);

            return view('applications.index', compact('applications'));
        }

        if ($user->isCompany()) {
            // Base query : candidatures reçues par cette entreprise
            $baseQuery = Application::whereHas('jobOffer', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });

            // Filtre optionnel par offre
            $jobOffer = null;
            if ($request->filled('job_offer_id')) {
                $baseQuery->where('job_offer_id', $request->job_offer_id);
                $jobOffer = JobOffer::find($request->job_offer_id);
            }

            // ── CORRECTION : cloner la query pour les stats AVANT paginate ──
            // paginate() modifie l'état interne de la query builder ;
            // il faut récupérer les stats sur une copie indépendante.
            $statsQuery = clone $baseQuery;
            $allForStats = $statsQuery->get();

            $stats = [
                'pending'  => $allForStats->where('status', 'pending')->count(),
                'accepted' => $allForStats->where('status', 'accepted')->count(),
                'rejected' => $allForStats->where('status', 'rejected')->count(),
            ];

            $applications = $baseQuery->with(['user', 'jobOffer'])->latest()->paginate(20);

            return view('applications.company_index', compact('applications', 'stats', 'jobOffer'));
        }

        abort(403);
    }

    public function store(Request $request, JobOffer $jobOffer)
    {
        /** @var User $user */
        $user = Auth::user();

        // Vérifier si déjà postulé
        $existing = Application::where('user_id', $user->id)
            ->where('job_offer_id', $jobOffer->id)
            ->first();

        if ($existing) {
            return back()->with('error', 'Vous avez déjà postulé à cette offre.');
        }

        $request->validate([
            'cv_type'      => 'required|in:existing,new',
            'new_cv'       => 'required_if:cv_type,new|file|mimes:pdf,doc,docx|max:5120',
            'cover_letter' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ], [
            'cv_type.required'   => 'Veuillez choisir un CV.',
            'new_cv.required_if' => 'Veuillez télécharger votre nouveau CV.',
            'new_cv.mimes'       => 'Le CV doit être au format PDF, DOC ou DOCX.',
            'cover_letter.mimes' => 'La lettre de motivation doit être au format PDF, DOC ou DOCX.',
        ]);

        $cvPath = null;

        if ($request->cv_type === 'existing') {
            $cvPath = $user->cv_path;
            if (!$cvPath) {
                return back()->with('error', 'Vous n\'avez pas de CV enregistré sur votre profil.');
            }
            // ── CORRECTION : vérifier que le fichier existe vraiment ──
            if (!Storage::disk('public')->exists($cvPath)) {
                return back()->with('error', 'Votre CV enregistré est introuvable. Veuillez en uploader un nouveau.');
            }
        } else {
            // Nouveau CV uploadé pour cette candidature
            $cvPath = $request->file('new_cv')->store('cvs', 'public');
        }

        $coverLetterPath = null;
        if ($request->hasFile('cover_letter')) {
            $coverLetterPath = $request->file('cover_letter')->store('cover_letters', 'public');
        }

        $application = Application::create([
            'user_id'           => $user->id,
            'job_offer_id'      => $jobOffer->id,
            'cv_path'           => $cvPath,
            'cover_letter_path' => $coverLetterPath,
            'status'            => 'pending',
        ]);

        // Notifier l'entreprise
        $jobOffer->company->notify(new NewApplication($application));

        return back()->with('success', 'Votre candidature a été envoyée avec succès.');
    }

    public function update(Request $request, Application $application)
    {
        /** @var User $user */
        $user = Auth::user();

        // Vérifier que la candidature appartient à une offre de cette entreprise
        if ($application->jobOffer->user_id !== $user->id) {
            abort(403);
        }

        $request->validate([
            'status'          => 'required|in:pending,accepted,rejected',
            'company_comment' => 'nullable|string|max:1000',
        ]);

        $previous = $application->status;
        $jobOffer = $application->jobOffer;

        // ── Gestion des postes vacants ─────────────────────────
        if ($request->status === 'accepted' && $previous !== 'accepted') {
            if ($jobOffer->vacancies <= 0) {
                return response()->json([
                    'ok'      => false,
                    'message' => 'Plus de postes vacants disponibles pour cette offre.',
                ], 422);
            }
            $jobOffer->decrement('vacancies');
        } elseif ($previous === 'accepted' && $request->status !== 'accepted') {
            $jobOffer->increment('vacancies');
        }

        $application->update([
            'status'          => $request->status,
            'company_comment' => $request->company_comment,
        ]);

        $application->refresh();

        // Notifier le candidat si le statut a changé
        if ($previous !== $application->status) {
            $application->user->notify(new ApplicationStatusUpdated($application));
        }

        // ── Stats mises à jour (sur toutes les candidatures de l'entreprise) ──
        $allApps = Application::whereHas('jobOffer', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->get();

        $stats = [
            'pending'  => $allApps->where('status', 'pending')->count(),
            'accepted' => $allApps->where('status', 'accepted')->count(),
            'rejected' => $allApps->where('status', 'rejected')->count(),
        ];

        if ($request->wantsJson()) {
            return response()->json([
                'ok'              => true,
                'status'          => $application->status,
                'company_comment' => $application->company_comment,
                'vacancies'       => $application->jobOffer->vacancies,
                'badge'           => self::statusBadgePayload($application->status),
                'stats'           => $stats,
            ]);
        }

        return back()->with('success', 'Statut de la candidature mis à jour.');
    }

    public function downloadCv(Application $application)
    {
        /** @var User $user */
        $user = Auth::user();

        // Autorisation : le candidat lui-même, l'entreprise concernée, ou un admin
        if (
            $user->id !== $application->user_id &&
            $user->id !== $application->jobOffer->user_id &&
            !$user->isAdmin()
        ) {
            abort(403, 'Accès non autorisé au CV.');
        }

        // ── CORRECTION : normaliser le chemin avant de vérifier ──
        $cvPath = $this->normalizePath($application->cv_path);

        if (!$cvPath || !Storage::disk('public')->exists($cvPath)) {
            return back()->with('error', 'Le fichier CV est introuvable.');
        }

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('public');
        return $disk->download($cvPath);
    }

    public function downloadCoverLetter(Application $application)
    {
        /** @var User $user */
        $user = Auth::user();

        if (
            $user->id !== $application->user_id &&
            $user->id !== $application->jobOffer->user_id &&
            !$user->isAdmin()
        ) {
            abort(403, 'Accès non autorisé à la lettre de motivation.');
        }

        $path = $this->normalizePath($application->cover_letter_path);

        if (!$path || !Storage::disk('public')->exists($path)) {
            return back()->with('error', 'La lettre de motivation est introuvable.');
        }

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('public');
        return $disk->download($path);
    }

    // ── Helpers ───────────────────────────────────────────────

    /**
     * Normalise un chemin stocké en base quelle que soit sa forme :
     * "cvs/f.pdf", "public/cvs/f.pdf", "storage/app/public/cvs/f.pdf"
     * → toujours "cvs/f.pdf"
     */
    private function normalizePath(?string $path): ?string
    {
        if (!$path) return null;
        $path = preg_replace('#^storage/app/public/#', '', $path);
        $path = preg_replace('#^public/#', '', $path);
        return $path;
    }

    private static function statusBadgePayload(string $status): array
    {
        return match ($status) {
            'pending'  => ['pill' => '#fffbeb', 'color' => '#92400e', 'border' => '#fde68a', 'dot' => '#f59e0b', 'label' => 'En attente'],
            'accepted' => ['pill' => '#f0fdf4', 'color' => '#166534', 'border' => '#bbf7d0', 'dot' => '#22c55e', 'label' => 'Accepté'],
            'rejected' => ['pill' => '#fef2f2', 'color' => '#991b1b', 'border' => '#fecaca', 'dot' => '#ef4444', 'label' => 'Refusé'],
            default    => ['pill' => '#f9fafb', 'color' => '#374151', 'border' => '#e5e7eb', 'dot' => '#9ca3af', 'label' => ucfirst($status)],
        };
    }
    public function showCv($id)
    {
        $user = User::findOrFail($id);

        if (!$user->cv_path) {
            abort(404);
        }

        $cvPath = $this->normalizePath($user->cv_path);

        if (!$cvPath || !Storage::disk('public')->exists($cvPath)) {
            abort(404);
        }

        return Storage::disk('public')->response($cvPath);
    }
}
