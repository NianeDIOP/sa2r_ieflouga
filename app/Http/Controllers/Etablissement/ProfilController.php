<?php

namespace App\Http\Controllers\Etablissement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfilController extends Controller
{
    /**
     * Afficher le profil de l'établissement
     */
    public function index()
    {
        $user = Auth::user();
        $etablissement = \App\Models\Etablissement::where('code', $user->code)->first();
        
        // Récupérer les infos du directeur depuis le rapport actif
        $anneeScolaireActive = \App\Models\AnneeScolaire::where('is_active', true)->first();
        $infoDirecteur = null;
        
        if ($anneeScolaireActive && $etablissement) {
            $rapport = \App\Models\Rapport::where('etablissement_id', $etablissement->id)
                ->where('annee_scolaire', $anneeScolaireActive->annee)
                ->with('infoDirecteur')
                ->first();
            
            if ($rapport && $rapport->infoDirecteur) {
                $infoDirecteur = $rapport->infoDirecteur;
            }
        }
        
        return view('etablissement.profil', [
            'user' => $user,
            'etablissement' => $etablissement,
            'infoDirecteur' => $infoDirecteur,
            'anneeScolaireActive' => $anneeScolaireActive
        ]);
    }

    /**
     * Changer le mot de passe
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.required' => 'Le mot de passe actuel est requis.',
            'current_password.current_password' => 'Le mot de passe actuel est incorrect.',
            'password.required' => 'Le nouveau mot de passe est requis.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('etablissement.profil')
            ->with('success', 'Mot de passe modifié avec succès.');
    }
}
