<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SA2R - Établissement')</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    
    @stack('styles')
</head>
<body class="min-h-screen bg-gray-50">
    
    <!-- Navbar Supérieure -->
    <header class="bg-[#002147] border-b border-blue-900/30 sticky top-0 z-50 shadow-lg">
        <div class="max-w-full px-4">
            <div class="flex items-center justify-between h-14">
                
                <!-- Logo et Nom Établissement -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('etablissement.dashboard') }}" class="flex items-center hover:opacity-90 transition-opacity">
                        <img src="{{ asset('images/logo.svg') }}" alt="SA2R - IEF Louga" class="h-10 w-auto object-contain">
                    </a>
                    
                    @php
                        $user = Auth::guard('etablissement')->user();
                        $etablissement = null;
                        if ($user && $user->etablissement_id) {
                            $etablissement = \App\Models\Etablissement::find($user->etablissement_id);
                        } elseif ($user && $user->code) {
                            $etablissement = \App\Models\Etablissement::where('code', $user->code)->first();
                        }
                    @endphp
                    
                    @if($etablissement)
                    <div class="border-l border-blue-700 pl-3 hidden md:block">
                        <div class="text-sm font-semibold text-white leading-tight">{{ $etablissement->etablissement }}</div>
                        <div class="text-xs text-blue-300 font-mono">Code: {{ $etablissement->code }}</div>
                    </div>
                    @endif
                </div>

                <!-- Navigation principale -->
                <nav class="flex items-center space-x-1">
                    <a href="{{ route('etablissement.dashboard') }}" 
                       class="flex items-center space-x-2 px-3 py-2 rounded-md text-sm font-medium transition-all duration-200 text-blue-100 hover:bg-white/10 hover:text-white {{ request()->routeIs('etablissement.dashboard') ? 'bg-white/10' : '' }}">
                        <i class="fas fa-home text-xs"></i>
                        <span>Dashboard</span>
                    </a>
                    
                    <a href="{{ route('etablissement.rapport-rentree.index') }}" 
                       class="flex items-center space-x-2 px-3 py-2 rounded-md text-sm font-bold transition-all duration-200 {{ request()->routeIs('etablissement.rapport-rentree.*') ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'text-emerald-400 hover:bg-emerald-500/10 hover:text-emerald-300' }}">
                        <i class="fas fa-file-alt text-xs"></i>
                        <span>Rapport de Rentrée</span>
                    </a>
                    
                    <a href="#" 
                       class="flex items-center space-x-2 px-3 py-2 rounded-md text-sm font-medium transition-all duration-200 text-blue-100 hover:bg-white/10 hover:text-white">
                        <i class="fas fa-history text-xs"></i>
                        <span>Historique</span>
                    </a>
                </nav>

                <!-- Infos Établissement -->
                <div class="flex items-center space-x-4">
                    <!-- Année Active -->
                    <div class="hidden md:flex items-center space-x-2 px-3 py-1.5 bg-emerald-500/20 rounded-lg border border-emerald-500/30">
                        <i class="fas fa-calendar-alt text-emerald-400 text-xs"></i>
                        <span class="text-xs font-medium text-emerald-300">
                            {{ $anneeScolaireActive->annee ?? 'N/A' }}
                        </span>
                    </div>

                    <!-- Menu utilisateur -->
                    <div class="relative group">
                        <button class="flex items-center space-x-2 px-3 py-2 rounded-md text-sm font-medium text-blue-100 hover:bg-white/10 hover:text-white transition-all">
                            <i class="fas fa-user-circle text-base"></i>
                            <span class="hidden sm:inline">Mon Compte</span>
                            <i class="fas fa-chevron-down text-[10px]"></i>
                        </button>
                        
                        <!-- Dropdown -->
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all">
                            <a href="#" class="flex items-center space-x-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 rounded-t-lg">
                                <i class="fas fa-user text-gray-400 text-xs w-4"></i>
                                <span>Mon Profil</span>
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center space-x-2 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 rounded-b-lg border-t">
                                    <i class="fas fa-sign-out-alt text-red-400 text-xs w-4"></i>
                                    <span>Déconnexion</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Layout sans Sidebar (la sidebar est maintenant dans la vue elle-même) -->
    <div class="flex min-h-[calc(100vh-3.5rem)]">
        
        <!-- Contenu Principal -->
        <main class="flex-1 overflow-x-hidden">
            <div class="max-w-[1400px] mx-auto p-4 sm:p-6">
                
                <!-- Messages Flash -->
                @if(session('success'))
                <div class="mb-4 rounded-lg p-3 text-sm bg-green-50 text-green-800 border border-green-200">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="mb-4 rounded-lg p-3 text-sm bg-red-50 text-red-800 border border-red-200">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ session('error') }}
                </div>
                @endif

                <!-- Contenu de la page -->
                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')
</body>
</html>
