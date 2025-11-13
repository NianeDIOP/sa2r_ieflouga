<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer des administrateurs
        User::create([
            'type' => 'admin',
            'username' => 'admin',
            'email' => 'admin@ieflouga.sn',
            'password' => Hash::make('admin123'),
            'nom_complet' => 'Administrateur Principal',
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        User::create([
            'type' => 'admin',
            'username' => 'admin_ief',
            'email' => 'ief@louga.sn',
            'password' => Hash::make('ief2024'),
            'nom_complet' => 'Inspecteur IEF Louga',
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Créer des établissements de test
        User::create([
            'type' => 'etablissement',
            'code' => '1234567890',
            'email' => 'ecole1@ieflouga.sn',
            'password' => Hash::make('SA2R2024'),
            'nom' => 'École Primaire Louga Centre',
            'arrondissement' => 'Louga',
            'commune' => 'Louga',
            'zone' => 'Zone 1',
            'statut' => 'Public',
            'is_active' => true,
        ]);

        User::create([
            'type' => 'etablissement',
            'code' => '0987654321',
            'email' => 'college@ieflouga.sn',
            'password' => Hash::make('SA2R2024'),
            'nom' => 'CEM Louga',
            'arrondissement' => 'Louga',
            'commune' => 'Louga',
            'zone' => 'Zone 2',
            'statut' => 'Public',
            'is_active' => true,
        ]);

        User::create([
            'type' => 'etablissement',
            'code' => '5555555555',
            'email' => 'prive@ieflouga.sn',
            'password' => Hash::make('SA2R2024'),
            'nom' => 'École Privée Al Amine',
            'arrondissement' => 'Louga',
            'commune' => 'Louga',
            'zone' => 'Zone 1',
            'statut' => 'Privé',
            'is_active' => true,
        ]);
    }
}
