<!-- ÉTAPE 4 : PERSONNEL ENSEIGNANT -->
<div id="etape4" class="hidden">
    @include('etablissement.rapport-rentree.partials.etape4-repartition-specialite')
    @include('etablissement.rapport-rentree.partials.etape4-repartition-corps')
    @include('etablissement.rapport-rentree.partials.etape4-repartition-diplomes')
    @include('etablissement.rapport-rentree.partials.etape4-competences-tic')
    @include('etablissement.rapport-rentree.partials.etape4-statistiques')

    <!-- Boutons de navigation -->
    <div class="flex justify-between pt-6">
        <button type="button" onclick="switchToEtape(3)" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-300 transition">
            <i class="fas fa-arrow-left mr-2"></i>Précédent
        </button>
        <button type="button" onclick="switchToEtape(5)" class="px-6 py-2 bg-emerald-600 text-white rounded-lg text-sm font-semibold hover:bg-emerald-700 transition">
            Suivant<i class="fas fa-arrow-right ml-2"></i>
        </button>
    </div>
</div>