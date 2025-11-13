<!-- ÉTAPE 6 : INFRASTRUCTURE & ÉQUIPEMENTS -->
<div id="etape6" class="hidden">
    @include('etablissement.rapport-rentree.partials.etape6-capital-immobilier')
    @include('etablissement.rapport-rentree.partials.etape6-capital-mobilier')
    @include('etablissement.rapport-rentree.partials.etape6-equipements-informatiques')

    <!-- Boutons de navigation -->
    <div class="flex justify-between pt-6">
        <button type="button" onclick="switchToEtape(5)" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-300 transition">
            <i class="fas fa-arrow-left mr-2"></i>Précédent
        </button>
        <button type="button" onclick="submitRapport()" class="px-6 py-2 bg-emerald-600 text-white rounded-lg text-sm font-semibold hover:bg-emerald-700 transition">
            Finaliser<i class="fas fa-check ml-2"></i>
        </button>
    </div>
</div>