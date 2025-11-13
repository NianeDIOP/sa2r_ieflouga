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
        Schema::table('rapport_geometrie', function (Blueprint $table) {
            // Supprimer les anciens champs
            $table->dropColumn([
                'regles_graduees',
                'equerres', 
                'rapporteurs',
                'compas',
                'metres_ruban',
                'etat_instruments_geometrie',
                'solides_geometriques',
                'planches_geometrie',
                'kit_geometrie_enseignant'
            ]);
            
            // Ajouter les nouveaux champs compatibles avec la vue
            $table->integer('regle_plate_total')->default(0);
            $table->integer('regle_plate_bon_etat')->default(0);
            $table->integer('equerre_total')->default(0);
            $table->integer('equerre_bon_etat')->default(0);
            $table->integer('compas_total')->default(0);
            $table->integer('compas_bon_etat')->default(0);
            $table->integer('rapporteur_total')->default(0);
            $table->integer('rapporteur_bon_etat')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rapport_geometrie', function (Blueprint $table) {
            // Restaurer les anciens champs
            $table->dropColumn([
                'regle_plate_total',
                'regle_plate_bon_etat', 
                'equerre_total',
                'equerre_bon_etat',
                'compas_total',
                'compas_bon_etat',
                'rapporteur_total',
                'rapporteur_bon_etat'
            ]);
            
            $table->integer('regles_graduees')->nullable();
            $table->integer('equerres')->nullable();
            $table->integer('rapporteurs')->nullable();
            $table->integer('compas')->nullable();
            $table->integer('metres_ruban')->nullable();
            $table->enum('etat_instruments_geometrie', ['excellent', 'bon', 'moyen', 'mauvais'])->nullable();
            $table->integer('solides_geometriques')->nullable();
            $table->integer('planches_geometrie')->nullable();
            $table->integer('kit_geometrie_enseignant')->nullable();
        });
    }
};
