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
        Schema::table('rapport_dictionnaires', function (Blueprint $table) {
            // Supprimer les anciens champs
            $table->dropColumn([
                'dictionnaire_francais_eleves',
                'dictionnaire_francais_enseignants',
                'dictionnaire_francais_etat',
                'dictionnaire_national_eleves',
                'dictionnaire_national_enseignants', 
                'dictionnaire_national_etat',
                'dictionnaire_anglais_eleves',
                'dictionnaire_anglais_enseignants',
                'dictionnaire_anglais_etat'
            ]);
            
            // Ajouter les nouveaux champs compatibles avec la vue
            $table->integer('dico_francais_total')->default(0);
            $table->integer('dico_francais_bon_etat')->default(0);
            $table->integer('dico_arabe_total')->default(0);
            $table->integer('dico_arabe_bon_etat')->default(0);
            $table->integer('dico_autre_total')->default(0);
            $table->integer('dico_autre_bon_etat')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rapport_dictionnaires', function (Blueprint $table) {
            // Restaurer les anciens champs
            $table->dropColumn([
                'dico_francais_total',
                'dico_francais_bon_etat',
                'dico_arabe_total',
                'dico_arabe_bon_etat',
                'dico_autre_total',
                'dico_autre_bon_etat'
            ]);
            
            $table->integer('dictionnaire_francais_eleves')->nullable();
            $table->integer('dictionnaire_francais_enseignants')->nullable();
            $table->enum('dictionnaire_francais_etat', ['excellent', 'bon', 'moyen', 'mauvais'])->nullable();
            $table->integer('dictionnaire_national_eleves')->nullable();
            $table->integer('dictionnaire_national_enseignants')->nullable();
            $table->enum('dictionnaire_national_etat', ['excellent', 'bon', 'moyen', 'mauvais'])->nullable();
            $table->integer('dictionnaire_anglais_eleves')->nullable();
            $table->integer('dictionnaire_anglais_enseignants')->nullable();
            $table->enum('dictionnaire_anglais_etat', ['excellent', 'bon', 'moyen', 'mauvais'])->nullable();
        });
    }
};
