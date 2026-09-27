<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\JobOffer;
use App\Models\Application;
use App\Models\Report;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $stats = [
            'users_count' => User::count(),
            'candidates_count' => User::where('role', User::ROLE_CANDIDATE)->count(),
            'companies_count' => User::where('role', User::ROLE_COMPANY)->count(),
            'offers_count' => JobOffer::count(),
            'applications_count' => Application::count(),
            'reports_count' => Report::count(),
            'pending_offers_count' => JobOffer::where('status', 'pending_validation')->count(),
            'open_offers_count' => JobOffer::where('status', 'open')->count(),
            'conversion_rate' => JobOffer::count() > 0 ? round((Application::count() / JobOffer::count()) * 100, 1) : 0,
        ];

        // Stats by city
        $statsByCity = JobOffer::select('location', \DB::raw('count(*) as total'))
            ->groupBy('location')
            ->get();

        // Stats by category
        $statsByCategory = Category::withCount('jobOffers')->get();

        // Applications by status
        $applicationsByStatus = Application::select('status', \DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        // Recent activity (last 7 days)
        $recentUsers = User::where('created_at', '>=', now()->subDays(7))->count();
        $recentOffers = JobOffer::where('created_at', '>=', now()->subDays(7))->count();
        $recentApplications = Application::where('created_at', '>=', now()->subDays(7))->count();

        // ── Chart data ────────────────────────────────────────────────────

        // Line chart: monthly growth over last 6 months
        $monthsFr = ['Jan','Fév','Mar','Avr','Mai','Jun','Jul','Aoû','Sep','Oct','Nov','Déc'];
        $chartMonths = [];
        $chartUsers  = [];
        $chartOffers = [];
        $chartApps   = [];
        for ($i = 5; $i >= 0; $i--) {
            $m = now()->subMonths($i);
            $chartMonths[] = $monthsFr[$m->month - 1] . ' ' . $m->format('y');
            $chartUsers[]  = User::whereYear('created_at', $m->year)->whereMonth('created_at', $m->month)->count();
            $chartOffers[] = JobOffer::whereYear('created_at', $m->year)->whereMonth('created_at', $m->month)->count();
            $chartApps[]   = Application::whereYear('created_at', $m->year)->whereMonth('created_at', $m->month)->count();
        }

        // Donut: user role distribution
        $adminCount     = User::where('role', User::ROLE_ADMIN)->count();
        $chartRoleData  = [$stats['candidates_count'], $stats['companies_count'], $adminCount];
        $chartRoleLabels = ['Candidats', 'Entreprises', 'Admins'];

        // Donut: applications by status (pre-formatted)
        $statusMap = ['pending' => 'En attente', 'accepted' => 'Acceptée', 'rejected' => 'Refusée', 'withdrawn' => 'Retirée'];
        $chartAppLabels = [];
        $chartAppData   = [];
        foreach ($applicationsByStatus->sortByDesc('total') as $a) {
            $chartAppLabels[] = $statusMap[$a->status] ?? ucfirst($a->status);
            $chartAppData[]   = $a->total;
        }

        // Bar: all categories sorted desc
        $allCatsSorted  = $statsByCategory->sortByDesc('job_offers_count');
        $chartCatLabels = $allCatsSorted->pluck('name')->values()->toArray();
        $chartCatData   = $allCatsSorted->pluck('job_offers_count')->values()->toArray();

        // Bar: all cities sorted desc
        $allCitiesSorted = $statsByCity->sortByDesc('total');
        $chartCityLabels = $allCitiesSorted->pluck('location')->map(fn($l) => $l ?? 'Non spécifié')->values()->toArray();
        $chartCityData   = $allCitiesSorted->pluck('total')->values()->toArray();

        return view('admin.dashboard', compact(
            'stats', 'statsByCity', 'statsByCategory', 'applicationsByStatus',
            'recentUsers', 'recentOffers', 'recentApplications',
            'chartMonths', 'chartUsers', 'chartOffers', 'chartApps',
            'chartRoleData', 'chartRoleLabels',
            'chartAppLabels', 'chartAppData',
            'chartCatLabels', 'chartCatData',
            'chartCityLabels', 'chartCityData'
        ));
    }

    public function moderateOffers(\Illuminate\Http\Request $request)
    {
        auth()->user()->update(['offers_seen_at' => now()]);

        $sort   = in_array($request->get('sort'), ['title', 'company', 'date']) ? $request->get('sort') : 'date';
        $dir    = $request->get('dir') === 'asc' ? 'asc' : 'desc';
        $status = in_array($request->get('status'), ['pending_validation', 'open', 'refused', 'closed']) ? $request->get('status') : '';

        $query = JobOffer::with(['company', 'offerCategory']);

        if ($status) {
            $query->where('status', $status);
        }

        if ($sort === 'company') {
            $query->join('users as co', 'job_offers.user_id', '=', 'co.id')
                  ->orderBy('co.name', $dir)
                  ->select('job_offers.*');
        } elseif ($sort === 'title') {
            $query->orderBy('title', $dir);
        } else {
            $query->orderBy('job_offers.created_at', $dir);
        }

        $offers        = $query->paginate(15)->withQueryString();
        $pendingCount  = JobOffer::where('status', 'pending_validation')->count();

        return view('admin.offers.moderation', compact('offers', 'sort', 'dir', 'status', 'pendingCount'));
    }

    public function updateOfferStatus(Request $request, JobOffer $jobOffer)
    {
        $request->validate([
            'status' => 'required|in:open,refused',
        ]);

        $jobOffer->update(['status' => $request->status]);

        if ($request->status === 'open') {
            // Notify all candidates when an offer is validated
            $candidates = User::where('role', User::ROLE_CANDIDATE)->get();
            \Illuminate\Support\Facades\Notification::send($candidates, new \App\Notifications\NewJobOffer($jobOffer));
        }

        return back()->with('success', 'Le statut de l\'offre a été mis à jour.');
    }
    // Dans AdminController.php

public function showOffer(JobOffer $jobOffer)
{
    // On charge l'entreprise et la catégorie pour avoir toutes les infos
    $jobOffer->load(['company', 'offerCategory']);
    
    return view('admin.offers.show', compact('jobOffer'));
}
    public function moderateProfiles(Request $request)
    {
        auth()->user()->update(['users_seen_at' => now()]);

        $sort      = $request->input('sort', 'date');
        $dir       = $request->input('dir', 'desc') === 'asc' ? 'asc' : 'desc';
        $role      = $request->input('role');
        $validated = $request->input('validated'); // '1', '0', or null

        $query = User::withCount(['jobOffers', 'applications']);

        if ($role === 'company') {
            $query->where('role', User::ROLE_COMPANY);
        } elseif ($role === 'candidate') {
            $query->where('role', User::ROLE_CANDIDATE);
        }

        if ($validated === '1') {
            $query->where('is_validated', true);
        } elseif ($validated === '0') {
            $query->where('is_validated', false);
        }

        if ($sort === 'name') {
            $query->orderBy('name', $dir);
        } else {
            $query->orderBy('created_at', $dir);
        }

        $users = $query->paginate(15)->withQueryString();
        return view('admin.users.moderation', compact('users', 'sort', 'dir', 'role', 'validated'));
    }

    public function showUser(User $user)
    {
        $user->loadCount(['jobOffers', 'applications']);
        $recentOffers = $user->isCompany()
            ? $user->jobOffers()->with('offerCategory')->withCount('applications')->latest()->limit(5)->get()
            : collect();
        $recentApplications = $user->isCandidate()
            ? $user->applications()->with('jobOffer.company')->latest()->limit(5)->get()
            : collect();
        return view('admin.users.show', compact('user', 'recentOffers', 'recentApplications'));
    }

    public function updateUserStatus(Request $request, User $user)
    {
        if ($request->has('is_validated')) {
            $user->update(['is_validated' => $request->is_validated]);
        }

        if ($request->has('is_blocked')) {
            $user->update(['is_blocked' => $request->is_blocked]);
        }

        return back()->with('success', 'Le statut de l\'utilisateur a été mis à jour.');
    }

    public function viewReports(Request $request)
    {
        $sort = $request->input('sort', 'date');
        $dir  = $request->input('dir', 'desc') === 'asc' ? 'asc' : 'desc';

        $query = Report::with(['user', 'reportedUser']);

        if ($sort === 'reporter') {
            $query->join('users as reporter', 'reports.user_id', '=', 'reporter.id')
                  ->orderBy('reporter.name', $dir)
                  ->select('reports.*');
        } elseif ($sort === 'reported') {
            $query->join('users as reported', 'reports.reported_user_id', '=', 'reported.id')
                  ->orderBy('reported.name', $dir)
                  ->select('reports.*');
        } else {
            $query->orderBy('reports.created_at', $dir);
        }

        $reports      = $query->paginate(15)->withQueryString();
        $totalReports = Report::count();
        $blockedCount = Report::whereHas('reportedUser', fn($u) => $u->where('is_blocked', true))->count();

        auth()->user()->update(['reports_seen_at' => now()]);

        return view('admin.reports.index', compact('reports', 'totalReports', 'blockedCount', 'sort', 'dir'));
    }

    public function showReport(Report $report)
    {
        $report->load(['user', 'reportedUser']);
        return view('admin.reports.show', compact('report'));
    }

    public function exportStats()
    {
        $stats = [
            ['Titre', 'Valeur'],
            ['Total Utilisateurs', User::count()],
            ['Total Candidats', User::where('role', User::ROLE_CANDIDATE)->count()],
            ['Total Entreprises', User::where('role', User::ROLE_COMPANY)->count()],
            ['Total Offres', JobOffer::count()],
            ['Total Candidatures', Application::count()],
        ];

        $callback = function() use ($stats) {
            $file = fopen('php://output', 'w');
            foreach ($stats as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return Response::stream($callback, 200, [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=statistiques_plateforme.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ]);
    }
}
