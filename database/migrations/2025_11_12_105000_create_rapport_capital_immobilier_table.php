<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rapport_capital_immobilier', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rapport_id');
            $table->foreign('rapport_id')->references('id')->on('rapports')->onDelete('cascade');

            // SALLES DE CLASSE
            $table->integer('salles_dur_total')->default(0);
            $table->integer('salles_dur_bon_etat')->default(0);
            $table->integer('abris_provisoires_total')->default(0);
            $table->integer('abris_provisoires_bon_etat')->default(0);

            // BÂTIMENTS ADMINISTRATIFS
            $table->integer('bloc_admin_total')->default(0);
            $table->integer('bloc_admin_bon_etat')->default(0);
            $table->integer('magasin_total')->default(0);
            $table->integer('magasin_bon_etat')->default(0);

            // SALLES SPÉCIALISÉES
            $table->integer('salle_informatique_total')->default(0);
            $table->integer('salle_informatique_bon_etat')->default(0);
            $table->integer('salle_bibliotheque_total')->default(0);
            $table->integer('salle_bibliotheque_bon_etat')->default(0);
            $table->integer('cuisine_total')->default(0);
            $table->integer('cuisine_bon_etat')->default(0);
            $table->integer('refectoire_total')->default(0);
            $table->integer('refectoire_bon_etat')->default(0);

            // TOILETTES
            $table->integer('toilettes_enseignants_total')->default(0);
            $table->integer('toilettes_enseignants_bon_etat')->default(0);
            $table->integer('toilettes_garcons_total')->default(0);
            $table->integer('toilettes_garcons_bon_etat')->default(0);
            $table->integer('toilettes_filles_total')->default(0);
            $table->integer('toilettes_filles_bon_etat')->default(0);
            $table->integer('toilettes_mixtes_total')->default(0);
            $table->integer('toilettes_mixtes_bon_etat')->default(0);

            // LOGEMENTS
            $table->integer('logement_directeur_total')->default(0);
            $table->integer('logement_directeur_bon_etat')->default(0);
            $table->integer('logement_gardien_total')->default(0);
            $table->integer('logement_gardien_bon_etat')->default(0);

            // AUTRES
            $table->integer('autres_infrastructures_total')->default(0);
            $table->integer('autres_infrastructures_bon_etat')->default(0);

            $table->timestamps();
            $table->unique('rapport_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rapport_capital_immobilier');
    }
};
