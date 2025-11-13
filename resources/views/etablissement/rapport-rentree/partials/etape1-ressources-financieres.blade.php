<!-- Ressources Financières -->
<div id="finances" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
    <h3 class="text-xs font-semibold text-gray-700 mb-3 flex items-center uppercase tracking-wide">
        <i class="fas fa-coins text-gray-500 mr-2 text-xs"></i>
        Ressources Financières (Subventions)
    </h3>
    
    <p class="text-xs text-gray-500 mb-4 italic">
        <i class="fas fa-info-circle text-blue-500 mr-1"></i>
        Cochez uniquement les sources de financement reçues. Si aucune case n'est cochée, cela signifie que l'établissement ne bénéficie pas de cette ressource. Le total sera calculé automatiquement.
    </p>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        
        <!-- Subvention État -->
        <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
            <label class="flex items-center space-x-2 mb-2">
                <input type="checkbox" name="subvention_etat_existe" data-section="ressources-financieres" 
                       {{ ($rapport->ressourcesFinancieres->subvention_etat_existe ?? false) ? 'checked' : '' }}
                       onchange="autoSave('ressources-financieres'); toggleFinanceField(this, 'subvention_etat_montant')"
                       class="w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500">
                <span class="text-xs font-medium text-gray-700">Subvention État</span>
            </label>
            <input type="number" id="subvention_etat_montant" name="subvention_etat_montant" data-section="ressources-financieres" 
                   value="{{ $rapport->ressourcesFinancieres->subvention_etat_montant ?? '' }}"
                   onchange="autoSave('ressources-financieres')"
                   {{ !($rapport->ressourcesFinancieres->subvention_etat_existe ?? false) ? 'disabled' : '' }}
                   min="0" step="0.01" placeholder="Montant (FCFA)"
                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-emerald-500 disabled:bg-gray-100 disabled:cursor-not-allowed">
        </div>

        <!-- Subvention Partenaires/ONG -->
        <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
            <label class="flex items-center space-x-2 mb-2">
                <input type="checkbox" name="subvention_partenaires_existe" data-section="ressources-financieres" 
                       {{ ($rapport->ressourcesFinancieres->subvention_partenaires_existe ?? false) ? 'checked' : '' }}
                       onchange="autoSave('ressources-financieres'); toggleFinanceField(this, 'subvention_partenaires_montant')"
                       class="w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500">
                <span class="text-xs font-medium text-gray-700">Subvention Partenaires/ONG</span>
            </label>
            <input type="number" id="subvention_partenaires_montant" name="subvention_partenaires_montant" data-section="ressources-financieres" 
                   value="{{ $rapport->ressourcesFinancieres->subvention_partenaires_montant ?? '' }}"
                   onchange="autoSave('ressources-financieres')"
                   {{ !($rapport->ressourcesFinancieres->subvention_partenaires_existe ?? false) ? 'disabled' : '' }}
                   min="0" step="0.01" placeholder="Montant (FCFA)"
                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-emerald-500 disabled:bg-gray-100 disabled:cursor-not-allowed">
        </div>

        <!-- Subvention Collectivités Locales -->
        <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
            <label class="flex items-center space-x-2 mb-2">
                <input type="checkbox" name="subvention_collectivites_existe" data-section="ressources-financieres" 
                       {{ ($rapport->ressourcesFinancieres->subvention_collectivites_existe ?? false) ? 'checked' : '' }}
                       onchange="autoSave('ressources-financieres'); toggleFinanceField(this, 'subvention_collectivites_montant')"
                       class="w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500">
                <span class="text-xs font-medium text-gray-700">Subvention Collectivités</span>
            </label>
            <input type="number" id="subvention_collectivites_montant" name="subvention_collectivites_montant" data-section="ressources-financieres" 
                   value="{{ $rapport->ressourcesFinancieres->subvention_collectivites_montant ?? '' }}"
                   onchange="autoSave('ressources-financieres')"
                   {{ !($rapport->ressourcesFinancieres->subvention_collectivites_existe ?? false) ? 'disabled' : '' }}
                   min="0" step="0.01" placeholder="Montant (FCFA)"
                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-emerald-500 disabled:bg-gray-100 disabled:cursor-not-allowed">
        </div>

        <!-- Contribution Communauté (CGE/APE) -->
        <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
            <label class="flex items-center space-x-2 mb-2">
                <input type="checkbox" name="subvention_communaute_existe" data-section="ressources-financieres" 
                       {{ ($rapport->ressourcesFinancieres->subvention_communaute_existe ?? false) ? 'checked' : '' }}
                       onchange="autoSave('ressources-financieres'); toggleFinanceField(this, 'subvention_communaute_montant')"
                       class="w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500">
                <span class="text-xs font-medium text-gray-700">Contribution Communauté</span>
            </label>
            <input type="number" id="subvention_communaute_montant" name="subvention_communaute_montant" data-section="ressources-financieres" 
                   value="{{ $rapport->ressourcesFinancieres->subvention_communaute_montant ?? '' }}"
                   onchange="autoSave('ressources-financieres')"
                   {{ !($rapport->ressourcesFinancieres->subvention_communaute_existe ?? false) ? 'disabled' : '' }}
                   min="0" step="0.01" placeholder="Montant (FCFA)"
                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-emerald-500 disabled:bg-gray-100 disabled:cursor-not-allowed">
        </div>

        <!-- Ressources Générées (AGR) -->
        <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
            <label class="flex items-center space-x-2 mb-2">
                <input type="checkbox" name="ressources_generees_existe" data-section="ressources-financieres" 
                       {{ ($rapport->ressourcesFinancieres->ressources_generees_existe ?? false) ? 'checked' : '' }}
                       onchange="autoSave('ressources-financieres'); toggleFinanceField(this, 'ressources_generees_montant')"
                       class="w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500">
                <span class="text-xs font-medium text-gray-700">Ressources Générées (AGR)</span>
            </label>
            <input type="number" id="ressources_generees_montant" name="ressources_generees_montant" data-section="ressources-financieres" 
                   value="{{ $rapport->ressourcesFinancieres->ressources_generees_montant ?? '' }}"
                   onchange="autoSave('ressources-financieres')"
                   {{ !($rapport->ressourcesFinancieres->ressources_generees_existe ?? false) ? 'disabled' : '' }}
                   min="0" step="0.01" placeholder="Montant (FCFA)"
                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-emerald-500 disabled:bg-gray-100 disabled:cursor-not-allowed">
        </div>

        <!-- Total (auto-calculé) -->
        <div class="border-2 border-emerald-200 rounded-lg p-3 bg-emerald-50">
            <span class="block text-sm font-bold text-emerald-700 mb-2">Total Ressources</span>
            <div class="text-lg font-bold text-emerald-800" id="total-ressources-display">
                {{ number_format($rapport->ressourcesFinancieres->total_ressources ?? 0, 0, ',', ' ') }} FCFA
            </div>
            <p class="mt-1 text-xs text-emerald-600">Calculé automatiquement</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleFinanceField(checkbox, fieldId) {
    const field = document.getElementById(fieldId);
    field.disabled = !checkbox.checked;
    if (!checkbox.checked) {
        field.value = '';
    }
    calculateFinanceTotal();
}

// Calculer le total en temps réel
function calculateFinanceTotal() {
    const etat = parseFloat(document.querySelector('input[name="subvention_etat_montant"]')?.value) || 0;
    const partenaires = parseFloat(document.querySelector('input[name="subvention_partenaires_montant"]')?.value) || 0;
    const collectivites = parseFloat(document.querySelector('input[name="subvention_collectivites_montant"]')?.value) || 0;
    const communaute = parseFloat(document.querySelector('input[name="subvention_communaute_montant"]')?.value) || 0;
    const generees = parseFloat(document.querySelector('input[name="ressources_generees_montant"]')?.value) || 0;
    
    const total = etat + partenaires + collectivites + communaute + generees;
    
    document.getElementById('total-ressources-display').textContent = 
        new Intl.NumberFormat('fr-FR').format(total) + ' FCFA';
}

// Ajouter l'événement sur tous les champs de montant
document.addEventListener('DOMContentLoaded', function() {
    const montantFields = document.querySelectorAll('input[name$="_montant"]');
    montantFields.forEach(field => {
        field.addEventListener('input', calculateFinanceTotal);
    });
    calculateFinanceTotal(); // Calculer au chargement
});
</script>
@endpush

<!-- Navigation Button -->
<div class="flex items-center justify-end mt-6 pt-4 border-t border-gray-200">
    <button type="button" onclick="switchToEtape(2)" class="px-6 py-2 bg-emerald-600 text-white rounded-lg text-sm font-semibold hover:bg-emerald-700 transition">
        Suivant<i class="fas fa-arrow-right ml-2"></i>
    </button>
</div>
