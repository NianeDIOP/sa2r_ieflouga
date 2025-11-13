<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnneeScolaire;
use Illuminate\Http\Request;

class AnneeScolaireController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $annees = AnneeScolaire::orderBy('annee', 'desc')->get();
        return view('admin.annees-scolaires.index', compact('annees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'annee' => 'required|string|unique:annees_scolaires,annee',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'description' => 'nullable|string'
        ], [
            'annee.required' => 'L\'année scolaire est requise',
            'annee.unique' => 'Cette année scolaire existe déjà',
            'date_debut.required' => 'La date de début est requise',
            'date_fin.required' => 'La date de fin est requise',
            'date_fin.after' => 'La date de fin doit être après la date de début'
        ]);

        AnneeScolaire::create($validated);

        return redirect()->route('admin.annees-scolaires.index')
            ->with('success', 'Année scolaire créée avec succès');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AnneeScolaire $anneesScolaire)
    {
        $validated = $request->validate([
            'annee' => 'required|string|unique:annees_scolaires,annee,' . $anneesScolaire->id,
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'description' => 'nullable|string'
        ], [
            'annee.required' => 'L\'année scolaire est requise',
            'annee.unique' => 'Cette année scolaire existe déjà',
            'date_debut.required' => 'La date de début est requise',
            'date_fin.required' => 'La date de fin est requise',
            'date_fin.after' => 'La date de fin doit être après la date de début'
        ]);

        $anneesScolaire->update($validated);

        return redirect()->route('admin.annees-scolaires.index')
            ->with('success', 'Année scolaire mise à jour avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AnneeScolaire $anneesScolaire)
    {
        if ($anneesScolaire->is_active) {
            return redirect()->route('admin.annees-scolaires.index')
                ->with('error', 'Impossible de supprimer l\'année scolaire active');
        }

        $anneesScolaire->delete();

        return redirect()->route('admin.annees-scolaires.index')
            ->with('success', 'Année scolaire supprimée avec succès');
    }

    /**
     * Activer une année scolaire
     */
    public function activate(AnneeScolaire $anneesScolaire)
    {
        $anneesScolaire->activate();

        return redirect()->route('admin.annees-scolaires.index')
            ->with('success', 'Année scolaire activée avec succès');
    }
}
