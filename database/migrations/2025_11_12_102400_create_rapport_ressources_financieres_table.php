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
        Schema::create('rapport_ressources_financieres', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('rapport_id');
            $table->foreign('rapport_id')
                  ->references('id')
                  ->on('rapports')
                  ->onDelete('cascade');

            // Subvention État
            $table->boolean('subvention_etat_existe')->default(false);
            $table->decimal('subvention_etat_montant', 12, 2)->nullable();

            // Subvention Partenaires
            $table->boolean('subvention_partenaires_existe')->default(false);
            $table->decimal('subvention_partenaires_montant', 12, 2)->nullable();

            // Subvention Collectivités Locales
            $table->boolean('subvention_collectivites_existe')->default(false);
            $table->decimal('subvention_collectivites_montant', 12, 2)->nullable();

            // Subvention Communauté
            $table->boolean('subvention_communaute_existe')->default(false);
            $table->decimal('subvention_communaute_montant', 12, 2)->nullable();

            // Ressources Générées (AGR)
            $table->boolean('ressources_generees_existe')->default(false);
            $table->decimal('ressources_generees_montant', 12, 2)->nullable();

            // Total calculé automatiquement
            $table->decimal('total_ressources', 12, 2)->nullable()->comment('Somme de toutes les ressources');

            $table->timestamps();

            // Un seul enregistrement par rapport
            $table->unique('rapport_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rapport_ressources_financieres');
    }
};
