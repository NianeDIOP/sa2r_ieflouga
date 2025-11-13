<!-- ÉTAPE 3 : EXAMENS ET RECRUTEMENT CI -->
<div id="etape3" class="hidden">
    @include('etablissement.rapport-rentree.partials.etape3-cmg')
    @include('etablissement.rapport-rentree.partials.etape3-cfee')
    @include('etablissement.rapport-rentree.partials.etape3-entree-sixieme')
    @include('etablissement.rapport-rentree.partials.etape3-recrutement-ci')

    <!-- Boutons de navigation -->
    <div class="flex justify-between pt-6">
        <button type="button" onclick="switchToEtape(2)" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-300 transition">
            <i class="fas fa-arrow-left mr-2"></i>Précédent
        </button>
        <button type="button" onclick="switchToEtape(4, 'repartition-specialite')" class="px-6 py-2 bg-emerald-600 text-white rounded-lg text-sm font-semibold hover:bg-emerald-700 transition">
            Suivant<i class="fas fa-arrow-right ml-2"></i>
        </button>
    </div>
</div>