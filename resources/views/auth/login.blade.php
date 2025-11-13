<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - SA2R</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'sa2r-blue': '#002147',
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen bg-[#002147] flex items-center justify-center p-3">
    <div class="max-w-sm w-full">
        <!-- Logo et titre -->
        <div class="text-center mb-3">
            <div class="inline-flex items-center justify-center">
                <img 
                    src="{{ asset('images/logo.svg') }}" 
                    alt="SA2R - IEF Louga" 
                    class="h-14 w-auto object-contain"
                />
            </div>
            <p class="text-blue-200 text-sm mt-2">Système d'Analyse des Rapports de Rentrée</p>
        </div>

        <!-- Formulaire de connexion -->
        <div class="bg-white rounded-lg shadow-xl p-6">
            <h2 class="text-xl font-bold text-[#002147] mb-5 text-center">
                Connexion
            </h2>

            @if(session('error'))
                <div class="mb-4 bg-red-50 border-l-2 border-red-500 rounded-r p-3">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-red-600 mr-2"></i>
                        <p class="text-red-800 text-sm font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                
                <!-- Identifiant -->
                <div>
                    <label for="identifiant" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Identifiant
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-400 w-4"></i>
                        </div>
                        <input
                            id="identifiant"
                            type="text"
                            name="identifiant"
                            class="block w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                            placeholder="Nom d'utilisateur ou code"
                            value="{{ old('identifiant') }}"
                            required
                            autofocus
                        />
                    </div>
                </div>

                <!-- Mot de passe -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Mot de passe
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400 w-4"></i>
                        </div>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            class="block w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                            placeholder="Votre mot de passe"
                            required
                        />
                    </div>
                </div>

                <!-- Bouton connexion -->
                <button
                    type="submit"
                    class="w-full bg-[#002147] text-white py-2.5 px-4 rounded text-sm font-semibold hover:bg-blue-900 transition-colors flex items-center justify-center shadow-lg"
                >
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Se connecter
                </button>
            </form>
        </div>

        <!-- Footer -->
        <div class="mt-5 text-center text-xs text-blue-200">
            <p>IEF LOUGA - 2025 | Bureau Planification</p>
        </div>
    </div>
</body>
</html>
