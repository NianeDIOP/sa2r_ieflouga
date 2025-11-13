<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SA2R - IEF Louga')</title>
    
    <!-- Tailwind CSS CDN - MÊME FRAMEWORK QU'AVANT -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome - MÊMES ICÔNES -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    
    @stack('styles')
</head>
<body class="min-h-screen flex flex-col bg-gray-50">
    
    <!-- Navbar - MÊME DESIGN EXACTEMENT -->
    <header class="bg-[#002147] border-b border-blue-900/30 sticky top-0 z-50 shadow-lg">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6">
            <div class="flex items-center justify-between h-14">
                
                <!-- Logo - MÊME LOGO -->
                <a href="{{ route('admin.dashboard') }}" class="flex items-center hover:opacity-90 transition-opacity">
                    <img src="{{ asset('images/logo.svg') }}" alt="SA2R - IEF Louga" class="h-10 w-auto object-contain">
                </a>

                <!-- Navigation principale - MÊME STRUCTURE -->
                <nav class="flex items-center space-x-1">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2 px-3 py-2 rounded-md text-sm font-medium transition-all duration-200 text-blue-100 hover:bg-white/10 hover:text-white">
                        <i class="fas fa-chart-line text-xs"></i>
                        <span>Dashboard</span>
                    </a>
                    
                    <a href="#" class="flex items-center space-x-2 px-3 py-2 rounded-md text-sm font-medium transition-all duration-200 text-blue-100 hover:bg-white/10 hover:text-white">
                        <i class="fas fa-building text-xs"></i>
                        <span>Établissements</span>
                    </a>
                    
                    <a href="#" class="flex items-center space-x-2 px-3 py-2 rounded-md text-sm font-medium transition-all duration-200 text-blue-100 hover:bg-white/10 hover:text-white">
                        <i class="fas fa-users text-xs"></i>
                        <span>Effectifs</span>
                    </a>
                    
                    <a href="#" class="flex items-center space-x-2 px-3 py-2 rounded-md text-sm font-medium transition-all duration-200 text-blue-100 hover:bg-white/10 hover:text-white">
                        <i class="fas fa-chalkboard-teacher text-xs"></i>
                        <span>Personnel</span>
                    </a>
                    
                    <a href="#" class="flex items-center space-x-2 px-3 py-2 rounded-md text-sm font-medium transition-all duration-200 text-blue-100 hover:bg-white/10 hover:text-white">
                        <i class="fas fa-graduation-cap text-xs"></i>
                        <span>Résultats</span>
                    </a>
                    
                    <a href="#" class="flex items-center space-x-2 px-3 py-2 rounded-md text-sm font-medium transition-all duration-200 text-blue-100 hover:bg-white/10 hover:text-white">
                        <i class="fas fa-hammer text-xs"></i>
                        <span>Infrastructure</span>
                    </a>
                    
                    <a href="#" class="flex items-center space-x-2 px-3 py-2 rounded-md text-sm font-medium transition-all duration-200 text-amber-300 hover:bg-amber-500/20 hover:text-white">
                        <i class="fas fa-file-alt text-xs"></i>
                        <span>Rapports</span>
                    </a>
                    
                    <a href="#" class="flex items-center space-x-2 px-3 py-2 rounded-md text-sm font-medium transition-all duration-200 text-emerald-300 hover:bg-emerald-500/20 hover:text-white">
                        <i class="fas fa-chart-bar text-xs"></i>
                        <span>Analyses</span>
                    </a>
                </nav>

                <!-- User section - MÊME STYLE -->
                <div class="flex items-center space-x-2">
                    <button class="p-1.5 text-blue-200 hover:text-white hover:bg-white/10 rounded-md transition-colors">
                        <i class="fas fa-bell text-base"></i>
                    </button>
                    
                    <div class="relative">
                        <button id="settings-btn" class="p-1.5 text-blue-200 hover:text-white hover:bg-white/10 rounded-md transition-colors">
                            <i class="fas fa-cog text-base"></i>
                        </button>
                        
                        <!-- Dropdown Paramètres - 8 OPTIONS ADMIN -->
                        <div id="settings-menu" class="hidden absolute right-0 mt-2 w-72 bg-white rounded-lg shadow-xl border border-gray-200 py-2 z-50">
                            <div class="px-4 py-2 border-b border-gray-100">
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Administration</p>
                            </div>
                            
                            <!-- 1. Import Données -->
                            <a href="{{ route('admin.etablissements.index') }}" class="block px-4 py-2.5 hover:bg-blue-50 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-file-excel text-green-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Import Données</p>
                                        <p class="text-xs text-gray-500">Importer fichier Excel</p>
                                    </div>
                                </div>
                            </a>
                            
                            <!-- 2. Gestion Comptes -->
                            <a href="#" class="block px-4 py-2.5 hover:bg-blue-50 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-users-cog text-blue-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Gestion Comptes</p>
                                        <p class="text-xs text-gray-500">CRUD établissements</p>
                                    </div>
                                </div>
                            </a>
                            
                            <!-- 3. Suivi Progression -->
                            <a href="#" class="block px-4 py-2.5 hover:bg-blue-50 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-tasks text-indigo-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Suivi Progression</p>
                                        <p class="text-xs text-gray-500">État des soumissions</p>
                                    </div>
                                </div>
                            </a>
                            
                            <!-- 4. Contrôle Qualité -->
                            <a href="#" class="block px-4 py-2.5 hover:bg-blue-50 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-check-double text-amber-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Contrôle Qualité</p>
                                        <p class="text-xs text-gray-500">Validation des données</p>
                                    </div>
                                </div>
                            </a>
                            
                            <!-- 5. Qualité des Données -->
                            <a href="#" class="block px-4 py-2.5 hover:bg-blue-50 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-star text-purple-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Qualité des Données</p>
                                        <p class="text-xs text-gray-500">Scores par soumission</p>
                                    </div>
                                </div>
                            </a>
                            
                            <!-- 6. Années Scolaires -->
                            <a href="{{ route('admin.annees-scolaires.index') }}" class="block px-4 py-2.5 hover:bg-blue-50 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-calendar-alt text-indigo-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Années Scolaires</p>
                                        <p class="text-xs text-gray-500">Gestion des années</p>
                                    </div>
                                </div>
                            </a>
                            
                            <!-- 7. Vérification Données -->
                            <a href="#" class="block px-4 py-2.5 hover:bg-blue-50 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-teal-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-search text-teal-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Vérification Données</p>
                                        <p class="text-xs text-gray-500">Inspection technique</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-2 pl-3 ml-2 border-l border-white/20">
                        <!-- Menu utilisateur -->
                        <div class="relative group">
                            <button class="flex items-center space-x-2">
                                <div class="w-9 h-9 bg-red-600 rounded-full flex items-center justify-center shadow-sm">
                                    <span class="text-white text-sm font-bold">{{ strtoupper(substr(Auth::guard('admin')->user()->username ?? 'AD', 0, 2)) }}</span>
                                </div>
                                <div class="hidden md:block">
                                    <p class="text-sm font-semibold text-white leading-tight">{{ Auth::guard('admin')->user()->username ?? 'Admin' }}</p>
                                </div>
                                <i class="fas fa-chevron-down text-[10px] text-blue-200"></i>
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
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 max-w-[1400px] mx-auto px-4 sm:px-6 py-6 w-full">
        @yield('content')
    </main>

    <!-- Footer - MÊME STYLE -->
    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 py-4">
            <div class="flex flex-col md:flex-row items-center justify-between text-[13px] text-gray-600">
                <p>© 2024 <span class="font-semibold">SA2R</span> - Système d'Analyse des Rapports de Rentrée</p>
                <p class="mt-1 md:mt-0">IEF Louga</p>
            </div>
        </div>
    </footer>

    <script>
        // Dropdown settings menu
        const settingsBtn = document.getElementById('settings-btn');
        const settingsMenu = document.getElementById('settings-menu');
        
        settingsBtn?.addEventListener('click', () => {
            settingsMenu.classList.toggle('hidden');
        });
        
        document.addEventListener('click', (e) => {
            if (!settingsBtn?.contains(e.target) && !settingsMenu?.contains(e.target)) {
                settingsMenu?.classList.add('hidden');
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
