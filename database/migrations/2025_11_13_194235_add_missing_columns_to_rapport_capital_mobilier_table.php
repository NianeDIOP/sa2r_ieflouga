<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('rapport_capital_mobilier', function (Blueprint $table) {
            // Ajouter les colonnes manquantes pour Mobilier Élèves
            $table->integer('chaises_eleves_total')->default(0)->after('tables_bancs_bon_etat');
            $table->integer('chaises_eleves_bon_etat')->default(0)->after('chaises_eleves_total');
            $table->integer('tables_individuelles_total')->default(0)->after('chaises_eleves_bon_etat');
            $table->integer('tables_individuelles_bon_etat')->default(0)->after('tables_individuelles_total');
            
            // Ajouter les colonnes manquantes pour Mobilier Enseignants
            $table->integer('bureaux_maitre_total')->default(0)->after('tables_individuelles_bon_etat');
            $table->integer('bureaux_maitre_bon_etat')->default(0)->after('bureaux_maitre_total');
            $table->integer('chaises_maitre_total')->default(0)->after('bureaux_maitre_bon_etat');
            $table->integer('chaises_maitre_bon_etat')->default(0)->after('chaises_maitre_total');
            $table->integer('tableaux_total')->default(0)->after('chaises_maitre_bon_etat');
            $table->integer('tableaux_bon_etat')->default(0)->after('tableaux_total');
            
            // Ajouter les colonnes manquantes pour Mobilier Administratif
            $table->integer('bureaux_admin_total')->default(0)->after('armoires_bon_etat');
            $table->integer('bureaux_admin_bon_etat')->default(0)->after('bureaux_admin_total');
            $table->integer('chaises_admin_total')->default(0)->after('bureaux_admin_bon_etat');
            $table->integer('chaises_admin_bon_etat')->default(0)->after('chaises_admin_total');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rapport_capital_mobilier', function (Blueprint $table) {
            $table->dropColumn([
                'chaises_eleves_total',
                'chaises_eleves_bon_etat',
                'tables_individuelles_total',
                'tables_individuelles_bon_etat',
                'bureaux_maitre_total',
                'bureaux_maitre_bon_etat',
                'chaises_maitre_total',
                'chaises_maitre_bon_etat',
                'tableaux_total',
                'tableaux_bon_etat',
                'bureaux_admin_total',
                'bureaux_admin_bon_etat',
                'chaises_admin_total',
                'chaises_admin_bon_etat',
            ]);
        });
    }
};
