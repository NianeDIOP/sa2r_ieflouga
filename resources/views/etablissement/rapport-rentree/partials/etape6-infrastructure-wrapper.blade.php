<!-- ÉTAPE 6 : INFRASTRUCTURE ET ÉQUIPEMENTS -->
<div id="etape6" class="hidden">
    @include('etablissement.rapport-rentree.partials.etape6-capital-immobilier')
    @include('etablissement.rapport-rentree.partials.etape6-capital-mobilier')
    @include('etablissement.rapport-rentree.partials.etape6-equipements-informatiques')

    <!-- Boutons de navigation -->
    <div class="flex justify-between pt-6">
        <button type="button" onclick="switchToEtape(5)" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-300 transition">
            <i class="fas fa-arrow-left mr-2"></i>Précédent
        </button>
        
        @if($rapport->statut === 'brouillon' || $rapport->statut === 'rejeté')
            <button type="button" 
                    id="btnSubmitRapport"
                    onclick="showSubmitModal()" 
                    class="px-8 py-3 bg-gradient-to-r from-emerald-600 to-green-600 text-white rounded-lg text-sm font-bold hover:from-emerald-700 hover:to-green-700 transition shadow-lg">
                <i class="fas fa-paper-plane mr-2"></i>Soumettre le Rapport
            </button>
        @elseif($rapport->statut === 'soumis')
            <div class="px-8 py-3 bg-blue-100 text-blue-800 rounded-lg text-sm font-semibold border border-blue-300">
                <i class="fas fa-clock mr-2"></i>Rapport en attente de validation
            </div>
        @elseif($rapport->statut === 'validé')
            <div class="px-8 py-3 bg-green-100 text-green-800 rounded-lg text-sm font-semibold border border-green-300">
                <i class="fas fa-check-circle mr-2"></i>Rapport validé
            </div>
        @endif
    </div>
</div>