<?php

namespace App\Http\Controllers\Etablissement;

use App\Http\Controllers\Controller;
use App\Models\Rapport;
use App\Models\AnneeScolaire;
use App\Services\RapportExcelService;
use Illuminate\Support\Facades\Auth;

class RapportHistoriqueController extends Controller
{
    /**
     * Afficher la liste des rapports soumis
     */
    public function index()
    {
        $etablissement = Auth::user()->etablissement;
        
        // Récupérer toutes les années scolaires disponibles
        $anneesDisponibles = AnneeScolaire::orderBy('annee', 'desc')->get();
        
        // Récupérer l'année filtrée (ou toutes par défaut)
        $anneeSelectionnee = request('annee');
        
        // Requête de base
        $query = Rapport::where('etablissement_id', $etablissement->id)
            ->whereIn('statut', ['soumis', 'validé', 'rejeté'])
            ->with(['historique.user', 'soumis_par', 'valide_par']);
        
        // Appliquer le filtre année si sélectionné
        if ($anneeSelectionnee) {
            $query->where('annee_scolaire', $anneeSelectionnee);
        }
        
        $rapports = $query->orderBy('annee_scolaire', 'desc')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('etablissement.rapport-rentree.historique.index', compact('rapports', 'etablissement', 'anneesDisponibles', 'anneeSelectionnee'));
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

    /**
     * Télécharger un rapport en format Excel
     */
    public function downloadExcel(Rapport $rapport)
    {
        // Vérifier que le rapport appartient à l'établissement
        if ($rapport->etablissement_id !== Auth::user()->etablissement_id) {
            abort(403);
        }

        // Générer et télécharger le fichier Excel
        $excelService = new RapportExcelService($rapport);
        return $excelService->download();
    }
}
