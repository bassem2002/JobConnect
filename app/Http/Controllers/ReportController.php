<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use App\Models\Application;
use App\Notifications\ReportUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class ReportController extends Controller
{
    public function create(User $user)
    {
        /** @var User $currentUser */
        $currentUser = Auth::user();

        if ($currentUser->isCandidate()) {
            if (!$user->isCompany()) {
                return back()->with('error', 'Vous ne pouvez signaler que des entreprises.');
            }

            $hasApplied = Application::where('user_id', $currentUser->id)
                ->whereHas('jobOffer', fn($q) => $q->where('user_id', $user->id))
                ->exists();

            if (!$hasApplied) {
                return back()->with('error', 'Vous devez avoir postulé à une offre de cette entreprise pour pouvoir la signaler.');
            }
        }

        if ($currentUser->isCompany() && !$user->isCandidate()) {
            return back()->with('error', 'Vous ne pouvez signaler que des candidats.');
        }

        $existingReport = Report::where('user_id', $currentUser->id)
            ->where('reported_user_id', $user->id)
            ->first();

        return view('reports.create', compact('user', 'existingReport'));
    }

    public function store(Request $request, User $user)
    {
        /** @var User $currentUser */
        $currentUser = Auth::user();

        if ($currentUser->isCandidate()) {
            $hasApplied = Application::where('user_id', $currentUser->id)
                ->whereHas('jobOffer', fn($q) => $q->where('user_id', $user->id))
                ->exists();

            if (!$hasApplied) {
                return back()->with('error', 'Action non autorisée.');
            }
        }

        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $report = Report::updateOrCreate(
            ['user_id' => $currentUser->id, 'reported_user_id' => $user->id],
            ['reason' => $request->reason]
        );

        if (!$report->wasRecentlyCreated) {
            $admins = User::where('role', User::ROLE_ADMIN)->get();
            Notification::send($admins, new ReportUpdated($report));
        }

        $targetLabel = $user->isCompany() ? 'L\'entreprise' : 'Le candidat';
        $action = $report->wasRecentlyCreated ? 'signalé(e)' : 'mis à jour';
        return redirect()->route('dashboard')->with('success', $targetLabel . ' a été ' . $action . '.');
    }
}
