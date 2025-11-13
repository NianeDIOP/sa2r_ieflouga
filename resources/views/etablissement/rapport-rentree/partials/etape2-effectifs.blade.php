<!-- ÉTAPE 2 : EFFECTIFS -->
<div id="etape2" class="hidden">
    @php
    $niveaux = ['CI', 'CP', 'CE1', 'CE2', 'CM1', 'CM2'];
    $effectifsData = collect($niveaux)->mapWithKeys(function($niveau) use ($rapport) {
        $effectif = $rapport->effectifs->where('niveau', $niveau)->first();
        return [$niveau => $effectif ?? (object)[]];
    });
    @endphp

    @include('etablissement.rapport-rentree.partials.etape2-nombre-classes')
    @include('etablissement.rapport-rentree.partials.etape2-effectifs-totaux')
    @include('etablissement.rapport-rentree.partials.etape2-redoublants')
    @include('etablissement.rapport-rentree.partials.etape2-abandons')
    @include('etablissement.rapport-rentree.partials.etape2-handicaps')
    @include('etablissement.rapport-rentree.partials.etape2-situations-speciales')

    <!-- Navigation Buttons -->
    <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-200">
        <button type="button" onclick="switchToEtape(1)" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-300 transition">
            <i class="fas fa-arrow-left mr-2"></i>Précédent
        </button>
        <button type="button" onclick="switchToEtape(3, 'cmg')" class="px-6 py-2 bg-emerald-600 text-white rounded-lg text-sm font-semibold hover:bg-emerald-700 transition">
            Suivant<i class="fas fa-arrow-right ml-2"></i>
        </button>
    </div>
</div>

@push('scripts')
<script>
// Calculer le total Garçons + Filles pour effectifs
function calculateEffectifTotal(input, category) {
    const niveau = input.dataset.niveau;
    const garconsInput = document.querySelector(`input[data-niveau="${niveau}"][data-category="${category}"][name*="_garcons"]`);
    const fillesInput = document.querySelector(`input[data-niveau="${niveau}"][data-category="${category}"][name*="_filles"]`);
    const totalInput = document.querySelector(`input[data-niveau="${niveau}"][data-total-for="${category}"]`);
    
    if (garconsInput && fillesInput && totalInput) {
        const garcons = parseInt(garconsInput.value) || 0;
        const filles = parseInt(fillesInput.value) || 0;
        totalInput.value = garcons + filles;
    }
}
</script>
@endpush
