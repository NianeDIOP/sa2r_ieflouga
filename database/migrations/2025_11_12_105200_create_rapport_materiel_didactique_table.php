<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rapport_materiel_didactique', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rapport_id');
            $table->foreign('rapport_id')->references('id')->on('rapports')->onDelete('cascade');

            // DICTIONNAIRES
            $table->integer('dico_francais_total')->default(0);
            $table->integer('dico_francais_bon_etat')->default(0);
            $table->integer('dico_arabe_total')->default(0);
            $table->integer('dico_arabe_bon_etat')->default(0);
            $table->integer('dico_autre_total')->default(0);
            $table->integer('dico_autre_bon_etat')->default(0);

            // INSTRUMENTS DE GÉOMÉTRIE
            $table->integer('regle_plate_total')->default(0);
            $table->integer('regle_plate_bon_etat')->default(0);
            $table->integer('equerre_total')->default(0);
            $table->integer('equerre_bon_etat')->default(0);
            $table->integer('compas_total')->default(0);
            $table->integer('compas_bon_etat')->default(0);
            $table->integer('rapporteur_total')->default(0);
            $table->integer('rapporteur_bon_etat')->default(0);

            // INSTRUMENTS DE MESURE
            $table->integer('decametre_total')->default(0);
            $table->integer('decametre_bon_etat')->default(0);
            $table->integer('chaine_arpenteur_total')->default(0);
            $table->integer('chaine_arpenteur_bon_etat')->default(0);
            $table->integer('boussole_total')->default(0);
            $table->integer('boussole_bon_etat')->default(0);
            $table->integer('thermometre_total')->default(0);
            $table->integer('thermometre_bon_etat')->default(0);
            $table->integer('kit_capacite_total')->default(0);
            $table->integer('kit_capacite_bon_etat')->default(0);
            $table->integer('balance_total')->default(0);
            $table->integer('balance_bon_etat')->default(0);

            // MATÉRIEL PÉDAGOGIQUE
            $table->integer('globe_total')->default(0);
            $table->integer('globe_bon_etat')->default(0);
            $table->integer('cartes_murales_total')->default(0);
            $table->integer('cartes_murales_bon_etat')->default(0);
            $table->integer('planches_illustrees_total')->default(0);
            $table->integer('planches_illustrees_bon_etat')->default(0);
            $table->integer('kit_materiel_scientifique_total')->default(0);
            $table->integer('kit_materiel_scientifique_bon_etat')->default(0);
            $table->integer('autres_materiel_total')->default(0);
            $table->integer('autres_materiel_bon_etat')->default(0);

            $table->timestamps();
            $table->unique('rapport_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rapport_materiel_didactique');
    }
};
