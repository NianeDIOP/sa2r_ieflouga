<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\RapportInfoDirecteur;
use App\Models\Rapport;

class SyncDirecteurInfo extends Command
{
    protected $signature = 'sync:directeur {code?}';
    protected $description = 'Synchroniser les informations directeur depuis les rapports vers les comptes';

    public function handle()
    {
        $code = $this->argument('code');
        
        if ($code) {
            // Synchroniser un seul établissement
            $this->syncOne($code);
        } else {
            // Synchroniser tous
            $this->syncAll();
        }
    }

    private function syncOne($code)
    {
        $user = User::where('code', $code)->where('type', 'etablissement')->first();
        
        if (!$user) {
            $this->error("Utilisateur avec code {$code} non trouvé");
            return;
        }

        if (!$user->etablissement) {
            $this->error("Pas d'établissement associé au code {$code}");
            return;
        }

        // Récupérer le dernier rapport avec infos directeur
        $rapport = Rapport::where('etablissement_id', $user->etablissement->id)
            ->whereHas('infoDirecteur')
            ->latest()
            ->first();

        if (!$rapport || !$rapport->infoDirecteur) {
            $this->warn("Aucun rapport avec infos directeur trouvé pour {$code}");
            $this->info("Directeur actuel dans users: " . ($user->directeur_nom ?? 'NULL'));
            return;
        }

        $infoDirecteur = $rapport->infoDirecteur;
        
        $this->info("=== Synchronisation pour {$code} ===");
        $this->info("Établissement: " . $user->etablissement->etablissement);
        $this->info("Ancien nom: " . ($user->directeur_nom ?? 'NULL'));
        $this->info("Nouveau nom: " . ($infoDirecteur->directeur_nom ?? 'NULL'));
        $this->info("Ancien tél: " . ($user->directeur_telephone ?? 'NULL'));
        $this->info("Nouveau tél: " . ($infoDirecteur->directeur_contact_1 ?? 'NULL'));

        $user->update([
            'directeur_nom' => $infoDirecteur->directeur_nom,
            'directeur_telephone' => $infoDirecteur->directeur_contact_1,
        ]);

        $this->info("✅ Synchronisation effectuée avec succès!");
    }

    private function syncAll()
    {
        $this->info("Synchronisation de tous les établissements...");
        
        $users = User::where('type', 'etablissement')
            ->whereHas('etablissement')
            ->get();

        $synced = 0;
        $notFound = 0;

        foreach ($users as $user) {
            $rapport = Rapport::where('etablissement_id', $user->etablissement->id)
                ->whereHas('infoDirecteur')
                ->latest()
                ->first();

            if ($rapport && $rapport->infoDirecteur) {
                $user->update([
                    'directeur_nom' => $rapport->infoDirecteur->directeur_nom,
                    'directeur_telephone' => $rapport->infoDirecteur->directeur_contact_1,
                ]);
                $synced++;
            } else {
                $notFound++;
            }
        }

        $this->info("✅ Synchronisation terminée!");
        $this->info("- Synchronisés: {$synced}");
        $this->info("- Sans données: {$notFound}");
    }
}
