<!-- Recrutement CI (Cours d'Initiation) -->
<div id="recrutement-ci" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
    <h3 class="text-xs font-semibold text-gray-700 mb-3 flex items-center uppercase tracking-wide">
        <i class="fas fa-user-plus text-gray-500 mr-2 text-xs"></i>
        Recrutement CI (Cours d'Initiation)
    </h3>
    
    <p class="text-xs text-gray-500 mb-4 italic">
        <i class="fas fa-info-circle text-blue-500 mr-1"></i>
        Renseignez les informations sur le recrutement en Cours d'Initiation. Les totaux sont calculés automatiquement.
    </p>

    <form id="recrutement-ci-form" data-save-url="{{ route('etablissement.rapport-rentree.save-recrutement-ci', $rapport) }}">
        @csrf
        
        <!-- Informations de base -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Nombre de CI</label>
                <input type="number" 
                       id="ci_nombre" 
                       name="ci_nombre"
                       min="0"
                       value="{{ $rapport->recrutementCi?->ci_nombre }}"
                       data-section="recrutement-ci"
                       oninput="calculateRecrutementCiTotals(); autoSave('recrutement-ci')"
                       class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                       placeholder="Ex: 2">
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Statut</label>
                <select id="ci_statut" 
                        name="ci_statut"
                        data-section="recrutement-ci"
                        onchange="autoSave('recrutement-ci')"
                        class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                    <option value="">-- Sélectionnez --</option>
                    <option value="homologue" {{ ($rapport->recrutementCi?->ci_statut ?? '') == 'homologue' ? 'selected' : '' }}>Homologué</option>
                    <option value="non_homologue" {{ ($rapport->recrutementCi?->ci_statut ?? '') == 'non_homologue' ? 'selected' : '' }}>Non homologué</option>
                </select>
            </div>
        </div>

        <!-- Effectifs Octobre -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Garçons Octobre</label>
                <input type="number" 
                       id="ci_octobre_garcons" 
                       name="ci_octobre_garcons"
                       min="0"
                       value="{{ $rapport->recrutementCi?->ci_octobre_garcons }}"
                       data-section="recrutement-ci"
                       oninput="calculateRecrutementCiTotals(); autoSave('recrutement-ci')"
                       class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                       placeholder="Ex: 25">
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Filles Octobre</label>
                <input type="number" 
                       id="ci_octobre_filles" 
                       name="ci_octobre_filles"
                       min="0"
                       value="{{ $rapport->recrutementCi?->ci_octobre_filles }}"
                       data-section="recrutement-ci"
                       oninput="calculateRecrutementCiTotals(); autoSave('recrutement-ci')"
                       class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                       placeholder="Ex: 20">
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Total Octobre</label>
                <input type="number" 
                       id="ci_octobre_total" 
                       name="ci_octobre_total"
                       readonly
                       value="{{ $rapport->recrutementCi?->ci_octobre_total }}"
                       class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md bg-emerald-100 font-medium text-emerald-700"
                       placeholder="Calculé automatiquement">
            </div>
        </div>

        <!-- Effectifs Mai -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Garçons Mai</label>
                <input type="number" 
                       id="ci_mai_garcons" 
                       name="ci_mai_garcons"
                       min="0"
                       value="{{ $rapport->recrutementCi?->ci_mai_garcons }}"
                       data-section="recrutement-ci"
                       oninput="calculateRecrutementCiTotals(); autoSave('recrutement-ci')"
                       class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                       placeholder="Ex: 21">
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Filles Mai</label>
                <input type="number" 
                       id="ci_mai_filles" 
                       name="ci_mai_filles"
                       min="0"
                       value="{{ $rapport->recrutementCi?->ci_mai_filles }}"
                       data-section="recrutement-ci"
                       oninput="calculateRecrutementCiTotals(); autoSave('recrutement-ci')"
                       class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                       placeholder="Ex: 19">
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Total Mai</label>
                <input type="number" 
                       id="ci_mai_total" 
                       name="ci_mai_total"
                       readonly
                       value="{{ $rapport->recrutementCi?->ci_mai_total }}"
                       class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md bg-emerald-100 font-medium text-emerald-700"
                       placeholder="Calculé automatiquement">
            </div>
        </div>

        <!-- Pourcentages (calculés automatiquement) -->
        <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-3">
            <h4 class="text-xs font-semibold text-emerald-700 mb-3 flex items-center uppercase tracking-wide">
                <i class="fas fa-chart-line text-emerald-500 mr-2 text-xs"></i>
                Pourcentages filles (Calculé automatiquement)
            </h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div class="text-center">
                    <div class="text-lg font-bold text-emerald-600" id="pourcentage-octobre-ci">0%</div>
                    <div class="text-xs text-gray-600">Octobre</div>
                </div>
                <div class="text-center">
                    <div class="text-lg font-bold text-emerald-600" id="pourcentage-mai-ci">0%</div>
                    <div class="text-xs text-gray-600">Mai</div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
// Calculer les totaux pour Recrutement CI
function calculateRecrutementCiTotals() {
    // Calcul des totaux avec garcons + filles = total
    
    // Octobre
    const octGarcons = parseInt(document.getElementById('ci_octobre_garcons').value) || 0;
    const octFilles = parseInt(document.getElementById('ci_octobre_filles').value) || 0;
    const octTotal = octGarcons + octFilles;
    document.getElementById('ci_octobre_total').value = octTotal;

    // Mai
    const maiGarcons = parseInt(document.getElementById('ci_mai_garcons').value) || 0;
    const maiFilles = parseInt(document.getElementById('ci_mai_filles').value) || 0;
    const maiTotal = maiGarcons + maiFilles;
    document.getElementById('ci_mai_total').value = maiTotal;

    // Calcul des pourcentages de filles
    if (octTotal > 0) {
        const pourcentageOctobre = Math.round((octFilles / octTotal) * 100);
        document.getElementById('pourcentage-octobre-ci').textContent = pourcentageOctobre + '%';
    } else {
        document.getElementById('pourcentage-octobre-ci').textContent = '0%';
    }

    if (maiTotal > 0) {
        const pourcentageMai = Math.round((maiFilles / maiTotal) * 100);
        document.getElementById('pourcentage-mai-ci').textContent = pourcentageMai + '%';
    } else {
        document.getElementById('pourcentage-mai-ci').textContent = '0%';
    }
}

// Calculer les totaux au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    calculateRecrutementCiTotals();
});
</script>