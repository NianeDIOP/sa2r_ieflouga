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
        // Modifier rapport_structures_communautaires
        Schema::table('rapport_structures_communautaires', function (Blueprint $table) {
            $table->integer('cge_hommes')->nullable()->change();
            $table->integer('cge_femmes')->nullable()->change();
            $table->integer('cge_total')->nullable()->change();
            $table->integer('gscol_garcons')->nullable()->change();
            $table->integer('gscol_filles')->nullable()->change();
            $table->integer('gscol_total')->nullable()->change();
            $table->integer('ape_hommes')->nullable()->change();
            $table->integer('ape_femmes')->nullable()->change();
            $table->integer('ape_total')->nullable()->change();
            $table->integer('ame_nombre')->nullable()->change();
        });

        // Modifier rapport_ressources_financieres
        Schema::table('rapport_ressources_financieres', function (Blueprint $table) {
            $table->decimal('subvention_etat_montant', 12, 2)->nullable()->change();
            $table->decimal('subvention_partenaires_montant', 12, 2)->nullable()->change();
            $table->decimal('subvention_collectivites_montant', 12, 2)->nullable()->change();
            $table->decimal('subvention_communaute_montant', 12, 2)->nullable()->change();
            $table->decimal('ressources_generees_montant', 12, 2)->nullable()->change();
            $table->decimal('total_ressources', 12, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revenir aux valeurs par défaut
        Schema::table('rapport_structures_communautaires', function (Blueprint $table) {
            $table->integer('cge_hommes')->default(0)->change();
            $table->integer('cge_femmes')->default(0)->change();
            $table->integer('cge_total')->default(0)->change();
            $table->integer('gscol_garcons')->default(0)->change();
            $table->integer('gscol_filles')->default(0)->change();
            $table->integer('gscol_total')->default(0)->change();
            $table->integer('ape_hommes')->default(0)->change();
            $table->integer('ape_femmes')->default(0)->change();
            $table->integer('ape_total')->default(0)->change();
            $table->integer('ame_nombre')->default(0)->change();
        });

        Schema::table('rapport_ressources_financieres', function (Blueprint $table) {
            $table->decimal('subvention_etat_montant', 12, 2)->default(0)->change();
            $table->decimal('subvention_partenaires_montant', 12, 2)->default(0)->change();
            $table->decimal('subvention_collectivites_montant', 12, 2)->default(0)->change();
            $table->decimal('subvention_communaute_montant', 12, 2)->default(0)->change();
            $table->decimal('ressources_generees_montant', 12, 2)->default(0)->change();
            $table->decimal('total_ressources', 12, 2)->default(0)->change();
        });
    }
};
