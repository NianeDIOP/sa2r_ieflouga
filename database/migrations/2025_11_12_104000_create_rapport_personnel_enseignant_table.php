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
        Schema::create('rapport_personnel_enseignant', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('rapport_id');
            $table->foreign('rapport_id')
                  ->references('id')
                  ->on('rapports')
                  ->onDelete('cascade');

            // RÉPARTITION PAR SPÉCIALITÉ
            $table->integer('titulaires_hommes')->default(0);
            $table->integer('titulaires_femmes')->default(0);
            $table->integer('titulaires_total')->default(0)->comment('Calculé automatiquement');

            $table->integer('vacataires_hommes')->default(0);
            $table->integer('vacataires_femmes')->default(0);
            $table->integer('vacataires_total')->default(0)->comment('Calculé automatiquement');

            $table->integer('volontaires_hommes')->default(0);
            $table->integer('volontaires_femmes')->default(0);
            $table->integer('volontaires_total')->default(0)->comment('Calculé automatiquement');

            $table->integer('contractuels_hommes')->default(0);
            $table->integer('contractuels_femmes')->default(0);
            $table->integer('contractuels_total')->default(0)->comment('Calculé automatiquement');

            $table->integer('communautaires_hommes')->default(0);
            $table->integer('communautaires_femmes')->default(0);
            $table->integer('communautaires_total')->default(0)->comment('Calculé automatiquement');

            // RÉPARTITION PAR CORPS
            $table->integer('instituteurs_hommes')->default(0);
            $table->integer('instituteurs_femmes')->default(0);
            $table->integer('instituteurs_total')->default(0)->comment('Calculé automatiquement');

            $table->integer('instituteurs_adjoints_hommes')->default(0);
            $table->integer('instituteurs_adjoints_femmes')->default(0);
            $table->integer('instituteurs_adjoints_total')->default(0)->comment('Calculé automatiquement');

            $table->integer('professeurs_hommes')->default(0);
            $table->integer('professeurs_femmes')->default(0);
            $table->integer('professeurs_total')->default(0)->comment('Calculé automatiquement');

            // RÉPARTITION PAR DIPLÔMES
            $table->integer('bac_hommes')->default(0);
            $table->integer('bac_femmes')->default(0);
            $table->integer('bac_total')->default(0)->comment('Calculé automatiquement');

            $table->integer('bfem_hommes')->default(0);
            $table->integer('bfem_femmes')->default(0);
            $table->integer('bfem_total')->default(0)->comment('Calculé automatiquement');

            $table->integer('cfee_hommes')->default(0);
            $table->integer('cfee_femmes')->default(0);
            $table->integer('cfee_total')->default(0)->comment('Calculé automatiquement');

            $table->integer('licence_hommes')->default(0);
            $table->integer('licence_femmes')->default(0);
            $table->integer('licence_total')->default(0)->comment('Calculé automatiquement');

            $table->integer('master_hommes')->default(0);
            $table->integer('master_femmes')->default(0);
            $table->integer('master_total')->default(0)->comment('Calculé automatiquement');

            $table->integer('autres_diplomes_hommes')->default(0);
            $table->integer('autres_diplomes_femmes')->default(0);
            $table->integer('autres_diplomes_total')->default(0)->comment('Calculé automatiquement');

            // COMPÉTENCES TIC
            $table->integer('enseignants_formes_tic_hommes')->default(0);
            $table->integer('enseignants_formes_tic_femmes')->default(0);
            $table->integer('enseignants_formes_tic_total')->default(0)->comment('Calculé automatiquement');

            // TOTAUX GÉNÉRAUX
            $table->integer('total_personnel_hommes')->default(0)->comment('Somme de tous les hommes');
            $table->integer('total_personnel_femmes')->default(0)->comment('Somme de toutes les femmes');
            $table->integer('total_personnel')->default(0)->comment('Total général');

            // RATIOS CALCULÉS
            $table->decimal('ratio_eleves_enseignant', 8, 2)->default(0)->comment('Nb élèves / Nb enseignants');
            $table->decimal('taux_feminisation', 5, 2)->default(0)->comment('(Femmes/Total)*100');

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
        Schema::dropIfExists('rapport_personnel_enseignant');
    }
};
