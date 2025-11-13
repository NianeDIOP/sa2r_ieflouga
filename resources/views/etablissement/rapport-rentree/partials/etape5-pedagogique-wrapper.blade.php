<!-- ÉTAPE 5 : MATÉRIEL PÉDAGOGIQUE -->
<div id="etape5" class="hidden">
    @include('etablissement.rapport-rentree.partials.etape5-manuels-eleves')
    @include('etablissement.rapport-rentree.partials.etape5-manuels-maitre')
    @include('etablissement.rapport-rentree.partials.etape5-dictionnaires')
    @include('etablissement.rapport-rentree.partials.etape5-materiel-didactique')
    @include('etablissement.rapport-rentree.partials.etape5-geometrie')
    {{-- Mesure supprimé - déjà dans materiel-didactique --}}

    <!-- Boutons de navigation -->
    <div class="flex justify-between pt-6">
        <button type="button" onclick="switchToEtape(4)" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-300 transition">
            <i class="fas fa-arrow-left mr-2"></i>Précédent
        </button>
        <button type="button" onclick="switchToEtape(6)" class="px-6 py-2 bg-emerald-500 text-white rounded-lg text-sm font-semibold hover:bg-emerald-600 transition">
            Suivant<i class="fas fa-arrow-right ml-2"></i>
        </button>
    </div>
</div>