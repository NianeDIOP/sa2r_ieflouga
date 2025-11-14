<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Afficher le formulaire de connexion unifié
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Traiter la connexion - détecte automatiquement le type d'utilisateur
     */
    public function login(Request $request)
    {
        $request->validate([
            'identifiant' => 'required|string',
            'password' => 'required|string',
        ], [
            'identifiant.required' => 'L\'identifiant est requis',
            'password.required' => 'Le mot de passe est requis',
        ]);

        $identifiant = $request->identifiant;
        $password = $request->password;

        // Détecter le type d'identifiant
        // Si c'est 10 chiffres = Établissement
        // Sinon = Admin
        if (preg_match('/^[0-9]{10}$/', $identifiant)) {
            return $this->loginEtablissement($identifiant, $password, $request);
        } else {
            return $this->loginAdmin($identifiant, $password, $request);
        }
    }

    /**
     * Connexion Admin
     */
    private function loginAdmin($username, $password, $request)
    {
        // Rechercher l'admin par username
        $user = User::where('username', $username)
            ->where('type', 'admin')
            ->where('is_active', true)
            ->first();

        // Debug
        Log::info('Login Admin Attempt', [
            'username' => $username,
            'user_found' => $user ? 'yes' : 'no',
            'user_id' => $user ? $user->id : null,
            'password_check' => $user ? Hash::check($password, $user->password) : false
        ]);

        // Vérifier si l'utilisateur existe et si le mot de passe est correct
        if ($user && Hash::check($password, $user->password)) {
            Auth::guard('admin')->login($user, $request->filled('remember'));
            $request->session()->regenerate();
            
            // Enregistrer la connexion
            $user->increment('login_count');
            $user->update(['last_login_at' => now()]);
            
            Log::info('Login Admin Success', ['user_id' => $user->id]);
            
            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Bienvenue, ' . $user->nom_complet);
        }

        Log::warning('Login Admin Failed', ['username' => $username]);

        return back()->with('error', 'Identifiant ou mot de passe incorrect.');
    }

    /**
     * Connexion Établissement
     */
    private function loginEtablissement($code, $password, $request)
    {
        // Rechercher l'établissement par code
        $user = User::where('code', $code)
            ->where('type', 'etablissement')
            ->where('is_active', true)
            ->first();

        // Debug
        Log::info('Login Etablissement Attempt', [
            'code' => $code,
            'user_found' => $user ? 'yes' : 'no',
            'user_id' => $user ? $user->id : null,
            'password_check' => $user ? Hash::check($password, $user->password) : false
        ]);

        // Vérifier si l'utilisateur existe et si le mot de passe est correct
        if ($user && Hash::check($password, $user->password)) {
            Auth::guard('etablissement')->login($user, $request->filled('remember'));
            $request->session()->regenerate();
            
            // Enregistrer la connexion
            $user->increment('login_count');
            $user->update(['last_login_at' => now()]);
            
            Log::info('Login Etablissement Success', ['user_id' => $user->id]);
            
            return redirect()->intended(route('etablissement.dashboard'))
                ->with('success', 'Bienvenue, ' . $user->nom);
        }

        Log::warning('Login Etablissement Failed', ['code' => $code]);
        
        return back()->with('error', 'Code établissement ou mot de passe incorrect.');
    }

    /**
     * Déconnexion
     */
    public function logout(Request $request)
    {
        // Détecter quel guard est actif et déconnecter
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        } elseif (Auth::guard('etablissement')->check()) {
            Auth::guard('etablissement')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Déconnexion réussie !');
    }
}
