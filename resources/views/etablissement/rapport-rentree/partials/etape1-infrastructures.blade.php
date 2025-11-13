<!-- Infrastructures de Base -->
<div id="infrastructures" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
    <h3 class="text-xs font-semibold text-gray-700 mb-3 flex items-center uppercase tracking-wide">
        <i class="fas fa-building text-gray-500 mr-2 text-xs"></i>
        Infrastructures de Base
    </h3>
    
    <p class="text-xs text-gray-500 mb-4 italic">
        <i class="fas fa-info-circle text-blue-500 mr-1"></i>
        Cochez uniquement les infrastructures existantes. Si rien n'est coché, cela signifie que l'infrastructure n'existe pas dans l'établissement.
    </p>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        
        <!-- CPE -->
        <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
            <label class="flex items-center space-x-2 mb-2">
                <input type="checkbox" name="cpe_existe" data-section="infrastructures" 
                       {{ ($rapport->infrastructuresBase->cpe_existe ?? false) ? 'checked' : '' }}
                       onchange="autoSave('infrastructures'); toggleField(this, 'cpe_nombre')"
                       class="w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500">
                <span class="text-xs font-medium text-gray-700">CPE (Case Tout-Petits)</span>
            </label>
            <input type="number" id="cpe_nombre" name="cpe_nombre" data-section="infrastructures" 
                   value="{{ $rapport->infrastructuresBase->cpe_nombre ?? '' }}"
                   onchange="autoSave('infrastructures')"
                   {{ !($rapport->infrastructuresBase->cpe_existe ?? false) ? 'disabled' : '' }}
                   min="0" placeholder="Nombre"
                   class="w-full px-2 py-1 text-sm border border-gray-300 rounded disabled:bg-gray-100 disabled:cursor-not-allowed">
        </div>

        <!-- Clôture -->
        <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
            <label class="flex items-center space-x-2 mb-2">
                <input type="checkbox" name="cloture_existe" data-section="infrastructures" 
                       {{ ($rapport->infrastructuresBase->cloture_existe ?? false) ? 'checked' : '' }}
                       onchange="autoSave('infrastructures'); toggleField(this, 'cloture_type')"
                       class="w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500">
                <span class="text-xs font-medium text-gray-700">Clôture</span>
            </label>
            <select id="cloture_type" name="cloture_type" data-section="infrastructures" 
                    onchange="autoSave('infrastructures')"
                    {{ !($rapport->infrastructuresBase->cloture_existe ?? false) ? 'disabled' : '' }}
                    class="w-full px-2 py-1 text-sm border border-gray-300 rounded disabled:bg-gray-100 disabled:cursor-not-allowed">
                <option value="">Type...</option>
                <option value="dur" {{ ($rapport->infrastructuresBase->cloture_type ?? '') === 'dur' ? 'selected' : '' }}>Dur</option>
                <option value="provisoire" {{ ($rapport->infrastructuresBase->cloture_type ?? '') === 'provisoire' ? 'selected' : '' }}>Provisoire</option>
                <option value="haie" {{ ($rapport->infrastructuresBase->cloture_type ?? '') === 'haie' ? 'selected' : '' }}>Haie</option>
                <option value="autre" {{ ($rapport->infrastructuresBase->cloture_type ?? '') === 'autre' ? 'selected' : '' }}>Autre</option>
            </select>
        </div>

        <!-- Eau -->
        <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
            <label class="flex items-center space-x-2 mb-2">
                <input type="checkbox" name="eau_existe" data-section="infrastructures" 
                       {{ ($rapport->infrastructuresBase->eau_existe ?? false) ? 'checked' : '' }}
                       onchange="autoSave('infrastructures'); toggleField(this, 'eau_type')"
                       class="w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500">
                <span class="text-xs font-medium text-gray-700">Eau</span>
            </label>
            <select id="eau_type" name="eau_type" data-section="infrastructures" 
                    onchange="autoSave('infrastructures')"
                    {{ !($rapport->infrastructuresBase->eau_existe ?? false) ? 'disabled' : '' }}
                    class="w-full px-2 py-1 text-sm border border-gray-300 rounded disabled:bg-gray-100 disabled:cursor-not-allowed">
                <option value="">Type...</option>
                <option value="robinet" {{ ($rapport->infrastructuresBase->eau_type ?? '') === 'robinet' ? 'selected' : '' }}>Robinet</option>
                <option value="forage" {{ ($rapport->infrastructuresBase->eau_type ?? '') === 'forage' ? 'selected' : '' }}>Forage</option>
                <option value="puits" {{ ($rapport->infrastructuresBase->eau_type ?? '') === 'puits' ? 'selected' : '' }}>Puits</option>
                <option value="autre" {{ ($rapport->infrastructuresBase->eau_type ?? '') === 'autre' ? 'selected' : '' }}>Autre</option>
            </select>
        </div>

        <!-- Électricité -->
        <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
            <label class="flex items-center space-x-2 mb-2">
                <input type="checkbox" name="electricite_existe" data-section="infrastructures" 
                       {{ ($rapport->infrastructuresBase->electricite_existe ?? false) ? 'checked' : '' }}
                       onchange="autoSave('infrastructures'); toggleField(this, 'electricite_type')"
                       class="w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500">
                <span class="text-xs font-medium text-gray-700">Électricité</span>
            </label>
            <select id="electricite_type" name="electricite_type" data-section="infrastructures" 
                    onchange="autoSave('infrastructures')"
                    {{ !($rapport->infrastructuresBase->electricite_existe ?? false) ? 'disabled' : '' }}
                    class="w-full px-2 py-1 text-sm border border-gray-300 rounded disabled:bg-gray-100 disabled:cursor-not-allowed">
                <option value="">Type...</option>
                <option value="SENELEC" {{ ($rapport->infrastructuresBase->electricite_type ?? '') === 'SENELEC' ? 'selected' : '' }}>SENELEC</option>
                <option value="solaire" {{ ($rapport->infrastructuresBase->electricite_type ?? '') === 'solaire' ? 'selected' : '' }}>Solaire</option>
                <option value="groupe" {{ ($rapport->infrastructuresBase->electricite_type ?? '') === 'groupe' ? 'selected' : '' }}>Groupe électrogène</option>
                <option value="autre" {{ ($rapport->infrastructuresBase->electricite_type ?? '') === 'autre' ? 'selected' : '' }}>Autre</option>
            </select>
        </div>

        <!-- Internet -->
        <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
            <label class="flex items-center space-x-2 mb-2">
                <input type="checkbox" name="connexion_internet_existe" data-section="infrastructures" 
                       {{ ($rapport->infrastructuresBase->connexion_internet_existe ?? false) ? 'checked' : '' }}
                       onchange="autoSave('infrastructures'); toggleField(this, 'connexion_internet_type')"
                       class="w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500">
                <span class="text-xs font-medium text-gray-700">Connexion Internet</span>
            </label>
            <select id="connexion_internet_type" name="connexion_internet_type" data-section="infrastructures" 
                    onchange="autoSave('infrastructures')"
                    {{ !($rapport->infrastructuresBase->connexion_internet_existe ?? false) ? 'disabled' : '' }}
                    class="w-full px-2 py-1 text-sm border border-gray-300 rounded disabled:bg-gray-100 disabled:cursor-not-allowed">
                <option value="">Type...</option>
                <option value="fibre" {{ ($rapport->infrastructuresBase->connexion_internet_type ?? '') === 'fibre' ? 'selected' : '' }}>Fibre optique</option>
                <option value="4G" {{ ($rapport->infrastructuresBase->connexion_internet_type ?? '') === '4G' ? 'selected' : '' }}>4G/5G</option>
                <option value="satellite" {{ ($rapport->infrastructuresBase->connexion_internet_type ?? '') === 'satellite' ? 'selected' : '' }}>Satellite</option>
                <option value="autre" {{ ($rapport->infrastructuresBase->connexion_internet_type ?? '') === 'autre' ? 'selected' : '' }}>Autre</option>
            </select>
        </div>

        <!-- Cantine -->
        <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
            <label class="flex items-center space-x-2 mb-2">
                <input type="checkbox" name="cantine_existe" data-section="infrastructures" 
                       {{ ($rapport->infrastructuresBase->cantine_existe ?? false) ? 'checked' : '' }}
                       onchange="autoSave('infrastructures'); toggleField(this, 'cantine_type')"
                       class="w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500">
                <span class="text-xs font-medium text-gray-700">Cantine Scolaire</span>
            </label>
            <select id="cantine_type" name="cantine_type" data-section="infrastructures" 
                    onchange="autoSave('infrastructures')"
                    {{ !($rapport->infrastructuresBase->cantine_existe ?? false) ? 'disabled' : '' }}
                    class="w-full px-2 py-1 text-sm border border-gray-300 rounded disabled:bg-gray-100 disabled:cursor-not-allowed">
                <option value="">Gestion...</option>
                <option value="state" {{ ($rapport->infrastructuresBase->cantine_type ?? '') === 'state' ? 'selected' : '' }}>État</option>
                <option value="partenaire" {{ ($rapport->infrastructuresBase->cantine_type ?? '') === 'partenaire' ? 'selected' : '' }}>Partenaire</option>
                <option value="communaute" {{ ($rapport->infrastructuresBase->cantine_type ?? '') === 'communaute' ? 'selected' : '' }}>Communauté</option>
                <option value="autre" {{ ($rapport->infrastructuresBase->cantine_type ?? '') === 'autre' ? 'selected' : '' }}>Autre</option>
            </select>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleInfraField(checkbox, fieldId) {
    const field = document.getElementById(fieldId);
    field.disabled = !checkbox.checked;
    if (!checkbox.checked) {
        field.value = '';
    }
}
</script>
@endpush
