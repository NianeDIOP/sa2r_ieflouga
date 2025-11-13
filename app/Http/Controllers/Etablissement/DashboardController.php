<?php

namespace App\Http\Controllers\Etablissement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Etablissement;
use App\Models\AnneeScolaire;

class DashboardController extends Controller
{
    /**
     * Afficher le dashboard établissement
     */
    public function index()
    {
        $user = Auth::guard('etablissement')->user();
        
        // Récupérer les informations complètes de l'établissement
        $etablissement = Etablissement::where('code', $user->code)->first();
        
        if (!$etablissement) {
            return redirect()->route('login')->with('error', 'Établissement non trouvé');
        }
        
        // Récupérer l'année scolaire active
        $anneeScolaireActive = AnneeScolaire::getActive();
        $annee_active = $anneeScolaireActive ? $anneeScolaireActive->annee : null;

        return view('etablissement.dashboard', compact('etablissement', 'user', 'annee_active', 'anneeScolaireActive'));
    }
}
