<!-- CFEE (Certificat de Fin d'Études Élémentaires) -->
<div id="cfee" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
    <h3 class="text-xs font-semibold text-gray-700 mb-3 flex items-center uppercase tracking-wide">
        <i class="fas fa-graduation-cap text-gray-500 mr-2 text-xs"></i>
        CFEE (Certificat de Fin d'Études Élémentaires)
    </h3>
    
    <p class="text-xs text-gray-500 mb-4 italic">
        <i class="fas fa-info-circle text-blue-500 mr-1"></i>
        Renseignez les informations sur les résultats du CFEE. Les taux de réussite sont calculés automatiquement.
    </p>

    <form id="cfee-form" data-save-url="{{ route('etablissement.rapport-rentree.save-cfee', $rapport) }}">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
            <!-- CANDIDATS -->
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Total candidats</label>
                <input type="number" 
                       id="cfee_candidats_total" 
                       name="cfee_candidats_total"
                       min="0"
                       value="{{ $rapport->cfee?->cfee_candidats_total }}"
                       data-section="cfee"
                       oninput="calculateCfeeTotals(); updateMaxValues(); autoSave('cfee')"
                       class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                       placeholder="Ex: 35">
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Candidates filles</label>
                <input type="number" 
                       id="cfee_candidats_filles" 
                       name="cfee_candidats_filles" 
                       min="0"
                       value="{{ $rapport->cfee?->cfee_candidats_filles }}"
                       data-section="cfee"
                       oninput="calculateCfeeTotals(); updateMaxValues(); autoSave('cfee')"
                       class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                       placeholder="Ex: 20">
            </div>

            <!-- ADMIS -->
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Total admis</label>
                <input type="number" 
                       id="cfee_admis_total" 
                       name="cfee_admis_total"
                       min="0"
                       value="{{ $rapport->cfee?->cfee_admis_total }}"
                       data-section="cfee"
                       oninput="calculateCfeeTotals(); updateMaxValues(); autoSave('cfee')"
                       class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                       placeholder="Ex: 30">
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Admises filles</label>
                <input type="number" 
                       id="cfee_admis_filles" 
                       name="cfee_admis_filles" 
                       min="0"
                       max="999"
                       value="{{ $rapport->cfee?->cfee_admis_filles }}"
                       data-section="cfee"
                       oninput="calculateCfeeTotals(); updateMaxValues(); autoSave('cfee')"
                       class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                       placeholder="Ex: 18">
            </div>
        </div>

        <!-- Taux de réussite -->
        <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-3">
            <h4 class="text-xs font-semibold text-emerald-700 mb-3 flex items-center uppercase tracking-wide">
                <i class="fas fa-chart-line text-emerald-500 mr-2 text-xs"></i>
                Taux de Réussite (Calculé automatiquement)
            </h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div class="text-center">
                    <div class="text-lg font-bold text-emerald-600" id="cfee_taux_filles">
                        {{ $rapport->cfee?->cfee_taux_reussite_filles ? number_format($rapport->cfee->cfee_taux_reussite_filles, 1) : '0.0' }}%
                    </div>
                    <div class="text-xs text-gray-600">Filles</div>
                </div>
                <div class="text-center">
                    <div class="text-lg font-bold text-emerald-600" id="cfee_taux_general">
                        {{ $rapport->cfee?->cfee_taux_reussite ? number_format($rapport->cfee->cfee_taux_reussite, 1) : '0.0' }}%
                    </div>
                    <div class="text-xs text-gray-600">Général</div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function calculateCfeeTotals() {
    // Récupérer les valeurs saisies
    const candidatesFilles = parseInt(document.getElementById('cfee_candidats_filles').value) || 0;
    const candidatsTotal = parseInt(document.getElementById('cfee_candidats_total').value) || 0;
    const admisesFilles = parseInt(document.getElementById('cfee_admis_filles').value) || 0;
    const admisTotal = parseInt(document.getElementById('cfee_admis_total').value) || 0;
    
    // VALIDATION 1 : Total candidats ne peut pas être inférieur aux candidates filles
    if (candidatsTotal < candidatesFilles) {
        document.getElementById('cfee_candidats_total').value = candidatesFilles;
        showValidationError('cfee_candidats_total', `Minimum : ${candidatesFilles} (nombre de filles)`);
    } else {
        clearValidationError('cfee_candidats_total');
    }
    
    // VALIDATION 2 : Total admis ne peut pas être inférieur aux admises filles
    if (admisTotal < admisesFilles) {
        document.getElementById('cfee_admis_total').value = admisesFilles;
        showValidationError('cfee_admis_total', `Minimum : ${admisesFilles} (nombre de filles)`);
    } else {
        clearValidationError('cfee_admis_total');
    }
    
    // VALIDATION 3 : Empêcher admis > candidats
    const candidatsTotalFinal = Math.max(candidatsTotal, candidatesFilles);
    const admisesTotalFinal = Math.max(admisTotal, admisesFilles);
    
    if (admisesFilles > candidatesFilles) {
        document.getElementById('cfee_admis_filles').value = candidatesFilles;
        showValidationError('cfee_admis_filles', `Maximum : ${candidatesFilles} candidates filles`);
    } else {
        clearValidationError('cfee_admis_filles');
    }
    
    if (admisesTotalFinal > candidatsTotalFinal) {
        document.getElementById('cfee_admis_total').value = candidatsTotalFinal;
        showValidationError('cfee_admis_total', `Maximum : ${candidatsTotalFinal} candidats total`);
    } else if (admisTotal >= admisesFilles) {
        clearValidationError('cfee_admis_total');
    }
    
    // Utiliser les valeurs corrigées pour les calculs
    const candidatsTotalCorrige = Math.max(candidatsTotal, candidatesFilles);
    const admisesTotalCorrige = Math.min(Math.max(admisTotal, admisesFilles), candidatsTotalCorrige);
    const admisFillesCorrige = Math.min(admisesFilles, candidatesFilles);
    
    // Calculer les garçons automatiquement (pour affichage seulement)
    const candidatsGarcons = Math.max(0, candidatsTotalCorrige - candidatesFilles);
    const admisGarcons = Math.max(0, admisesTotalCorrige - admisFillesCorrige);
    
    // Calculer les taux de réussite (sans garçons)
    const tauxFilles = candidatesFilles > 0 ? (admisFillesCorrige / candidatesFilles * 100) : 0;
    const tauxGeneral = candidatsTotalCorrige > 0 ? (admisesTotalCorrige / candidatsTotalCorrige * 100) : 0;
    
    // Mettre à jour l'affichage des taux
    document.getElementById('cfee_taux_filles').textContent = tauxFilles.toFixed(1) + '%';
    document.getElementById('cfee_taux_general').textContent = tauxGeneral.toFixed(1) + '%';
}

function showValidationError(inputId, message) {
    const input = document.getElementById(inputId);
    input.style.borderColor = '#ef4444';
    input.style.backgroundColor = '#fef2f2';
    input.title = message;
    
    // Afficher tooltip temporaire
    if (!input.nextElementSibling || !input.nextElementSibling.classList.contains('validation-tooltip')) {
        const tooltip = document.createElement('div');
        tooltip.className = 'validation-tooltip text-xs text-red-600 mt-1';
        tooltip.textContent = message;
        input.parentNode.appendChild(tooltip);
        
        setTimeout(() => {
            if (tooltip.parentNode) {
                tooltip.parentNode.removeChild(tooltip);
            }
        }, 3000);
    }
}

function clearValidationError(inputId) {
    const input = document.getElementById(inputId);
    input.style.borderColor = '';
    input.style.backgroundColor = '';
    input.title = '';
    
    const tooltip = input.parentNode.querySelector('.validation-tooltip');
    if (tooltip) {
        tooltip.parentNode.removeChild(tooltip);
    }
}

// Initialisation au chargement
document.addEventListener('DOMContentLoaded', function() {
    calculateCfeeTotals();
    updateMaxValues();
});

function updateMaxValues() {
    // Mettre à jour les attributs max et min pour empêcher la saisie illogique
    const candidatesFilles = parseInt(document.getElementById('cfee_candidats_filles').value) || 0;
    const candidatsTotal = parseInt(document.getElementById('cfee_candidats_total').value) || 0;
    
    // Total candidats doit être au moins égal au nombre de filles
    document.getElementById('cfee_candidats_total').setAttribute('min', candidatesFilles);
    
    // Total admis doit être au moins égal au nombre de filles admises
    const admisesFilles = parseInt(document.getElementById('cfee_admis_filles').value) || 0;
    document.getElementById('cfee_admis_total').setAttribute('min', admisesFilles);
    
    // Les admis ne peuvent pas dépasser les candidats
    document.getElementById('cfee_admis_filles').setAttribute('max', candidatesFilles);
    document.getElementById('cfee_admis_total').setAttribute('max', Math.max(candidatsTotal, candidatesFilles));
}
</script>