@extends('layouts.admin')

@section('title', 'Détails Qualité - ' . ($rapport->etablissement->etablissement ?? $rapport->etablissement->nom ?? 'Établissement'))

@section('content')
<div class="p-4">
    {{-- Header compact --}}
    <div class="flex items-center justify-between mb-3">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.controle-qualite.index') }}" 
               class="w-9 h-9 flex items-center justify-center bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                <i class="fas fa-arrow-left text-gray-600"></i>
            </a>
            <div>
                <h1 class="text-base font-bold text-gray-900">{{ $rapport->etablissement->etablissement ?? $rapport->etablissement->nom ?? 'Établissement' }}</h1>
                <p class="text-xs text-gray-500 mt-0.5">
                    <i class="fas fa-map-marker-alt text-gray-400 text-[10px]"></i>
                    {{ $rapport->etablissement->zone ?? 'Zone non définie' }} 
                    <span class="mx-1">•</span> 
                    <i class="fas fa-calendar text-gray-400 text-[10px]"></i>
                    {{ $rapport->annee_scolaire }}
                </p>
            </div>
        </div>

        {{-- Badge qualité --}}
        <div class="text-center px-4 py-2 rounded-lg shadow-sm border-2
            @if($qualite['badge']['color'] === 'emerald') bg-emerald-50 border-emerald-300
            @elseif($qualite['badge']['color'] === 'green') bg-green-50 border-green-300
            @elseif($qualite['badge']['color'] === 'blue') bg-blue-50 border-blue-300
            @elseif($qualite['badge']['color'] === 'amber') bg-amber-50 border-amber-300
            @elseif($qualite['badge']['color'] === 'orange') bg-orange-50 border-orange-300
            @else bg-red-50 border-red-300
            @endif">
            <div class="flex items-center gap-2">
                <span class="text-2xl">{{ $qualite['badge']['icon'] }}</span>
                <div class="text-left">
                    <p class="text-2xl font-bold leading-none
                        @if($qualite['badge']['color'] === 'emerald') text-emerald-700
                        @elseif($qualite['badge']['color'] === 'green') text-green-700
                        @elseif($qualite['badge']['color'] === 'blue') text-blue-700
                        @elseif($qualite['badge']['color'] === 'amber') text-amber-700
                        @elseif($qualite['badge']['color'] === 'orange') text-orange-700
                        @else text-red-700
                        @endif">{{ $qualite['score_total'] }}</p>
                    <p class="text-[10px] font-medium mt-0.5
                        @if($qualite['badge']['color'] === 'emerald') text-emerald-600
                        @elseif($qualite['badge']['color'] === 'green') text-green-600
                        @elseif($qualite['badge']['color'] === 'blue') text-blue-600
                        @elseif($qualite['badge']['color'] === 'amber') text-amber-600
                        @elseif($qualite['badge']['color'] === 'orange') text-orange-600
                        @else text-red-600
                        @endif">{{ $qualite['badge']['label'] }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Breakdown des scores --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
        {{-- Complétude --}}
        <div class="bg-white rounded-lg shadow-sm border-2 border-blue-200 p-3">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-xs font-semibold text-blue-700">📋 Complétude</h3>
                <span class="text-lg font-bold text-blue-600">{{ $qualite['completude']['score'] }}/40</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                <div class="bg-blue-500 h-2 rounded-full transition-all duration-500" style="width: {{ $qualite['completude']['pourcentage'] }}%"></div>
            </div>
            <p class="text-[10px] text-gray-600 mb-2">{{ $qualite['completude']['pourcentage'] }}% des sections renseignées</p>
            
            <div class="space-y-1">
                @php
                    $labels = [
                        'info_directeur' => 'Info directeur',
                        'effectifs' => 'Effectifs',
                        'personnel' => 'Personnel',
                        'infrastructures' => 'Infrastructures',
                        'structures_comm' => 'Structures comm.',
                        'langues_projets' => 'Langues/Projets',
                        'finances' => 'Finances',
                        'examens' => 'Examens',
                        'capital_immo' => 'Bâtiments',
                        'capital_mob' => 'Mobilier',
                        'materiel_didac' => 'Matériel didac.',
                        'equip_info' => 'Équip. info',
                        'manuels' => 'Manuels',
                        'dictionnaires' => 'Dictionnaires',
                    ];
                @endphp
                @foreach($qualite['completude']['details'] as $section => $detail)
                    <div class="flex items-center justify-between text-[10px]">
                        <span class="text-gray-600">{{ $labels[$section] ?? ucfirst(str_replace('_', ' ', $section)) }}</span>
                        <div class="flex items-center gap-1">
                            <span class="font-semibold {{ isset($detail['manquant']) ? 'text-red-600' : 'text-green-600' }}">
                                {{ $detail['score'] }}/{{ $detail['max'] }}
                            </span>
                            @if(isset($detail['manquant']))
                                <span class="text-red-500 text-xs">❌</span>
                            @else
                                <span class="text-green-500 text-xs">✅</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Cohérence --}}
        <div class="bg-white rounded-lg shadow-sm border-2 border-green-200 p-3">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-xs font-semibold text-green-700">🔍 Cohérence</h3>
                <span class="text-lg font-bold text-green-600">{{ $qualite['coherence']['score'] }}/30</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                <div class="bg-green-500 h-2 rounded-full transition-all duration-500" style="width: {{ $qualite['coherence']['pourcentage'] }}%"></div>
            </div>
            <p class="text-[10px] text-gray-600 mb-2">{{ $qualite['coherence']['pourcentage'] }}% de cohérence des données</p>
            
            @if(!empty($qualite['coherence']['erreurs']))
                <div class="space-y-1">
                    <p class="text-[10px] font-semibold text-red-600 mb-1">{{ count($qualite['coherence']['erreurs']) }} erreur(s) :</p>
                    @foreach($qualite['coherence']['erreurs'] as $erreur)
                        <div class="flex items-start gap-1 p-1.5 bg-red-50 rounded text-[9px] text-red-700">
                            <span>❌</span>
                            <span>{{ $erreur }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex items-center gap-1 p-1.5 bg-green-50 rounded text-[10px] text-green-700">
                    <span>✅</span>
                    <span>Aucune erreur détectée</span>
                </div>
            @endif
        </div>

        {{-- Précision --}}
        <div class="bg-white rounded-lg shadow-sm border-2 border-purple-200 p-3">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-xs font-semibold text-purple-700">🎯 Précision</h3>
                <span class="text-lg font-bold text-purple-600">{{ $qualite['precision']['score'] }}/30</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                <div class="bg-purple-500 h-2 rounded-full transition-all duration-500" style="width: {{ $qualite['precision']['pourcentage'] }}%"></div>
            </div>
            <p class="text-[10px] text-gray-600">{{ $qualite['precision']['pourcentage'] }}% de précision des informations</p>
        </div>
    </div>

    {{-- Anomalies --}}
    @if(!empty($qualite['anomalies']))
        <div class="bg-white rounded-lg shadow-sm border-2 border-red-200 p-3 mb-4">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-xs font-semibold text-red-700">⚠️ Anomalies détectées</h3>
                <span class="px-2 py-0.5 bg-red-100 text-red-700 rounded-full text-[10px] font-semibold">
                    {{ count($qualite['anomalies']) }}
                </span>
            </div>
            
            <div class="space-y-1.5">
                @foreach($qualite['anomalies'] as $anomalie)
                    <div class="flex items-start gap-2 p-2 rounded-lg border 
                        {{ $anomalie['gravite'] === 'error' ? 'bg-red-50 border-red-200' : '' }}
                        {{ $anomalie['gravite'] === 'warning' ? 'bg-amber-50 border-amber-200' : '' }}
                        {{ $anomalie['gravite'] === 'info' ? 'bg-blue-50 border-blue-200' : '' }}">
                        
                        <span class="text-sm">
                            @if($anomalie['gravite'] === 'error') ❌
                            @elseif($anomalie['gravite'] === 'warning') ⚠️
                            @else ℹ️
                            @endif
                        </span>
                        
                        <div class="flex-1">
                            <p class="text-[10px] font-semibold
                                {{ $anomalie['gravite'] === 'error' ? 'text-red-800' : '' }}
                                {{ $anomalie['gravite'] === 'warning' ? 'text-amber-800' : '' }}
                                {{ $anomalie['gravite'] === 'info' ? 'text-blue-800' : '' }}">
                                {{ ucfirst(str_replace('_', ' ', $anomalie['type'])) }}
                            </p>
                            <p class="text-[9px] mt-0.5
                                {{ $anomalie['gravite'] === 'error' ? 'text-red-600' : '' }}
                                {{ $anomalie['gravite'] === 'warning' ? 'text-amber-600' : '' }}
                                {{ $anomalie['gravite'] === 'info' ? 'text-blue-600' : '' }}">
                                {{ $anomalie['message'] }}
                            </p>
                        </div>
                        
                        <span class="px-1.5 py-0.5 rounded-full text-[9px] font-semibold uppercase whitespace-nowrap
                            {{ $anomalie['gravite'] === 'error' ? 'bg-red-100 text-red-700' : '' }}
                            {{ $anomalie['gravite'] === 'warning' ? 'bg-amber-100 text-amber-700' : '' }}
                            {{ $anomalie['gravite'] === 'info' ? 'bg-blue-100 text-blue-700' : '' }}">
                            {{ $anomalie['gravite'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Recommandations --}}
    @if(!empty($recommandations))
        <div class="bg-white rounded-lg shadow-sm border-2 border-blue-200 p-3 mb-4">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-xs font-semibold text-blue-700">💡 Recommandations</h3>
                <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full text-[10px] font-semibold">
                    {{ count($recommandations) }}
                </span>
            </div>
            
            <div class="space-y-1.5">
                @foreach($recommandations as $recommandation)
                    <div class="flex items-start gap-2 p-2 rounded-lg border 
                        {{ $recommandation['priorite'] === 'haute' ? 'bg-orange-50 border-orange-200' : 'bg-gray-50 border-gray-200' }}">
                        
                        <span class="text-sm">
                            @if($recommandation['categorie'] === 'completude') 📝
                            @elseif($recommandation['categorie'] === 'coherence') 🔧
                            @else 🔍
                            @endif
                        </span>
                        
                        <div class="flex-1">
                            <p class="text-[10px] text-gray-800">{{ $recommandation['message'] }}</p>
                        </div>
                        
                        <span class="px-1.5 py-0.5 rounded-full text-[9px] font-semibold uppercase whitespace-nowrap
                            {{ $recommandation['priorite'] === 'haute' ? 'bg-orange-100 text-orange-700' : 'bg-gray-100 text-gray-700' }}">
                            {{ $recommandation['priorite'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Aperçu des données --}}
    <div class="bg-white rounded-lg shadow-sm border-2 border-gray-200 p-3">
        <h3 class="text-xs font-semibold text-gray-800 mb-2">📊 Aperçu des données</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
            {{-- Effectifs --}}
            <div class="border border-gray-200 rounded-lg p-2">
                <h4 class="text-[10px] font-semibold text-gray-700 mb-1">👥 Effectifs</h4>
                <p class="text-lg font-bold text-gray-900">{{ $rapport->effectifs->sum('effectif_total') }}</p>
                <p class="text-[9px] text-gray-500 mt-0.5">{{ $rapport->effectifs->count() }} niveaux</p>
            </div>

            {{-- Personnel --}}
            @if($rapport->personnelEnseignant)
                <div class="border border-gray-200 rounded-lg p-2">
                    <h4 class="text-[10px] font-semibold text-gray-700 mb-1">👨‍🏫 Enseignants</h4>
                    <p class="text-lg font-bold text-gray-900">{{ $rapport->personnelEnseignant->total_personnel }}</p>
                    <p class="text-[9px] text-gray-500 mt-0.5">
                        Ratio: {{ $rapport->personnelEnseignant->ratio_eleves_enseignant ?? 'N/A' }}
                    </p>
                </div>
            @endif

            {{-- Directeur --}}
            @if($rapport->infoDirecteur)
                <div class="border border-gray-200 rounded-lg p-2">
                    <h4 class="text-[10px] font-semibold text-gray-700 mb-1">📞 Directeur</h4>
                    <p class="text-[10px] font-medium text-gray-900">{{ $rapport->infoDirecteur->directeur_nom }}</p>
                    <p class="text-[9px] text-gray-500 mt-0.5">{{ $rapport->infoDirecteur->directeur_contact_1 ?? 'Contact non renseigné' }}</p>
                </div>
            @endif

            {{-- Examens --}}
            <div class="border border-gray-200 rounded-lg p-2">
                <h4 class="text-[10px] font-semibold text-gray-700 mb-1">🎓 Examens</h4>
                <div class="space-y-0.5">
                    <p class="text-[9px] {{ $rapport->cfee ? 'text-green-600' : 'text-gray-400' }}">
                        {{ $rapport->cfee ? '✅' : '❌' }} CFEE
                    </p>
                    <p class="text-[9px] {{ $rapport->cmg ? 'text-green-600' : 'text-gray-400' }}">
                        {{ $rapport->cmg ? '✅' : '❌' }} CMG
                    </p>
                </div>
            </div>

            {{-- Infrastructures --}}
            <div class="border border-gray-200 rounded-lg p-2">
                <h4 class="text-[10px] font-semibold text-gray-700 mb-1">🏫 Infrastructure</h4>
                <p class="text-[10px] {{ $rapport->infrastructuresBase ? 'text-green-600' : 'text-red-600' }}">
                    {{ $rapport->infrastructuresBase ? '✅ Renseignée' : '❌ Non renseignée' }}
                </p>
            </div>

            {{-- Manuels --}}
            <div class="border border-gray-200 rounded-lg p-2">
                <h4 class="text-[10px] font-semibold text-gray-700 mb-1">📚 Manuels</h4>
                <p class="text-[9px] text-gray-600">
                    Élèves: {{ $rapport->manuelsEleves->count() }}<br>
                    Maître: {{ $rapport->manuelsMaitre->count() }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
