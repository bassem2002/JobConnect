<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TrackNavigation
{
    const ROUTE_LEVELS = [
        // Level 0 — root/list pages; URL stored as nav_level0, nav_level1 cleared
        'job-offers.index'        => [0, 'Offres d\'emploi'],
        'job-offers.my-offers'    => [0, 'Mes offres'],
        'candidates.index'        => [0, 'Candidats'],
        'applications.index'      => [0, 'Mes candidatures'],
        'saved-jobs.index'        => [0, 'Offres sauvegardées'],
        'notifications.index'     => [0, 'Notifications'],
        'profile.edit'            => [0, 'Mon profil'],
        'admin.users.moderation'  => [0, 'Utilisateurs'],
        'admin.offers.moderation' => [0, 'Offres'],
        'admin.reports.index'     => [0, 'Signalements'],
        'admin.categories.index'  => [0, 'Catégories'],
        // Level 1 — detail pages; URL stored as nav_level1, nav_level0 kept
        'job-offers.show'            => [1, null],
        'candidates.show'            => [1, null],
        'applications.company_index' => [1, 'Candidatures reçues'],
        'admin.users.show'           => [1, null],
        'admin.offers.show'          => [1, null],
        'admin.reports.show'         => [1, null],
    ];

    public function handle(Request $request, Closure $next)
    {
        if ($request->isMethod('GET') && !$request->ajax()) {
            $route = $request->route()?->getName();
            if (isset(self::ROUTE_LEVELS[$route])) {
                [$level, $label] = self::ROUTE_LEVELS[$route];
                if ($level === 0) {
                    session([
                        'nav_level0' => ['url' => $request->fullUrl(), 'label' => $label],
                        'nav_level1' => null,
                    ]);
                } elseif ($level === 1) {
                    session(['nav_level1' => ['url' => $request->fullUrl(), 'label' => $label]]);
                }
            }
        }
        return $next($request);
    }
}
