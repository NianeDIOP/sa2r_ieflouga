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
        Schema::create('rapport_effectifs_classe', function (Blueprint $table) {
            $table->id();
            
            // Relation avec rapport
            $table->unsignedBigInteger('rapport_id');
            $table->foreign('rapport_id')
                  ->references('id')
                  ->on('rapports')
                  ->onDelete('cascade');
            
            // Niveau scolaire
            $table->enum('niveau', ['CI', 'CP', 'CE1', 'CE2', 'CM1', 'CM2']);
            
            // NOMBRE DE CLASSES
            $table->integer('nombre_classes')->nullable();
            
            // EFFECTIFS GLOBAUX
            $table->integer('effectif_garcons')->nullable();
            $table->integer('effectif_filles')->nullable();
            $table->integer('effectif_total')->nullable(); // Calculé
            
            // REDOUBLANTS
            $table->integer('redoublants_garcons')->nullable();
            $table->integer('redoublants_filles')->nullable();
            $table->integer('redoublants_total')->nullable(); // Calculé
            
            // ABANDONS
            $table->integer('abandons_garcons')->nullable();
            $table->integer('abandons_filles')->nullable();
            $table->integer('abandons_total')->nullable(); // Calculé
            
            // HANDICAP MOTEUR
            $table->integer('handicap_moteur_garcons')->nullable();
            $table->integer('handicap_moteur_filles')->nullable();
            $table->integer('handicap_moteur_total')->nullable(); // Calculé
            
            // HANDICAP VISUEL
            $table->integer('handicap_visuel_garcons')->nullable();
            $table->integer('handicap_visuel_filles')->nullable();
            $table->integer('handicap_visuel_total')->nullable(); // Calculé
            
            // HANDICAP SOURD/MUET
            $table->integer('handicap_sourd_muet_garcons')->nullable();
            $table->integer('handicap_sourd_muet_filles')->nullable();
            $table->integer('handicap_sourd_muet_total')->nullable(); // Calculé
            
            // HANDICAP DÉFICIENCE INTELLECTUELLE
            $table->integer('handicap_deficience_intel_garcons')->nullable();
            $table->integer('handicap_deficience_intel_filles')->nullable();
            $table->integer('handicap_deficience_intel_total')->nullable(); // Calculé
            
            // ORPHELINS
            $table->integer('orphelins_garcons')->nullable();
            $table->integer('orphelins_filles')->nullable();
            $table->integer('orphelins_total')->nullable(); // Calculé
            
            // SANS EXTRAIT DE NAISSANCE
            $table->integer('sans_extrait_garcons')->nullable();
            $table->integer('sans_extrait_filles')->nullable();
            $table->integer('sans_extrait_total')->nullable(); // Calculé
            
            $table->timestamps();
            
            // Index pour performance
            $table->index(['rapport_id', 'niveau']);
            
            // Contrainte unique : un seul enregistrement par rapport et par niveau
            $table->unique(['rapport_id', 'niveau']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rapport_effectifs_classe');
    }
};
