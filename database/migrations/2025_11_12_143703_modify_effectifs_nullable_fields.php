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
        Schema::table('rapport_effectifs_classe', function (Blueprint $table) {
            $table->integer('nombre_classes')->nullable()->change();
            $table->integer('effectif_garcons')->nullable()->change();
            $table->integer('effectif_filles')->nullable()->change();
            $table->integer('effectif_total')->nullable()->change();
            $table->integer('redoublants_garcons')->nullable()->change();
            $table->integer('redoublants_filles')->nullable()->change();
            $table->integer('redoublants_total')->nullable()->change();
            $table->integer('abandons_garcons')->nullable()->change();
            $table->integer('abandons_filles')->nullable()->change();
            $table->integer('abandons_total')->nullable()->change();
            $table->integer('handicap_moteur_garcons')->nullable()->change();
            $table->integer('handicap_moteur_filles')->nullable()->change();
            $table->integer('handicap_moteur_total')->nullable()->change();
            $table->integer('handicap_visuel_garcons')->nullable()->change();
            $table->integer('handicap_visuel_filles')->nullable()->change();
            $table->integer('handicap_visuel_total')->nullable()->change();
            $table->integer('handicap_sourd_muet_garcons')->nullable()->change();
            $table->integer('handicap_sourd_muet_filles')->nullable()->change();
            $table->integer('handicap_sourd_muet_total')->nullable()->change();
            $table->integer('handicap_deficience_intel_garcons')->nullable()->change();
            $table->integer('handicap_deficience_intel_filles')->nullable()->change();
            $table->integer('handicap_deficience_intel_total')->nullable()->change();
            $table->integer('orphelins_garcons')->nullable()->change();
            $table->integer('orphelins_filles')->nullable()->change();
            $table->integer('orphelins_total')->nullable()->change();
            $table->integer('sans_extrait_garcons')->nullable()->change();
            $table->integer('sans_extrait_filles')->nullable()->change();
            $table->integer('sans_extrait_total')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Pas de rollback car on ne veut pas revenir aux default(0)
    }
};
