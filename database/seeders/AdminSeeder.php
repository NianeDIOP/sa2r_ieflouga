<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer le compte admin
        User::create([
            'type' => 'admin',
            'username' => 'admin',
            'nom_complet' => 'Administrateur Système',
            'email' => 'admin@education.sn',
            'password' => Hash::make('admin123'),
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        echo "✅ Compte admin créé avec succès\n";
        echo "Username: admin\n";
        echo "Email: admin@education.sn\n";
        echo "Password: admin123\n";
    }
}
