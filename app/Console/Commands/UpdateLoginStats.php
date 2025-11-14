<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class UpdateLoginStats extends Command
{
    protected $signature = 'update:login {code}';
    protected $description = 'Mettre à jour les statistiques de connexion pour un établissement';

    public function handle()
    {
        $code = $this->argument('code');
        
        $user = User::where('code', $code)->where('type', 'etablissement')->first();
        
        if (!$user) {
            $this->error("Utilisateur avec code {$code} non trouvé");
            return;
        }

        $user->login_count = 1;
        $user->last_login_at = now();
        $user->save();

        $this->info("✅ Statistiques mises à jour pour {$code}");
        $this->info("Login count: " . $user->login_count);
        $this->info("Last login: " . $user->last_login_at->format('d/m/Y H:i'));
    }
}
