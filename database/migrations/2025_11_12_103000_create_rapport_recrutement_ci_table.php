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
        Schema::create('rapport_recrutement_ci', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('rapport_id');
            $table->foreign('rapport_id')
                  ->references('id')
                  ->on('rapports')
                  ->onDelete('cascade');

            // Recrutement CI (Cours d'Initiation)
            $table->integer('ci_nombre')->default(0)->comment('Nombre de CI recrutés');
            $table->string('ci_combinaison_1')->nullable();
            $table->string('ci_combinaison_2')->nullable();
            $table->string('ci_combinaison_3')->nullable();
            $table->string('ci_combinaison_autres')->nullable();

            // Effectifs CI par période
            $table->integer('ci_octobre_garcons')->default(0);
            $table->integer('ci_octobre_filles')->default(0);
            $table->integer('ci_octobre_total')->default(0)->comment('Calculé automatiquement');
            
            $table->integer('ci_janvier_garcons')->default(0);
            $table->integer('ci_janvier_filles')->default(0);
            $table->integer('ci_janvier_total')->default(0)->comment('Calculé automatiquement');
            
            $table->integer('ci_mai_garcons')->default(0);
            $table->integer('ci_mai_filles')->default(0);
            $table->integer('ci_mai_total')->default(0)->comment('Calculé automatiquement');

            // Statut du CI
            $table->enum('ci_statut', ['homologue', 'non_homologue'])->nullable();

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
        Schema::dropIfExists('rapport_recrutement_ci');
    }
};
