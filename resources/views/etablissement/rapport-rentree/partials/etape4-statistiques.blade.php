<!-- Statistiques Générales -->
<div id="statistiques-personnel" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
    <h3 class="text-xs font-semibold text-gray-700 mb-3 flex items-center uppercase tracking-wide">
        <i class="fas fa-chart-bar text-gray-500 mr-2 text-xs"></i>
        Statistiques Générales
    </h3>
    
    <p class="text-xs text-gray-500 mb-4 italic">
        <i class="fas fa-info-circle text-blue-500 mr-1"></i>
        Totaux généraux et indicateurs calculés automatiquement.
    </p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Totaux généraux -->
        <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4">
            <h4 class="text-xs font-semibold text-emerald-700 mb-3 flex items-center">
                <i class="fas fa-users text-emerald-500 mr-2"></i>
                Totaux Généraux
            </h4>
            
            <div class="space-y-3">
                <div class="flex justify-between items-center py-1">
                    <span class="text-xs text-emerald-700">Total Hommes :</span>
                    <span id="total-hommes" class="text-sm font-bold text-emerald-800">
                        {{ $rapport->personnelEnseignant?->total_personnel_hommes ?? 0 }}
                    </span>
                </div>
                
                <div class="flex justify-between items-center py-1 border-t border-emerald-200">
                    <span class="text-xs text-emerald-700">Total Femmes :</span>
                    <span id="total-femmes" class="text-sm font-bold text-emerald-800">
                        {{ $rapport->personnelEnseignant?->total_personnel_femmes ?? 0 }}
                    </span>
                </div>
                
                <div class="flex justify-between items-center py-2 border-t-2 border-emerald-300 bg-emerald-100 rounded px-2">
                    <span class="text-xs font-semibold text-emerald-800">TOTAL GÉNÉRAL :</span>
                    <span id="total-general" class="text-lg font-bold text-emerald-900">
                        {{ $rapport->personnelEnseignant?->total_personnel ?? 0 }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Taux de féminisation -->
        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
            <h4 class="text-xs font-semibold text-purple-700 mb-3 flex items-center">
                <i class="fas fa-venus text-purple-500 mr-2"></i>
                Taux de Féminisation
            </h4>
            
            <div class="text-center">
                <div class="mb-3">
                    <span id="taux-feminisation" class="text-3xl font-bold text-purple-800">
                        {{ $rapport->personnelEnseignant?->taux_feminisation ?? '0.0' }}%
                    </span>
                </div>
                
                <p class="text-xs text-purple-600">
                    (Femmes / Total général) × 100
                </p>
                
                <!-- Indicateur visuel -->
                <div class="mt-4">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div id="barre-feminisation" 
                             class="bg-purple-500 h-2 rounded-full transition-all duration-300" 
                             style="width: {{ $rapport->personnelEnseignant?->taux_feminisation ?? 0 }}%">
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Indicateur de parité</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Répartition par catégories -->
    <div class="mt-6 bg-gray-50 border border-gray-200 rounded-lg p-4">
        <h4 class="text-xs font-semibold text-gray-700 mb-4 flex items-center">
            <i class="fas fa-pie-chart text-gray-500 mr-2"></i>
            Répartition par Catégories
        </h4>
        
        <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
            <!-- Titulaires -->
            <div class="text-center p-2 bg-white rounded border">
                <div class="text-lg font-bold text-blue-600" id="pourcentage-titulaires">0%</div>
                <div class="text-xs text-gray-600">Titulaires</div>
            </div>
            
            <!-- Vacataires -->
            <div class="text-center p-2 bg-white rounded border">
                <div class="text-lg font-bold text-green-600" id="pourcentage-vacataires">0%</div>
                <div class="text-xs text-gray-600">Vacataires</div>
            </div>
            
            <!-- Volontaires -->
            <div class="text-center p-2 bg-white rounded border">
                <div class="text-lg font-bold text-orange-600" id="pourcentage-volontaires">0%</div>
                <div class="text-xs text-gray-600">Volontaires</div>
            </div>
            
            <!-- Contractuels -->
            <div class="text-center p-2 bg-white rounded border">
                <div class="text-lg font-bold text-red-600" id="pourcentage-contractuels">0%</div>
                <div class="text-xs text-gray-600">Contractuels</div>
            </div>
            
            <!-- Communautaires -->
            <div class="text-center p-2 bg-white rounded border">
                <div class="text-lg font-bold text-purple-600" id="pourcentage-communautaires">0%</div>
                <div class="text-xs text-gray-600">Communautaires</div>
            </div>
        </div>
    </div>
</div>

<script>
function calculatePersonnelTotals() {
    // Categories for specialite
    const specialiteCategories = ['titulaires', 'vacataires', 'volontaires', 'contractuels', 'communautaires'];
    
    // Categories for corps
    const corpsCategories = ['instituteurs', 'instituteurs_adjoints', 'professeurs'];
    
    // Categories for diplomes
    const diplomesCategories = ['bac', 'bfem', 'cfee', 'licence', 'master', 'autres_diplomes'];
    
    let totalHommes = 0;
    let totalFemmes = 0;
    
    // Calculate totals for each specialite category (H + F = T)
    // Only use specialite categories for global totals to avoid double counting
    specialiteCategories.forEach(category => {
        const hommes = parseInt(document.getElementById(category + '_hommes')?.value || 0);
        const femmes = parseInt(document.getElementById(category + '_femmes')?.value || 0);
        const total = hommes + femmes;
        
        const totalField = document.getElementById(category + '_total');
        if (totalField) {
            totalField.value = total;
        }
        
        totalHommes += hommes;
        totalFemmes += femmes;
    });
    
    // Calculate totals for each corps category (H + F = T) - for display only
    corpsCategories.forEach(category => {
        const hommes = parseInt(document.getElementById(category + '_hommes')?.value || 0);
        const femmes = parseInt(document.getElementById(category + '_femmes')?.value || 0);
        const total = hommes + femmes;
        
        const totalField = document.getElementById(category + '_total');
        if (totalField) {
            totalField.value = total;
        }
        
        // DO NOT add to global totals to avoid double counting
    });
    
    // Calculate totals for each diplomes category (H + F = T) - for display only
    diplomesCategories.forEach(category => {
        const hommes = parseInt(document.getElementById(category + '_hommes')?.value || 0);
        const femmes = parseInt(document.getElementById(category + '_femmes')?.value || 0);
        const total = hommes + femmes;
        
        const totalField = document.getElementById(category + '_total');
        if (totalField) {
            totalField.value = total;
        }
        
        // DO NOT add to global totals - this is classification, not addition
    });
    
    // Calculate TIC totals (H + F = T)
    const ticHommes = parseInt(document.getElementById('enseignants_formes_tic_hommes')?.value || 0);
    const ticFemmes = parseInt(document.getElementById('enseignants_formes_tic_femmes')?.value || 0);
    const ticTotal = ticHommes + ticFemmes;
    
    const ticTotalField = document.getElementById('enseignants_formes_tic_total');
    if (ticTotalField) {
        ticTotalField.value = ticTotal;
    }
    
    // Update general statistics
    const totalGeneral = totalHommes + totalFemmes;
    
    // Update display elements
    const totalHommesElement = document.getElementById('total-hommes');
    const totalFemmesElement = document.getElementById('total-femmes');
    const totalGeneralElement = document.getElementById('total-general');
    
    if (totalHommesElement) totalHommesElement.textContent = totalHommes;
    if (totalFemmesElement) totalFemmesElement.textContent = totalFemmes;
    if (totalGeneralElement) totalGeneralElement.textContent = totalGeneral;
    
    // Calculate feminization rate
    const tauxFeminisation = totalGeneral > 0 ? ((totalFemmes / totalGeneral) * 100).toFixed(1) : 0;
    
    const tauxFeminisationElement = document.getElementById('taux-feminisation');
    const barreFeminisationElement = document.getElementById('barre-feminisation');
    
    if (tauxFeminisationElement) {
        tauxFeminisationElement.textContent = tauxFeminisation + '%';
    }
    
    if (barreFeminisationElement) {
        barreFeminisationElement.style.width = tauxFeminisation + '%';
    }
    
    // Calculate category percentages
    specialiteCategories.forEach(category => {
        const categoryTotal = parseInt(document.getElementById(category + '_total')?.value || 0);
        const percentage = totalGeneral > 0 ? ((categoryTotal / totalGeneral) * 100).toFixed(1) : 0;
        
        const percentageElement = document.getElementById('pourcentage-' + category);
        if (percentageElement) {
            percentageElement.textContent = percentage + '%';
        }
    });
    
    // Calculate TIC percentage
    const pourcentageTic = totalGeneral > 0 ? ((ticTotal / totalGeneral) * 100).toFixed(1) : 0;
    const pourcentageTicElement = document.getElementById('pourcentage-tic');
    if (pourcentageTicElement) {
        pourcentageTicElement.textContent = pourcentageTic + '%';
    }
}

// Initialize calculations on page load
document.addEventListener('DOMContentLoaded', function() {
    calculatePersonnelTotals();
});
</script>