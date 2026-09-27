<?php

namespace App\Http\Controllers;

use App\Models\JobOffer;
use App\Models\Category;
use App\Models\User;
use App\Notifications\NewJobOffer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class JobOfferController extends Controller
{
    public function create()
    {
        $categories = Category::all();
        return view('job-offers.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'required|string',
            'requirements'     => 'required|string',
            'expiration_date'  => 'required|date|after:today',
            'location'         => 'required|string|max:255',
            'type'             => 'required|in:full-time,part-time,remote',
            'category_id'      => 'required|exists:categories,id',
            'education_level'  => 'required|string',
            'experience_years' => 'required|integer|min:0',
            'salary'           => 'nullable|numeric|min:0',
            'languages'        => 'nullable|string|max:255',
            'keywords'         => 'nullable|string|max:255',
            'vacancies'        => 'required|integer|min:1',
        ]);

        JobOffer::create([
            'user_id'          => Auth::id(),
            'category_id'      => $request->category_id,
            'title'            => $request->title,
            'description'      => $request->description,
            'requirements'     => $request->requirements,
            'expiration_date'  => $request->expiration_date,
            'location'         => $request->location,
            'type'             => $request->type,
            'education_level'  => $request->education_level,
            'experience_years' => $request->experience_years,
            'salary'           => $request->salary,
            'languages'        => $request->languages,
            'keywords'         => $request->keywords,
            'vacancies'        => $request->vacancies,
            'status'           => 'pending_validation',
        ]);

        return redirect()->route('job-offers.my-offers')
            ->with('success', 'Votre offre est en attente de modération par un administrateur.');
    }

    public function edit(JobOffer $jobOffer)
    {
        if ($jobOffer->user_id !== Auth::id()) {
            abort(403);
        }
        $categories = Category::all();
        return view('job-offers.edit', compact('jobOffer', 'categories'));
    }

    /**
     * FIX : La validation utilisait 'category' au lieu de 'category_id'
     *       et $request->all() exposait des champs non validés.
     *       On utilise uniquement les champs validés.
     */
    public function update(Request $request, JobOffer $jobOffer)
    {
        if ($jobOffer->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'required|string',
            'requirements'     => 'required|string',
            'expiration_date'  => 'required|date|after_or_equal:today',
            'location'         => 'required|string|max:255',
            'type'             => 'required|in:full-time,part-time,remote',
            'category_id'      => 'required|exists:categories,id',  // FIX : category → category_id
            'education_level'  => 'required|string',
            'experience_years' => 'required|integer|min:0',
            'salary'           => 'nullable|numeric|min:0',
            'languages'        => 'nullable|string|max:255',
            'keywords'         => 'nullable|string|max:255',
            'vacancies'        => 'required|integer|min:1',
            'status'           => 'required|in:open,closed,archived',
        ]);

        $jobOffer->update($validated); // FIX : $request->all() → $validated

        return redirect()->route('job-offers.my-offers')
            ->with('success', 'Offre mise à jour avec succès.');
    }

    public function archive(JobOffer $jobOffer)
    {
        if ($jobOffer->user_id !== Auth::id()) {
            abort(403);
        }
        $jobOffer->update(['status' => 'archived']);
        return back()->with('success', 'Offre archivée avec succès.');
    }

    public function unarchive(JobOffer $jobOffer)
    {
        if ($jobOffer->user_id !== Auth::id()) {
            abort(403);
        }
        $jobOffer->update(['status' => 'open']);
        return back()->with('success', 'Offre restaurée avec succès.');
    }

    public function myOffers(Request $request)
    {
        Auth::user()->update(['my_offers_seen_at' => now()]);

        $baseQuery = JobOffer::where('user_id', Auth::id());

        // Stats across ALL offers (unfiltered)
        $allOffers = (clone $baseQuery)->withCount('applications')->get();
        $summaryStats = [
            'active_count'       => $allOffers->where('status', 'open')->count(),
            'applications_total' => $allOffers->sum('applications_count'),
        ];

        $sort   = $request->input('sort', 'date');
        $dir    = $request->input('dir', 'desc') === 'asc' ? 'asc' : 'desc';
        $status = $request->input('status');

        $validStatuses = ['open', 'pending_validation', 'refused', 'archived', 'closed'];
        if ($status && in_array($status, $validStatuses)) {
            $baseQuery->where('status', $status);
        }

        $baseQuery->with(['company', 'offerCategory'])->withCount('applications');

        if ($sort === 'title') {
            $baseQuery->orderBy('title', $dir);
        } elseif ($sort === 'applications') {
            $baseQuery->orderBy('applications_count', $dir);
        } else {
            $baseQuery->orderBy('created_at', $dir);
        }

        $jobOffers = $baseQuery->paginate(10)->withQueryString();

        return view('job-offers.my-offers', compact('jobOffers', 'summaryStats', 'sort', 'dir', 'status'));
    }

    public function index(Request $request)
    {
        $query = JobOffer::with(['company', 'offerCategory']); // category chargé pour éviter N+1

        // FIX : Auth::user() peut être null si le visiteur n'est pas connecté
        // index() et show() sont accessibles sans authentification
        $user = Auth::user();

        if ($user && $user->isAdmin()) {
            // Admin voit tout (toutes offres, tous statuts)
        } elseif ($user && $user->isCompany()) {
            // Entreprise : offres ouvertes + ses propres offres
            $query->where(function ($q) {
                $q->where('status', 'open')
                  ->orWhere('user_id', Auth::id());
            });
        } else {
            // Visiteur non connecté ou candidat : uniquement les offres ouvertes
            $query->where('status', 'open');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title',    'like', "%{$search}%")
                  ->orWhere('keywords',  'like', "%{$search}%")
                  ->orWhere('location',  'like', "%{$search}%")
                  ->orWhereHas('company',      fn($r) => $r->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('offerCategory', fn($r) => $r->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('location')) {
            $query->where('location', 'like', "%{$request->location}%");
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('education_level')) {
            $query->where('education_level', $request->education_level);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $jobOffers  = $query->latest()->paginate(10);
        $categories = Category::all();

        return view('job-offers.index', [
            'jobOffers'  => $jobOffers,
            'categories' => $categories,
            'filters'    => $request->only(['search', 'location', 'category_id', 'education_level', 'type']),
        ]);
    }

    public function show(JobOffer $jobOffer)
    {
        // FIX : Visiteur non connecté ne peut pas voir une offre non-ouverte
        if ($jobOffer->status !== 'open' && !Auth::check()) {
            abort(404);
        }

        $jobOffer->load(['company', 'offerCategory']);
        return view('job-offers.show', compact('jobOffer'));
    }
}
