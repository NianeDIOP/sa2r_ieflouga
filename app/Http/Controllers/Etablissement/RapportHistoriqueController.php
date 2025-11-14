<?php

namespace App\Http\Controllers\Etablissement;

use App\Http\Controllers\Controller;
use App\Models\Rapport;
use App\Models\AnneeScolaire;
use Illuminate\Support\Facades\Auth;

class RapportHistoriqueController extends Controller
{
    /**
     * Afficher la liste des rapports soumis
     */
    public function index()
    {
        $etablissement = Auth::user()->etablissement;
        
        // Récupérer tous les rapports soumis de cet établissement
        $rapports = Rapport::where('etablissement_id', $etablissement->id)
            ->whereIn('statut', ['soumis', 'validé', 'rejeté'])
            ->with(['historique.user', 'soumis_par', 'valide_par'])
            ->orderBy('annee_scolaire', 'desc')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('etablissement.rapport-rentree.historique.index', compact('rapports', 'etablissement'));
    }

    /**
     * Afficher un rapport en version imprimable
     */
    public function show(Rapport $rapport)
    {
        // Vérifier que le rapport appartient à l'établissement
        if ($rapport->etablissement_id !== Auth::user()->etablissement_id) {
            abort(403);
        }

        // Charger toutes les relations
        $rapport->load([
            'etablissement',
            'infoDirecteur',
            'infrastructuresBase',
            'structuresCommunautaires',
            'languesProjets',
            'ressourcesFinancieres',
            'effectifs',
            'cmg',
            'recrutementCi',
            'cfee',
            'entreeSixieme',
            'personnelEnseignant',
            'materielDidactique',
            'capitalImmobilier',
            'capitalMobilier',
            'equipementInformatique',
            'manuelsEleves',
            'manuelsMaitre',
            'dictionnaires',
            'historique.user',
            'soumis_par',
            'valide_par'
        ]);

        return view('etablissement.rapport-rentree.historique.show', compact('rapport'));
    }
}
