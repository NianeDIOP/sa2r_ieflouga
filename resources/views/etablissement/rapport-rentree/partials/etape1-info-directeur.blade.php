<!-- Info Directeur -->
<div id="info-directeur" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
    <h3 class="text-xs font-semibold text-gray-700 mb-3 flex items-center uppercase tracking-wide">
        <i class="fas fa-user text-gray-500 mr-2 text-xs"></i>
        Informations du Directeur
    </h3>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Nom complet</label>
            <input type="text" name="directeur_nom" data-section="info-directeur" 
                   value="{{ $rapport->infoDirecteur->directeur_nom ?? '' }}"
                   onchange="autoSave('info-directeur')"
                   class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
        </div>
        
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Contact 1</label>
            <input type="tel" name="directeur_contact_1" data-section="info-directeur" 
                   value="{{ $rapport->infoDirecteur->directeur_contact_1 ?? '' }}"
                   onchange="autoSave('info-directeur')"
                   placeholder="77 123 45 67"
                   class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
        </div>
        
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Contact 2</label>
            <input type="tel" name="directeur_contact_2" data-section="info-directeur" 
                   value="{{ $rapport->infoDirecteur->directeur_contact_2 ?? '' }}"
                   onchange="autoSave('info-directeur')"
                   placeholder="70 123 45 67"
                   class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
        </div>
        
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Email</label>
            <input type="email" name="directeur_email" data-section="info-directeur" 
                   value="{{ $rapport->infoDirecteur->directeur_email ?? '' }}"
                   onchange="autoSave('info-directeur')"
                   placeholder="directeur@etablissement.sn"
                   class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
        </div>
        
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Distance du siège IEF (km)</label>
            <input type="number" name="distance_siege" data-section="info-directeur" 
                   value="{{ $rapport->infoDirecteur->distance_siege ?? '' }}"
                   onchange="autoSave('info-directeur')"
                   step="0.1" min="0"
                   class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
        </div>
    </div>
</div>
