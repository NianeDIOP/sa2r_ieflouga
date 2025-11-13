<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Etablissement;
use Illuminate\Support\Facades\Hash;

class EtablissementTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer l'établissement EE ARTILLERIE
        $etablissement = Etablissement::create([
            'code' => 'EE001',
            'etablissement' => 'EE ARTILLERIE',
            'arrondissement' => 'Dakar-Plateau',
            'commune' => 'Dakar',
            'zone' => 'Zone 1',
            'statut' => 'Public',
        ]);

        // Créer le compte utilisateur pour cet établissement
        User::create([
            'type' => 'etablissement',
            'code' => 'EE001',
            'nom' => 'EE ARTILLERIE',
            'email' => 'artillerie@education.sn',
            'password' => Hash::make('123456'),
            'arrondissement' => 'Dakar-Plateau',
            'commune' => 'Dakar',
            'zone' => 'Zone 1',
            'statut' => 'Public',
            'is_active' => true,
            'etablissement_id' => $etablissement->id,
        ]);

        echo "✅ Établissement EE ARTILLERIE créé avec succès\n";
        echo "Code: EE001\n";
        echo "Email: artillerie@education.sn\n";
        echo "Password: 123456\n";
    }
}
