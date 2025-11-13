<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Afficher le dashboard admin
     */
    public function index()
    {
        // Statistiques à implémenter
        $stats = [
            'total_etablissements' => 0,
            'etablissements_public' => 0,
            'etablissements_prive' => 0,
            'total_eleves' => 0,
            'total_garcons' => 0,
            'total_filles' => 0,
            'total_handicap_moteur' => 0,
            'total_handicap_visuel' => 0,
            'total_orphelins' => 0,
            'total_sans_extrait' => 0,
            'taux_soumission_effectifs' => 0,
            'soumissions_effectifs' => 0,
            'taux_soumission_finances' => 0,
            'soumissions_finances' => 0,
            'taux_soumission_personnel' => 0,
            'soumissions_personnel' => 0,
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
