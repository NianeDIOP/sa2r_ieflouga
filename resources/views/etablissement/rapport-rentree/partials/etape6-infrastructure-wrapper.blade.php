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
                    disabled
                    class="px-6 py-2 bg-gray-300 text-gray-500 rounded-lg text-sm font-semibold cursor-not-allowed transition"
                    title="Remplissez toutes les sections obligatoires pour soumettre">
                <i class="fas fa-paper-plane mr-2"></i>Soumettre le Rapport
            </button>
            <div id="rapport-completion-indicator" class="text-xs text-gray-500 ml-4 flex items-center gap-2">
                <i class="fas fa-info-circle"></i>
                <span id="completion-text">Complétez les sections pour activer la soumission</span>
                <span id="completion-progress" class="font-semibold text-emerald-600">0%</span>
            </div>
        @elseif($rapport->statut === 'soumis')
            <div class="px-6 py-2 bg-blue-100 text-blue-800 rounded-lg text-sm font-semibold border border-blue-300">
                <i class="fas fa-clock mr-2"></i>Rapport en attente de validation
            </div>
        @elseif($rapport->statut === 'validé')
            <div class="px-6 py-2 bg-green-100 text-green-800 rounded-lg text-sm font-semibold border border-green-300">
                <i class="fas fa-check-circle mr-2"></i>Rapport validé
            </div>
        @endif
    </div>
</div>