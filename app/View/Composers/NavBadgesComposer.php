<?php

namespace App\View\Composers;

use App\Models\Application;
use App\Models\JobOffer;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NavBadgesComposer
{
    public function compose(View $view): void
    {
        $badges = [
            'navBadgeReports'  => 0,
            'navBadgeUsers'    => 0,
            'navBadgeOffers'   => 0,
            'navBadgeMyOffers' => 0,
            'navBadgeMyApps'   => 0,
        ];

        if (!Auth::check()) {
            $view->with($badges);
            return;
        }

        $user  = Auth::user();
        $epoch = now()->subYears(50); // fallback "never seen"

        if ($user->isAdmin()) {
            $seenReports = $user->reports_seen_at ?? $epoch;
            $seenUsers   = $user->users_seen_at   ?? $epoch;
            $seenOffers  = $user->offers_seen_at  ?? $epoch;

            $badges['navBadgeReports'] = Report::where('created_at', '>', $seenReports)->count();

            $badges['navBadgeUsers'] = User::where('role', '!=', User::ROLE_ADMIN)
                                           ->where('created_at', '>', $seenUsers)
                                           ->count();

            $badges['navBadgeOffers'] = JobOffer::where('status', 'pending_validation')
                                                ->where('created_at', '>', $seenOffers)
                                                ->count();
        }

        if ($user->isCompany()) {
            $seen = $user->my_offers_seen_at ?? $epoch;

            $badges['navBadgeMyOffers'] = Application::whereHas(
                'jobOffer',
                fn($q) => $q->where('user_id', $user->id)
            )->where('created_at', '>', $seen)->count();
        }

        if ($user->isCandidate()) {
            $seen = $user->my_apps_seen_at ?? $epoch;

            $badges['navBadgeMyApps'] = Application::where('user_id', $user->id)
                                                    ->whereIn('status', ['accepted', 'rejected'])
                                                    ->where('updated_at', '>', $seen)
                                                    ->count();
        }

        $view->with($badges);
    }
}
